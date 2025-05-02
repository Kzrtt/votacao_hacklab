<?php
    namespace App\Controllers;

    use App\Controllers\GenericCtrl;

    class EventCtrl extends GenericCtrl {
        public $model = "Event";

        public function __construct() {
            parent::__construct($this->model);
        }

        public function saveEventWithStopwatch($data) {
            $stopwatchCtrl = new GenericCtrl("EventTimer");

            $registry = $this->save($data);

            $stopwatchCtrl->save(array(
                'event_evt_id' => $registry->evt_id,
                'etm_duration' => "04:30:00",
            ));

            return $registry;
        }
    }
?>