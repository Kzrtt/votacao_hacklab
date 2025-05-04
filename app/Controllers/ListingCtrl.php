<?php 
    namespace App\Controllers;

    class ListingCtrl {
        public $model = "";
        public $entity;

        public function __construct($model) {
            $this->model = $model;
            $this->entity = "App\\Models\\".$this->model;
        }

        public function getAll() {
            return $this->entity::select()->get();
        }

        public function getForLevelByEvent() {
            if(auth()->user()->usr_level == "Admin") {
                return $this->getAll();
            } else {
                $evtId = auth()->user()->getRepresentedAgent->getAgent->event->evt_id;
                return $this->entity::select()->where('event_evt_id', $evtId)->get();
            }
        }
    }
?>