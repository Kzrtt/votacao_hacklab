<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DynamicForm extends Component
{
    public $formConfig;
    public $selectsPopulate;
    public $formData;
    public $isEdit;

    /**
     * Cria uma nova instância do componente.
     *
     * @param  array  $formConfig         Configuração do formulário (grupos, linhas e campos)
     * @param  array  $selectsPopulate    Dados para os selects, indexados pelo identifier dos campos
     */
    public function __construct(array $formConfig, array $selectsPopulate, array $formData, bool $isEdit)
    {
        $this->formConfig = $formConfig;
        $this->selectsPopulate = $selectsPopulate;
        $this->formData = $formData;
        $this->isEdit = $isEdit;
    }

    /**
     * Retorna a view que representa o componente.
     */
    public function render(): View|Closure|string
    {
        return view('components.dynamic-form');
    }
}
