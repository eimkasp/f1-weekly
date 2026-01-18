<?php

namespace App\Http\Controllers;

use App\Models\Race;
use App\Services\CalendarExportService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CalendarExportController extends Controller
{
    public function __construct(
        protected CalendarExportService $calendarService
    ) {}

    /**
     * Download full season calendar as ICS
     */
    public function downloadFullCalendar(Request $request): Response
    {
        $season = $request->get('season', now()->year);
        
        $ics = $this->calendarService->generateFullSeasonIcs($season);
        
        return response($ics)
            ->header('Content-Type', 'text/calendar; charset=utf-8')
            ->header('Content-Disposition', "attachment; filename=\"f1-{$season}-calendar.ics\"");
    }

    /**
     * Download single race weekend calendar as ICS
     */
    public function downloadRaceCalendar(Race $race): Response
    {
        $ics = $this->calendarService->generateRaceWeekendIcs($race);
        
        $filename = str_replace(' ', '-', strtolower($race->name)) . '.ics';
        
        return response($ics)
            ->header('Content-Type', 'text/calendar; charset=utf-8')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }

    /**
     * Get Google Calendar link for a race
     */
    public function googleCalendarLink(Race $race)
    {
        $url = $this->calendarService->getGoogleCalendarUrl($race);
        
        return redirect($url);
    }

    /**
     * Get Outlook Calendar link for a race
     */
    public function outlookCalendarLink(Race $race)
    {
        $url = $this->calendarService->getOutlookUrl($race);
        
        return redirect($url);
    }

    /**
     * Show calendar export options
     */
    public function exportOptions()
    {
        $races = Race::with('circuit')
            ->where('season', now()->year)
            ->where('race_date', '>=', now())
            ->orderBy('race_date')
            ->get();

        return view('pages.calendar-export', [
            'races' => $races,
            'season' => now()->year,
        ]);
    }
}
