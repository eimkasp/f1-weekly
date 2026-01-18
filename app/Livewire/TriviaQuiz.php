<?php

namespace App\Livewire;

use App\Models\Question;
use Livewire\Component;

class TriviaQuiz extends Component
{
    public $questions = [];
    public $currentQuestionId = null;
    public $currentIndex = 0;
    public $score = 0;
    public $gameFinished = false;
    public $selectedAnswer = null;
    public $showExplanation = false;

    public function mount()
    {
        $this->startNewGame();
    }

    public function startNewGame()
    {
        $this->questions = Question::inRandomOrder()->take(5)->get();
        $this->currentIndex = 0;
        $this->score = 0;
        $this->gameFinished = false;
        $this->selectedAnswer = null;
        $this->showExplanation = false;
        
        if ($this->questions->isNotEmpty()) {
            $this->currentQuestionId = $this->questions[$this->currentIndex]->id;
        }
    }

    public function getQuestionProperty()
    {
        return $this->questions[$this->currentIndex] ?? null;
    }

    public function selectAnswer($option)
    {
        if ($this->selectedAnswer) return; // Prevent changing answer

        $this->selectedAnswer = $option;
        $this->showExplanation = true;

        if ($option === $this->question->correct_answer) {
            $this->score++;
        }
    }

    public function nextQuestion()
    {
        if ($this->currentIndex < count($this->questions) - 1) {
            $this->currentIndex++;
            $this->currentQuestionId = $this->questions[$this->currentIndex]->id;
            $this->selectedAnswer = null;
            $this->showExplanation = false;
        } else {
            $this->gameFinished = true;
        }
    }

    public function render()
    {
        return view('livewire.trivia-quiz')->layout('components.layouts.app');
    }
}
