<?php

namespace App\Services;

use App\Models\Race;
use App\Models\RaceSession;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class CalendarExportService
{
    /**
     * Generate ICS content for all races in a season
     */
    public function generateFullSeasonIcs(int $season = null): string
    {
        $season = $season ?? now()->year;
        
        $races = Race::with(['circuit', 'sessions'])
            ->where('season', $season)
            ->orderBy('round')
            ->get();

        return $this->generateIcs($races, "F1 {$season} Season Calendar");
    }

    /**
     * Generate ICS content for a single race weekend
     */
    public function generateRaceWeekendIcs(Race $race): string
    {
        return $this->generateIcs(collect([$race]), $race->name);
    }

    /**
     * Generate ICS content from a collection of races
     */
    protected function generateIcs(Collection $races, string $calendarName): string
    {
        $ics = $this->getIcsHeader($calendarName);

        foreach ($races as $race) {
            // Add all sessions if available
            if ($race->sessions && $race->sessions->count() > 0) {
                foreach ($race->sessions as $session) {
                    $ics .= $this->createSessionEvent($race, $session);
                }
            } else {
                // Just add the main race if no session details
                $ics .= $this->createRaceEvent($race);
            }
        }

        $ics .= "END:VCALENDAR\r\n";

        return $ics;
    }

    /**
     * Create ICS header
     */
    protected function getIcsHeader(string $calendarName): string
    {
        return "BEGIN:VCALENDAR\r\n" .
            "VERSION:2.0\r\n" .
            "PRODID:-//F1 Weekly//Race Calendar//EN\r\n" .
            "CALSCALE:GREGORIAN\r\n" .
            "METHOD:PUBLISH\r\n" .
            "X-WR-CALNAME:{$calendarName}\r\n" .
            "X-WR-TIMEZONE:UTC\r\n";
    }

    /**
     * Create an event for a race session (FP1, Qualifying, etc.)
     */
    protected function createSessionEvent(Race $race, RaceSession $session): string
    {
        $startTime = $this->getSessionDateTime($session);
        $endTime = $startTime->copy()->addHours($this->getSessionDuration($session->type));
        
        $summary = $this->escapeIcs("{$race->name} - {$session->type_display}");
        $description = $this->escapeIcs($this->buildDescription($race, $session));
        $location = $this->escapeIcs($this->buildLocation($race));
        $uid = "f1-{$race->season}-r{$race->round}-{$session->type}@f1weekly.com";

        return $this->buildEvent($uid, $startTime, $endTime, $summary, $description, $location);
    }

    /**
     * Create an event for a race (when no session details available)
     */
    protected function createRaceEvent(Race $race): string
    {
        $startTime = $race->race_date;
        $endTime = $startTime->copy()->addHours(2);
        
        $summary = $this->escapeIcs($race->name);
        $description = $this->escapeIcs("Formula 1 {$race->name}\nRound {$race->round} of the {$race->season} season");
        $location = $this->escapeIcs($this->buildLocation($race));
        $uid = "f1-{$race->season}-r{$race->round}@f1weekly.com";

        return $this->buildEvent($uid, $startTime, $endTime, $summary, $description, $location);
    }

    /**
     * Build a single VEVENT block
     */
    protected function buildEvent(string $uid, Carbon $start, Carbon $end, string $summary, string $description, string $location): string
    {
        $now = now()->format('Ymd\THis\Z');
        $startStr = $start->format('Ymd\THis\Z');
        $endStr = $end->format('Ymd\THis\Z');

        return "BEGIN:VEVENT\r\n" .
            "UID:{$uid}\r\n" .
            "DTSTAMP:{$now}\r\n" .
            "DTSTART:{$startStr}\r\n" .
            "DTEND:{$endStr}\r\n" .
            "SUMMARY:{$summary}\r\n" .
            "DESCRIPTION:{$description}\r\n" .
            "LOCATION:{$location}\r\n" .
            "STATUS:CONFIRMED\r\n" .
            "SEQUENCE:0\r\n" .
            "END:VEVENT\r\n";
    }

    /**
     * Get the datetime for a session
     */
    protected function getSessionDateTime(RaceSession $session): Carbon
    {
        if ($session->date && $session->time) {
            return Carbon::parse($session->date->format('Y-m-d') . ' ' . $session->time->format('H:i:s'));
        }
        
        // Fallback to race date with estimated times
        $race = $session->race;
        $baseDate = $race->race_date->copy();
        
        return match($session->type) {
            'FP1' => $baseDate->subDays(2)->setTime(11, 30),
            'FP2' => $baseDate->subDays(2)->setTime(15, 0),
            'FP3' => $baseDate->subDays(1)->setTime(11, 30),
            'qualifying' => $baseDate->subDays(1)->setTime(15, 0),
            'sprint_qualifying' => $baseDate->subDays(1)->setTime(15, 0),
            'sprint' => $baseDate->setTime(11, 0),
            'race' => $baseDate,
            default => $baseDate,
        };
    }

    /**
     * Get estimated duration for each session type (in hours)
     */
    protected function getSessionDuration(string $type): float
    {
        return match($type) {
            'FP1', 'FP2', 'FP3' => 1.0,
            'qualifying', 'sprint_qualifying' => 1.0,
            'sprint' => 0.5,
            'race' => 2.0,
            default => 1.0,
        };
    }

    /**
     * Build description text
     */
    protected function buildDescription(Race $race, ?RaceSession $session = null): string
    {
        $parts = [
            "Formula 1 {$race->name}",
            "Round {$race->round} of the {$race->season} season",
        ];

        if ($race->circuit) {
            $parts[] = "Circuit: {$race->circuit->name}";
            if ($race->laps) {
                $parts[] = "Laps: {$race->laps}";
            }
        }

        $parts[] = "More info: " . route('races.show', $race);

        return implode("\\n", $parts);
    }

    /**
     * Build location string
     */
    protected function buildLocation(Race $race): string
    {
        if ($race->circuit) {
            return "{$race->circuit->name}, {$race->circuit->city}, {$race->circuit->country}";
        }
        return '';
    }

    /**
     * Escape special characters for ICS format
     */
    protected function escapeIcs(string $text): string
    {
        $text = str_replace("\\", "\\\\", $text);
        $text = str_replace(",", "\\,", $text);
        $text = str_replace(";", "\\;", $text);
        $text = str_replace("\n", "\\n", $text);
        return $text;
    }

    /**
     * Generate Google Calendar URL for a race
     */
    public function getGoogleCalendarUrl(Race $race): string
    {
        $startTime = $race->race_date->format('Ymd\THis\Z');
        $endTime = $race->race_date->copy()->addHours(2)->format('Ymd\THis\Z');
        
        $params = [
            'action' => 'TEMPLATE',
            'text' => $race->name,
            'dates' => $startTime . '/' . $endTime,
            'details' => "Formula 1 {$race->name}\nRound {$race->round} of the {$race->season} season\n\n" . route('races.show', $race),
            'location' => $this->buildLocation($race),
        ];

        return 'https://calendar.google.com/calendar/render?' . http_build_query($params);
    }

    /**
     * Generate Outlook Web Calendar URL for a race
     */
    public function getOutlookUrl(Race $race): string
    {
        $startTime = $race->race_date->format('Y-m-d\TH:i:s');
        $endTime = $race->race_date->copy()->addHours(2)->format('Y-m-d\TH:i:s');
        
        $params = [
            'path' => '/calendar/action/compose',
            'rru' => 'addevent',
            'subject' => $race->name,
            'startdt' => $startTime,
            'enddt' => $endTime,
            'body' => "Formula 1 {$race->name} - Round {$race->round}",
            'location' => $this->buildLocation($race),
        ];

        return 'https://outlook.live.com/calendar/0/deeplink/compose?' . http_build_query($params);
    }
}
