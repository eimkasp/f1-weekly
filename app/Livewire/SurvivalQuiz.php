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
            // Delay for effect, then next question (Frontend handles delay usually, but here we can just update state)
            // We'll let the user click "Next" to continue or auto-advance. 
            // For Survival, suspense is key. Let's show result, then user clicks "Next".
        } else {
            $this->gameOver = true;
        }
    }

    public function render()
    {
        return view('livewire.survival-quiz')->layout('components.layouts.app');
    }
}
