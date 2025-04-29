<?php 
    if(!function_exists("prettyPrint")) {
        function prettyPrint($data) {
            echo "<pre>";
            print_r($data);
            echo "</pre>";
        }
    }

    if(!function_exists("getFriendlyAgentType")) {
        function getFriendlyAgentType($agentType) {
            $agentMap = array(
                "Admin" => "Administrador",
                "Judge" => "Jurado"
            );

            return isset($agentMap[$agentType]) ? $agentMap[$agentType] : "Desconhecido";
        }
    } 

    if(!function_exists("getAgentName")) {
        function getAgentName($level) {
            switch ($level) {
                case 'Admin':
                    return auth()->user()->getRepresentedAgent->getAgent->adm_fantasy;
                case 'Judge':
                    return auth()->user()->getRepresentedAgent->getAgent->jdg_name;
                default:
                    return "Desconhecido";
                    break;
            }
            
        }
    }

    if(!function_exists("removeUserPermissionInSession")) {
        function removeUserPermissionInSession($local, $action) {
            $permissions = session('usr_permissions');
        
            if (isset($permissions[$local][$action])) {
                unset($permissions[$local][$action]);
                session()->put('usr_permissions', $permissions);
            }
        }
    }

    if(!function_exists("addUserPermissionInSession")) {
        function addUserPermissionInSession($local, $action) {
            $permissions = session('usr_permissions');
        
            if (!isset($permissions[$local][$action])) {
                $permissions[$local][$action] = true;
                session()->put('usr_permissions', $permissions);
            }
        }
    }

    if(!function_exists("getMessageForValidation")) {
        function getMessageForValidation($rule) {            
            $validationMap = array(
                "required" => "O Campo :attribute é obrigatório",
                "min" => "O Campo :attribute precisa ter no mínimo :min caracteres"
            );

            return isset($validationMap[$rule]) ? $validationMap[$rule] : "Erro no campo :attribute";
        }
    }

    if(!function_exists("getFriendlyPermission")) {
        function getFriendlyPermission($permission) {
            $permissionMap = array(
                "Consult" => "Consultar",
                "Insert" => "Inserir",
                "Delete" => "Deletar",
                "Edit" => "Edição",
            );

            return isset($permissionMap[$permission]) ? $permissionMap[$permission] : "?";
        }
    }

    if (!function_exists('zeroFill')) {
        function zeroFill($string, $size) {
            $zeros = "";
            $length = ($size - strlen($string));
            for ($i = 0; $i < $length; $i++) {
                $zeros .= 0;
            }
            $string = $zeros . $string;
            return $string;
        }
    }    
?>