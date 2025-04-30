<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Validation\ValidationException;
use App\Controllers\GenericCtrl;
use App\Controllers\Utils\SaveFunctions;
use App\Traits\DynamicFormTrait;
use TallStackUi\Traits\Interactions; 


#[Layout('components.layouts.app')]
class UserForm extends Component
{
    use DynamicFormTrait;
    use Interactions;

    public $isEdit = false;
    public $params = array();

    public function mount($local, $id = null) {
        $this->params = session('params');
        $this->params['_local'] = $local;
        $this->params['_id'] = $id;

        $this->renderUIViaYaml();

        if(!is_null($id)) {
            $this->isEdit = true;

            $genericCtrl = new GenericCtrl($local);
            $userRepresentedAgentCtrl = new GenericCtrl("UserRepresentedAgent");

            $className = "App\\Models\\".$local;
            $object = $genericCtrl->getObject($id);
            $type = "App\\Models\\".$object->usr_level;
            $representedAgent = $userRepresentedAgentCtrl->getObjectByFields(
                ["ura_type", "users_usr_id"],
                [$type, $object->usr_id]
            );
            
            if($object instanceof $className) {
                $converted = [];
                $objectArray = $object->toArray();

                foreach ($this->identifierToField as $friendlyKey => $dbKey) {
                    $converted[$friendlyKey] = array_key_exists($dbKey, $objectArray) ? $objectArray[$dbKey] : null;
                }

                $this->formData = array_merge($this->formData, $converted);
                $this->formData['representedAgent'] = $representedAgent->represented_agent_id;
            }

            foreach ($this->remoteUpdates as $identifier => $remoteConfig) {
                if (!empty($this->formData[$identifier])) {
                    if (!empty($remoteConfig['customRemote'])) {
                        $customMethod = $remoteConfig['customRemote'];
                        $this->{$customMethod}();
                    } else {
                        $this->updateRemoteField($identifier, $remoteConfig);
                    }
                }
            }
            
        }

        $this->selectsPopulate['representedAgent'] = array();
    }

    public function getRepresentedAgents() {
        $profileCtrl = new GenericCtrl("Profile");
        
        $prfId = $this->formData['profile'];
        $profile = $profileCtrl->getObject($prfId);

        $fetchModel = "App\\Models\\".$profile->prf_entity;

        $entityMap = array(
            "Judge" => array("label" => "jdg_name", "id" => "jdg_id"),
            "Admin" => array("label" => "adm_fantasy", "id" => "adm_id"),
        );
        
        $this->selectsPopulate['representedAgent'] = 
            $fetchModel::select()->get()->pluck($entityMap[$profile->prf_entity]['label'], $entityMap[$profile->prf_entity]['id'])->toArray();
    }

    public function submitForm() {
        try {
            $this->validate();

            //? Criação do Usuário
            $formData = array();
            $genericCtrl = new GenericCtrl($this->params['_local']);
            $profileCtrl = new GenericCtrl("Profile");
            $userRepresentedAgentCtrl = new GenericCtrl("UserRepresentedAgent");

            $profile = $profileCtrl->getObject($this->formData['profile']);

            foreach ($this->formData as $identifier => $value) {
                if($identifier == "representedAgent") {
                    continue;
                }
                
                if(array_key_exists($identifier, $this->saveFunctions)) {
                    $saveFunction = $this->saveFunctions[$identifier];
                    $formData[$this->identifierToField[$identifier]] = SaveFunctions::$saveFunction($value);
                } else {
                    $formData[$this->identifierToField[$identifier]] = $value;
                }
                
            }

            $formData['usr_level'] = $profile->prf_entity;

            if(!is_null($this->params['_id'])) {
                $user = $genericCtrl->update($this->params['_id'], $formData);
                $uraClass = "App\\Models\\UserRepresentedAgent";
                $representedAgent = $userRepresentedAgentCtrl->getObjectByFields(
                    ["ura_type", "users_usr_id"],
                    [$user->usr_level, $user->usr_id],
                );

                if($representedAgent instanceof $uraClass) {
                    if($representedAgent->represented_agent_id != $this->formData['representedAgent']) {
                        $userRepresentedAgentCtrl->save(
                            array(
                                'ura_type' => "App\\Models\\".$profile->prf_entity,
                                'represented_agent_id' => $this->formData['representedAgent'],
                                'users_usr_id' => $user->usr_id,
                            )
                        );

                        $userRepresentedAgentCtrl->delete($representedAgent->ura_id);
                    }
                }
            } else {
                $user = $genericCtrl->save($formData);
                $userRepresentedAgentCtrl->save(
                    array(
                        'ura_type' => $profile->prf_entity,
                        'represented_agent_id' => $this->formData['representedAgent'],
                        'users_usr_id' => $user->usr_id,
                    )
                );

                $this->reset('formData');
            }
            
            $this->dialog()
            ->success("Sucesso!", "Usuário criado com sucesso!")
            ->flash()
            ->send();

            $this->js("window.history.back()");
        } catch (ValidationException $ex) {
            $this->dialog()
            ->error("Erro no Formulário", $ex->validator->errors()->first())
            ->send();
        } catch (\Exception $ex) {
            $this->dialog()
            ->error("Erro Inesperado", $ex->getMessage())
            ->send();
        }   
    }
    
    public function render()
    {
        return view('livewire.user-form');
    }
}
