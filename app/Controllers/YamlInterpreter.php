<?php
    namespace App\Controllers;

    use App\Rules\ValidateCPF;
    use Symfony\Component\Yaml\Yaml;

    class YamlInterpreter {
        public $local = "";
        public $file = "";
        public $formOutput = array();
        public $listOutput = array();
        public $permissionsOutput = array();

        public function __construct($local) {
            $this->local = $local;
            $this->file = base_path('core/'.$this->local.'.yaml');
        }

        public function getPermissionsFromConfig() {
            $permissionsConfig = array();
            if(file_exists($this->file)) {
                $permissionsConfig = Yaml::parseFile($this->file)['Areas'];
            }

            foreach ($permissionsConfig as $group => $data) {
                if(!isset($this->permissionsOutput[$group])) {
                    $this->permissionsOutput[$group] = array(
                        "name" => $data['name'],
                        "icon" => $data['icon'],
                        "subItens" => array(),
                    );
                }

                foreach ($data['subItens'] as $area => $areaData) {
                    if(!isset($this->permissionsOutput[$group][$area])) {
                        $this->permissionsOutput[$group]['subItens'][] = array(
                            "name" => $areaData['name'],
                            "area" => $area,
                            "permissions" => $areaData['actions'],
                        );
                    }
                }
            }

            return $this->permissionsOutput;
        }

        public function renderListUIData() {
            $this->listOutput['tableConfig'] = array();
            $this->listOutput['gridConfig'] = array();
            $this->listOutput['additionalSingleActions'] = array();
            $this->listOutput['buttonsConfig'] = array(
                "showSearchButton" => true,
                "showInsertButton" => true,
                "showEditButton" => false,
                "showDetailsButton" => false,
                "showDeleteButton" => false
            );
            $this->listOutput['startsOn'] = "list";
            $this->listOutput['viewForm'] = "list.component";
    
            //? Carregando arquivo
            $listingConfig = array();
            
            if(file_exists($this->file)) {
                $listingConfig = Yaml::parseFile($this->file)[$this->local];
            }
    
            //? Pegando configurações da tabela, grid e botões
            if(key_exists('startsOn', $listingConfig)) {
                $this->listOutput['startsOn'] = $listingConfig['startsOn'];
            }
    
            if(key_exists('listingConfig', $listingConfig)) {
                foreach ($listingConfig['listingConfig'] as $type => $data) {
                    foreach ($data as $field => $config) {
                        $typeConfig = $type."Config";
                        $this->listOutput[$typeConfig][$field] = $config;
                    }            
                }
            }
    
            if(key_exists('buttonsConfig', $listingConfig)) {
                foreach ($listingConfig['buttonsConfig'] as $button => $data) {
                    $this->listOutput['buttonsConfig'][$button] = $data;
                }
            }
    
            if(key_exists('formConfig', $listingConfig)) {
                if(key_exists('view', $listingConfig['formConfig'])) {
                    $this->listOutput['viewForm'] = $listingConfig['formConfig']['view'];
                }
            }

            if(isset($listingConfig['additionalSingleActions'])) {
                foreach ($listingConfig['additionalSingleActions'] as $name => $data) {
                    $this->listOutput['additionalSingleActions'][$name] = $data;
                }
            }
    
            //? Carregando o controlador dinâmicamente
            $this->listOutput['getConfig'] = $listingConfig['getConfig'];
            if(key_exists('saveConfig', $listingConfig)) {
                $this->listOutput['saveConfig'] = $listingConfig['saveConfig'];
            }
    
            //? Marcando campo do id
            $this->listOutput['identifier'] = $listingConfig['identifier'];    

            return $this->listOutput;
        }

        public function renderFormUIData($isEdit) {
            $this->formOutput['formConfig'] = array();
            $this->formOutput['selectsPopulate'] = array();
            $this->formOutput['messages'] = array();
            $this->formOutput['rules'] = array();
            $this->formOutput['validationAttributes'] = array();
            $this->formOutput['formData'] = array();
            $this->formOutput['identifierToField'] = array();
            $this->formOutput['remoteUpdates'] = array();
            $this->formOutput['saveFunctions'] = array();
            $this->formOutput['saveConfig'] = array();
            
            //? Carregando arquivo
            $formConfig = array();
    
            if(file_exists($this->file)) {
                $formConfig = Yaml::parseFile($this->file)[$this->local];
            }

            if(isset($formConfig['saveConfig'])) {
                $this->formOutput['saveConfig'] = $formConfig['saveConfig'];
            }   
    
            foreach ($formConfig['formConfig'] as $field => $data) {
                if($field == "view") {
                    continue;
                }

                //? Carregando configurações da UI do formulário
                if(!isset($this->formOutput['formConfig'][$data['groupIn']])) {
                    $this->formOutput['formConfig'][$data['groupIn']] = array();
                }
    
                if(!isset($this->formOutput['formConfig'][$data['groupIn']][$data['line']])) {
                    $this->formOutput['formConfig'][$data['groupIn']][$data['line']] = array();
                }
    
                if($data['type'] == "select" || $data['type'] == "relation") {
                    if(!isset($this->formOutput['selectsPopulate'][$data['identifier']])) {
                        $this->formOutput['selectsPopulate'][$data['identifier']] = array();
                    }
    
                    if(@$data['values']) {
                        $this->formOutput['selectsPopulate'][$data['identifier']] = $data['values'];
                    }

                    if(@$data['fillOnStart']) {
                        $fill = $data['fillOnStart'];
                        $daoCtrl = app()->makeWith("App\\Controllers\\".$fill['controller'], $fill['params'] ?? []);
                        $temp = $daoCtrl->{$fill['method']}()->pluck(...array_values($fill['pluck']))->toArray();

                        $this->formOutput['selectsPopulate'][$data['identifier']] = $temp;
                    }
                }

                //? Adicionando as validações nos campos
                if(isset($data['validationRules'])) {
                    $validationArray = array();
                    foreach ($data['validationRules'] as $validation) {
                        if(strpos($validation, ":") !== false) {
                            $rule = explode(":", $validation)[0];
                        } else {
                            $rule = $validation;
                        }
    
                        $editable = filter_var(
                            $data['edit'],
                            FILTER_VALIDATE_BOOLEAN,
                            FILTER_NULL_ON_FAILURE
                        );
                        
                        if($rule === 'required') {
                            if ($isEdit && ! $editable) {
                            } else {
                                // ou estamos criando (! $isEdit) OU estamos editando e ele é editável
                                $data['required'] = true;
                                $this->formOutput['messages']['formData.'.$data['identifier'].'.'.$rule] = getMessageForValidation($rule);
                                $validationArray[] = $validation;
                            }
                        } else {    
                            $this->formOutput['messages']['formData.'.$data['identifier'].'.'.$rule] = getMessageForValidation($rule);
                            $validationArray[] = $validation;
                        }
                    }

                    if(@$data['customValidation']) {
                        $customRule = app("App\\Rules\\".$data['customValidation']);
                        $object = new $customRule;
                        //$validationArray[] = new ValidateCPF;
                    }
    
                    $this->formOutput['rules']['formData.'.$data['identifier']] = $validationArray;
                }

                if(isset($data['updateRemoteField'])) {
                    $this->formOutput['remoteUpdates'][$data['identifier']] = $data['updateRemoteField'];
                }

                if(isset($data['saveFunction'])) {
                    $this->formOutput['saveFunctions'][$data['identifier']] = $data['saveFunction'];
                }

                $this->formOutput['formConfig'][$data['groupIn']][$data['line']][] = $data;
                
                //? Passando aliases para os campos
                $this->formOutput['validationAttributes']['formData.'.$data['identifier']] = $data['label'];
    
                //? Criando mapeamento entre identifiers e nomes no banco
                $this->formOutput['formData'][$data['identifier']] = "";
                $this->formOutput['identifierToField'][$data['identifier']] = $field;
            }

            return $this->formOutput;
        }
    }
?>