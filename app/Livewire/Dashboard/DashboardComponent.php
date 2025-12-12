<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Livewire\Attributes\Reactive;

class DashboardComponent extends Component
{

    public int $year;

    public function mount()
    {
        $this->year = now()->year; // default latest year
    }

    public function updatedYear()
    {
        $this->dispatch('year-changed', year: $this->year);
    }

    public function render()
    {
        return view('livewire.dashboard.dashboard-component');
    }
}
