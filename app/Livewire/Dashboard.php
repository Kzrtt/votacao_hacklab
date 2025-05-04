<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use TallStackUi\Traits\Interactions; 
use App\Controllers\GenericCtrl;

#[Layout('components.layouts.app')]
class Dashboard extends Component
{
    use Interactions;

    public $events = array();
    public $report = array();

    public $usrLevel = "";
    public $evtId = "";
    public $selectEventModal = false;

    //? Start da tela, pre-carregando os eventos disponiveis
    public function mount() {
        $eventCtrl = new GenericCtrl("Event");

        $this->usrLevel = auth()->user()->usr_level;

        if($this->usrLevel == "Admin") {
            $events = $eventCtrl->getAll();
            foreach ($events as $key => $value) {
                $this->events[] = $value->toArray();
            }
        } else {
            $this->evtId = auth()->user()->getRepresentedAgent->getAgent->event->evt_id;
            $this->getEventEvaluations();
        }    
    }

    public function goVote() {
        return redirect()->route('voting-screen');
    }

    public function getEventEvaluations()
    {
        $projectCtrl = new GenericCtrl('Project');
        $projects = $projectCtrl->getObjectByField('event_evt_id', $this->evtId, false);
        $report = [];
    
        foreach ($projects as $prj) {
            $row = [
                'prj_id' => $prj->prj_id,
                'prj_name' => $prj->prj_name,
                'prj_participants' => $prj->prj_participants,
                'prj_stack' => $prj->prj_stack,
                'judges' => [],
                'final_average' => 0.0,
            ];
    
            $sumAllJudges = 0;
            $judgeCount = 0;
    
            foreach ($prj->evaluations as $evaluation) {
                $sumWeighted = 0;
                $sumWeights = 0;
                $scoresMap = [];
    
                // monta evaluation_scores e acumula para média ponderada
                foreach ($evaluation->scores as $evs) {
                    $peso = $evs->criterion->crt_weight;      // ex: 30,20,...
                    $nota = $evs->evs_score;                  // ex: 4.5
                    $key = sprintf('%s (%d%%)', $evs->criterion->crt_name, $peso);
    
                    $scoresMap[$key] = $nota;
                    $sumWeighted += $nota * $peso;
                    $sumWeights += $peso;
                }
    
                $judgeAvg = $sumWeights > 0
                    ? round($sumWeighted / $sumWeights, 2)
                    : 0.0;
    
                $row['judges'][] = [
                    'judge_id' => $evaluation->judge_jdg_id,
                    'judge_name' => $evaluation->judge->jdg_name,
                    'score' => $judgeAvg,
                    'evaluation_scores' => $scoresMap,
                ];
    
                $sumAllJudges += $judgeAvg;
                $judgeCount++;
            }
    
            $row['final_average'] = $judgeCount > 0
                ? round($sumAllJudges / $judgeCount, 2)
                : 0.0;
    
            $report[] = $row;
        }
    
        // ordena do maior para o menor pela média final
        usort($report, fn($a, $b) => $b['final_average'] <=> $a['final_average']);
    
        $this->report = $report;
    }

    public function openModal() { 
        $this->selectEventModal = true;
    }

    public function closeModal() {
        $this->selectEventModal = false;
    }

    //? Métodos para resgatar projetos
    public function selectEvent() {
        $this->getEventEvaluations();
        $this->selectEventModal = false;
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
