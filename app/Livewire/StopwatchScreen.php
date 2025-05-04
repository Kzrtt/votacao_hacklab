<?php
// App\Livewire\StopwatchScreen.php

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
    public $events = [];
    public $evtId = "";
    public $selectEventModal = false;
    public $changeDurationModal = false;
    public $stopwatchDuration = "";
    public $usrLevel = "";
    
    protected $listeners = ['startTimer', 'resetTimer'];

    public function mount()
    {
        $this->usrLevel = auth()->user()->usr_level;

        if($this->usrLevel == "Admin") {
            $eventCtrl = new GenericCtrl("Event");
            $events = $eventCtrl->getAll();
            foreach ($events as $key => $value) {
                $this->events[] = $value->toArray();
            }
        } else {
            $evtId = auth()->user()->getRepresentedAgent->getAgent->event->evt_id;
            
            $ctrl = new GenericCtrl("Event");
            $this->event = $ctrl->getObject($evtId);

            $timer = $this->event->timer
                ?? $this->event->timer()->create([
                    'etm_started_at' => null,
                    'etm_duration'   => $this->duration,
                ]);

            $this->startTimestamp = $timer->etm_started_at
                ? Carbon::parse($timer->etm_started_at)->timestamp
                : 0;

            $this->duration = $timer->etm_duration;
        }
    }

    public function selectEvent()
    {
        $ctrl = new GenericCtrl("Event");
        $this->event = $ctrl->getObject($this->evtId);

        $timer = $this->event->timer
            ?? $this->event->timer()->create([
                   'etm_started_at' => null,
                   'etm_duration'   => $this->duration,
               ]);

        $this->startTimestamp = $timer->etm_started_at
            ? Carbon::parse($timer->etm_started_at)->timestamp
            : 0;

        $this->duration = $timer->etm_duration;
        $this->closeModal();
    }

    public function startTimer()
    {
        $timer = $this->event->timer;
        if (! $timer->etm_started_at) {
            $timer->update(['etm_started_at' => now()]);
            $this->startTimestamp = Carbon::parse($timer->etm_started_at)->timestamp;
        }
    }

    public function resetTimer()
    {
        $timer = $this->event->timer;
        $timer->update([
            'etm_started_at' => null,
        ]);
        // reseta o timestamp local para 0 (pausado no init)
        $this->startTimestamp = 0;
    }

    public function changeStopwatchDuration() {
        $timer = $this->event->timer;
        $timer->update([
            'etm_duration' => $this->stopwatchDuration,
        ]);

        $this->duration = $this->stopwatchDuration;

        $this->startTimestamp = 0;
        $this->changeDurationModal = false;
        $this->toast()->success("Sucesso!", "Duração do crônometro alterada!")->send();
    }

    public function openModal()  { $this->selectEventModal = true; }
    public function closeModal() { $this->selectEventModal = false; }
    public function openChangeDurationModal() { $this->changeDurationModal = true; }
    public function closeChangeDurationModal() { $this->changeDurationModal = false; }

    public function setDuration($value)
    {
        $this->duration = $value;
        $this->event->timer->update([
            'etm_duration' => Carbon::createFromFormat('H:i', $value)->format('H:i'),
        ]);
    }

    public function render()
    {
        return view('livewire.stopwatch-screen');
    }
}
