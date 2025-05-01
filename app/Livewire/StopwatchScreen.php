<?php

namespace App\Livewire;

use Livewire\Component;
use TallStackUi\Traits\Interactions; 
use Livewire\Attributes\Layout;
use App\Controllers\GenericCtrl;
use Carbon\Carbon;

#[Layout('components.layouts.app')]
class StopwatchScreen extends Component
{
    use Interactions;

    public $event;
    public $startTimestamp; // epoch em segundos
    public $duration;       // “HH:MM”

    public function mount()
    {
        $eventId = 1;

        $eventCtrl = new GenericCtrl("Event");
        
        $this->event = $eventCtrl->getObject($eventId);

        $timer = $this->event->timer
            ?? $this->event->timer()->create(['etm_started_at' => now()]);

        // se vier como string, parseia
        $this->startTimestamp = is_numeric($timer->etm_started_at)
        ? (int) $timer->etm_started_at
        : Carbon::parse($timer->etm_started_at)->timestamp;

        $this->duration = $timer->etm_duration;
    }

    public function setDuration($value)
    {
        $this->duration = $value;
        $this->event->timer->update([
            'duration' => Carbon::createFromFormat('H:i', $value)->toTimeString()
        ]);
    }

    public function render()
    {
        return view('livewire.stopwatch-screen');
    }
}
