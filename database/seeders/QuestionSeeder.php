<?php

namespace Database\Seeders;

use App\Models\Question;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        $questions = [
            [
                'question' => 'Which driver holds the record for the most World Championship titles?',
                'options' => ['Lewis Hamilton', 'Michael Schumacher', 'Both of them', 'Max Verstappen'],
                'correct_answer' => 'Both of them',
                'explanation' => 'Lewis Hamilton and Michael Schumacher are tied with 7 World Championship titles each.',
                'category' => 'history',
                'difficulty' => 'easy',
            ],
            [
                'question' => 'Who was the youngest ever Formula 1 World Champion?',
                'options' => ['Max Verstappen', 'Sebastian Vettel', 'Lewis Hamilton', 'Fernando Alonso'],
                'correct_answer' => 'Sebastian Vettel',
                'explanation' => 'Sebastian Vettel won his first title in 2010 at age 23 years and 134 days.',
                'category' => 'history',
                'difficulty' => 'medium',
            ],
            [
                'question' => 'Which team has won the most Constructors\' Championships?',
                'options' => ['McLaren', 'Williams', 'Mercedes', 'Ferrari'],
                'correct_answer' => 'Ferrari',
                'explanation' => 'Ferrari holds the record with 16 Constructors\' Championships.',
                'category' => 'history',
                'difficulty' => 'easy',
            ],
            [
                'question' => 'What does DRS stand for?',
                'options' => ['Driver Racing System', 'Drag Reduction System', 'Downforce Regulation System', 'Dynamic Rear Stabilization'],
                'correct_answer' => 'Drag Reduction System',
                'explanation' => 'DRS (Drag Reduction System) opens the rear wing flap to reduce drag and increase top speed.',
                'category' => 'technical',
                'difficulty' => 'easy',
            ],
            [
                'question' => 'In which year did the first official Formula 1 World Championship take place?',
                'options' => ['1948', '1950', '1955', '1960'],
                'correct_answer' => '1950',
                'explanation' => 'The inaugural Formula 1 World Championship season began in 1950.',
                'category' => 'history',
                'difficulty' => 'medium',
            ],
            [
                'question' => 'Which circuit is known as the "Temple of Speed"?',
                'options' => ['Silverstone', 'Spa-Francorchamps', 'Monza', 'Suzuka'],
                'correct_answer' => 'Monza',
                'explanation' => 'The Autodromo Nazionale Monza is nicknamed the "Temple of Speed" due to its long straights and high average speeds.',
                'category' => 'circuits',
                'difficulty' => 'easy',
            ],
            [
                'question' => 'Who is the only driver to win the Triple Crown of Motorsport?',
                'options' => ['Graham Hill', 'Jim Clark', 'Mario Andretti', 'Fernando Alonso'],
                'correct_answer' => 'Graham Hill',
                'explanation' => 'Graham Hill is the only driver to have won the Monaco GP, Indianapolis 500, and 24 Hours of Le Mans.',
                'category' => 'history',
                'difficulty' => 'hard',
            ],
            [
                'question' => 'Which tire compound is marked with a Red stripe?',
                'options' => ['Hard', 'Medium', 'Soft', 'Intermediate'],
                'correct_answer' => 'Soft',
                'explanation' => 'Pirelli marks the Softest compound available for the weekend with a Red stripe.',
                'category' => 'technical',
                'difficulty' => 'easy',
            ],
            [
                'question' => 'How many points are awarded for a Race Win?',
                'options' => ['20', '25', '10', '15'],
                'correct_answer' => '25',
                'explanation' => 'Since 2010, 25 points are awarded to the winner of a Grand Prix.',
                'category' => 'rules',
                'difficulty' => 'easy',
            ],
            [
                'question' => 'Who was the first ever female F1 driver?',
                'options' => ['Lella Lombardi', 'Maria Teresa de Filippis', 'Susie Wolff', 'Giovanna Amati'],
                'correct_answer' => 'Maria Teresa de Filippis',
                'explanation' => 'Maria Teresa de Filippis was the first woman to race in F1, debuting in 1958.',
                'category' => 'history',
                'difficulty' => 'hard',
            ],
        ];

        foreach ($questions as $q) {
            Question::create($q);
        }
    }
}
