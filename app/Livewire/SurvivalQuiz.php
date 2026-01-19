<?php

namespace App\Livewire;

use App\Models\Question;
use Livewire\Component;

class SurvivalQuiz extends Component
{
    public $currentQuestion;
    public $score = 0;
    public $gameOver = false;
    public $selectedAnswer = null;
    public $correctAnswer = null;
    public $usedQuestionIds = [];

    public function mount()
    {
        $this->startNewGame();
    }

    public function startNewGame()
    {
        $this->score = 0;
        $this->gameOver = false;
        $this->usedQuestionIds = [];
        $this->loadNextQuestion();
    }

    public function loadNextQuestion()
    {
        $this->selectedAnswer = null;
        $this->correctAnswer = null;

        // get a random question not used yet
        $this->currentQuestion = Question::whereNotIn('id', $this->usedQuestionIds)
            ->inRandomOrder()
            ->first();

        // If we ran out of questions (unlikely but possible), just reset used IDs or end game
        if (!$this->currentQuestion) {
            $this->gameOver = true; // Or "You beat the game!"
            return;
        }

        $this->usedQuestionIds[] = $this->currentQuestion->id;
    }

    public function selectAnswer($option)
    {
        if ($this->selectedAnswer) return; // Prevent double click

        $this->selectedAnswer = $option;
        $this->correctAnswer = $this->currentQuestion->correct_answer;

        if ($option === $this->correctAnswer) {
            $this->score++;
        } else {
            $this->gameOver = true;
            $this->saveScore();
        }
    }

    protected function saveScore()
    {
        if (\Illuminate\Support\Facades\Auth::check()) {
            $user = \Illuminate\Support\Facades\Auth::user();
            if ($this->score > $user->survival_high_score) {
                $user->update(['survival_high_score' => $this->score]);
            }
        }
    }

    public function render()
    {
        return view('livewire.survival-quiz')->layout('components.layouts.app');
    }
}
