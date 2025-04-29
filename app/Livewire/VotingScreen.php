<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Controllers\GenericCtrl;
use TallStackUi\Traits\Interactions; 

/**
 * Classe para tratamento da visualização dinâmica dos registros
 * vindos do banco de dados usando um arquivo .yaml previamente estruturado
 * 
 * @author Felipe Kurt <fe.hatunaqueton@gmail.com>
 */

#[Layout('components.layouts.app')]
class VotingScreen extends Component
{
    use Interactions;

    //? Dados resgatados do banco para construção da tela
    public $projects = array();
    public $events = array();
    public $criterions = array();

    //? Dados da Avaliação
    public $selectedProject = null;
    public $projectsEvaluation = array();

    public $usrLevel = "";
    public $evtId = "";
    public $jdgId = "";
    public $selectEventModal = false;

    //? Start da tela, pre-carregando os eventos disponiveis
    public function mount() {
        $eventCtrl = new GenericCtrl("Event");

        $this->usrLevel = auth()->user()->usr_level;
        $this->jdgId = auth()->user()->getRepresentedAgent->getAgent->jdg_id;

        if($this->usrLevel == "Admin") {
            $events = $eventCtrl->getAll();
            foreach ($events as $key => $value) {
                $this->events[] = $value->toArray();
            }
        } else {
            $this->evtId = auth()->user()->getRepresentedAgent->getAgent->event->evt_id;
            $this->fetchProjects($this->evtId);
        }          
    }

    public function selectProject($prjId)
    {
        $this->selectedProject = $prjId;
        $this->refreshOptions($prjId);
    }

    public function vote() {
        if (! $this->selectedProject) {
            return; // nada selecionado
        }

        $evaluationCtrl = new GenericCtrl("Evaluation");
        $evaluationScoreCtrl = new GenericCtrl("EvaluationScore");

        $prjId = $this->selectedProject;
        $crtArray = $this->projectsEvaluation[$prjId] ?? [];

        $evaluation = $evaluationCtrl->getObjectByFields(
            ['project_prj_id','judge_jdg_id'],
            [$prjId, $this->jdgId]
        );

        if (! $evaluation instanceof \App\Models\Evaluation) {
            $evaluation = $evaluationCtrl->save([
                'project_prj_id' => $prjId,
                'judge_jdg_id'   => $this->jdgId,
            ]);
        }

        // agora só percorre os critérios deste projeto
        foreach ($crtArray as $crtId => $value) {
            $evaluationScore = $evaluationScoreCtrl->getObjectByFields(
                ['evaluation_eva_id','criteria_crt_id'],
                [$evaluation->eva_id, $crtId]
            );

            if (! $evaluationScore instanceof \App\Models\EvaluationScore) {
                $evaluationScore = $evaluationScoreCtrl->save([
                    'evaluation_eva_id' => $evaluation->eva_id,
                    'criteria_crt_id'   => $crtId,
                    'evs_score'         => 0,
                ]);
            }

            // atualiza só este par avaliação/critério
            $evaluationScoreCtrl->update(
                $evaluationScore->evs_id,
                ['evs_score' => $value]
            );
        }

        $this->toast()->success("Sucesso!", "Notas do projeto atualizadas")->send();
    }

    //? Controle do Modal
    public function openModal() { 
        $this->selectEventModal = true;
    }

    public function closeModal() {
        $this->selectEventModal = false;
    }

    //? Métodos para resgatar projetos
    public function selectEvent() {
        $this->fetchProjects($this->evtId);
        $this->selectEventModal = false;
    }

    public function fetchProjects($eventId) {
        $projectCtrl = new GenericCtrl("Project");
        $criterionCtrl = new GenericCtrl("Criterion");
        $evaluationCtrl = new GenericCtrl("Evaluation");


        $this->projects = [];
        $this->criterions = [];
        $this->projectsEvaluation = [];

        $criterionsArray = array();
        $criterions = $criterionCtrl->getObjectByField('event_evt_id', $eventId, false);
        foreach ($criterions as $key => $value) {
            $this->criterions[] = $value->toArray();
            $criterionsArray[$value->crt_id] = 0;
        }

        $projects = $projectCtrl->getObjectByField('event_evt_id', $eventId, false);
        foreach ($projects as $key => $project) {
            $this->projects[] = $project->toArray();

            $this->projectsEvaluation[$project->prj_id] = $criterionsArray;
            $this->refreshOptions($project->prj_id);
        }
    }

    public function refreshOptions($projectId) {
        $evaluationCtrl = new GenericCtrl("Evaluation");

        $evaluation = $evaluationCtrl->getObjectByFields(
            array("project_prj_id", "judge_jdg_id"),
            array($projectId, $this->jdgId),
        );

        if($evaluation instanceof \App\Models\Evaluation) {
            foreach ($evaluation->scores as $key => $score) {
                $this->projectsEvaluation[$projectId][$score->criterion->crt_id] = $score->evs_score;
            }
        }
    }

    public function render()
    {
        return view('livewire.voting-screen');
    }
}
