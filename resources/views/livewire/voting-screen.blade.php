<div class="flex flex-col items-center mt-8 px-50 w-full">
    <x-ts-modal 
        title="Seleção de Evento" 
        wire="selectEventModal" 
        center
        size="5xl"
    >
        <div>
            <!-- SELECT de SubÁrea -->
            <div>
                <label class="block text-sm font-medium text-gray-600/70 mb-2">Eventos</label>
                <div class="flex rounded-md border border-gray-300 text-gray-700/50 focus-within:ring-1 focus-within:ring-primary-500/30">
                    <div class="flex items-center px-3 bg-gray-50 border-r border-gray-300 rounded-l-md">
                        <i class="fad fa-ticket-alt"></i>
                    </div>
                    
                    <select
                        wire:model.defer="evtId"
                        class="w-full border-none px-3 py-2 text-gray-700 focus:outline-none focus:ring-0"
                    >
                        <option value="">Selecione o Evento</option>
                        @foreach($events as $evt)
                            <option value="{{ $evt['evt_id'] }}">{{ $evt['evt_name'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <hr class="border-t-2 border-dashed border-primary-800/30 my-4">

            <div class="flex flex-row justify-end items-center mt-4 space-x-2">
                <!-- Botão REMOVER Permissões -->
                <button 
                    type="button"
                    wire:click="closeModal"
                    class="p-2 px-4 rounded-lg bg-red-100 text-red-500 hover:bg-red-500 hover:text-white hover:cursor-pointer transition"
                >
                    <i class="fad fa-times-circle p-1"></i>
                    <span class="font-semibold">Cancelar</span>
                </button>

                <!-- Botão CONCEDER Permissões -->
                <button 
                    type="button"
                    wire:click="selectEvent"
                    class="p-2 px-4 rounded-lg bg-green-100 text-green-600 hover:bg-green-600 hover:text-white hover:cursor-pointer transition"
                >
                    <i class="fad fa-check-circle p-1"></i>
                    <span class="font-semibold">Selecionar</span>
                </button>
            </div>

            <x-slot:footer>
                <div class="w-full flex justify-end items-center mt-2">
                    <span class="text-sm text-black/40">Avaliação dos Projetos</span>                    
                </div>
            </x-slot:footer>
        </div>
    </x-ts-modal>

    <div class="flex items-center justify-between w-full bg-white rounded-lg p-4 shadow">
        <!-- Lado esquerdo: ícone + textos -->
        <div class="flex items-center space-x-4">
            <div class="w-14 h-14 flex items-center justify-center bg-tertiary-200/80 rounded-lg">
                <i class="fad fa-box-ballot text-xl text-primary-800/90"></i>
            </div>
            <div class="flex flex-col">
                <p class="text-lg font-semibold text-primary-800 mb-1">Avaliação do Evento</p>
                @if ($usrLevel == "Admin")
                    <p class="text-sm text-primary-600">Selecione um evento para apresentar a avaliação</p>
                @else 
                    <p class="text-sm text-primary-600">Selecione um projeto para avalia-lo</p>
                @endif
                
            </div>
        </div>
    
        @if($usrLevel == "Admin") 
            <!-- Botão à direita -->
            <button wire:click="openModal" class="p-2 rounded-lg bg-primary-300/55 text-primary-700 hover:bg-primary-600/80 hover:text-white hover:cursor-pointer">
                <i class="fad fa-ticket-alt p-1"></i>&nbsp;<span class="font-semibold">Selecionar Evento</span>&nbsp;
            </button>
        @endif
    </div>

    <div class="w-full"
        x-data="{
            projects: @entangle('projects'),
            criterions: @entangle('criterions'),
            selected: @entangle('selectedProject')
        }"
    >
        <div class="mt-8 w-full grid grid-cols-4 gap-x-10">
            @foreach($projects as $project)
                <div 
                    @click="selected = {{ $project['prj_id'] }}; $wire.selectProject({{ $project['prj_id'] }})"
                    class="bg-gray-100 w-full relative rounded-lg shadow hover:shadow-lg hover:scale-[1.01] hover:cursor-pointer transition-all duration-200"
                >
                    <div class="flex items-center justify-center w-20 h-20 bg-white rounded-full absolute top-6 right-3">
                        <div class="flex items-center justify-center w-17 h-17 bg-tertiary-200/80 rounded-full">
                            <i class="fad text-2xl text-primary-800/90"
                            :class="selected === {{ $project['prj_id'] }} ? 'fa-folder-open' : 'fa-folder'">
                            </i>
                        </div>
                    </div>
                    <div class="w-full h-16 rounded-t-lg bg-primary-800/90"></div>

                    <div class="px-4 pb-2 pt-0 mt-5 mb-2 space-y-1">
                        <div class="mt-6">
                            <p class="flex flex-col justify-between w-full truncate">
                                <span class="font-semibold text-primary-800/70 text-lg">Nome</span>
                                <span class="text-gray-700/55 font-semibold text-md pb-2">
                                    {{ $project['prj_name'] }}
                                </span>
                            </p>

                            <p class="flex flex-col justify-between w-full truncate">
                                <span class="font-semibold text-primary-800/70 text-lg">Participantes</span>
                                <span class="text-gray-700/55 font-semibold text-md pb-2">
                                    {{ $project['prj_participants'] }}
                                </span>
                            </p>

                            <p class="flex flex-col justify-between w-full truncate">
                                <span class="font-semibold text-primary-800/70 text-lg">Stack</span>
                                <span class="text-gray-700/55 font-semibold text-md pb-2">
                                    {{ $project['prj_stack'] }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- x-show="selected !== null" -->
        <div x-show="selected !== null" class="mt-8 mb-10 w-full bg-white rounded-xl">
            <table class="min-w-full bg-white rounded-lg overflow-hidden shadow">
                <thead class="bg-primary-800/90 my-4">
                    <tr>
                        <th class="px-4 py-4 text-left text-sm font-medium text-white">Nome</th>
                        <th class="px-4 py-4 text-right text-sm font-medium text-white">Nota</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($criterions as $criterion)
                        <tr wire:key="project-{{ $selectedProject }}-criterion-{{ $criterion['crt_id'] }}">
                            <td class="px-4 py-3 text-gray-800">
                                <span class="text-md font-semibold text-black/55">({{ $criterion['crt_id'] }}) {{ $criterion['crt_name'] }} ({{ $criterion['crt_weight'] }}%)</span><br>
                                <span class="text-sm text-primary-600/80">{{ $criterion['crt_explanation'] }}</span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <input
                                    type="number"
                                    min="0"
                                    max="10"
                                    step="0.1"
                                    lang="en"
                                    placeholder="0.0"
                                    class="w-20 px-2 py-1 border rounded focus:outline-none focus:ring-1 focus:ring-primary-500"
                                    value="{{ 
                                        isset($projectsEvaluation[$selectedProject][$criterion['crt_id']])
                                            ? number_format($projectsEvaluation[$selectedProject][$criterion['crt_id']], 1, '.', '')
                                            : ''
                                    }}"
                                    wire:change="updateScore({{ $selectedProject }}, {{ $criterion['crt_id'] }}, $event.target.value)"
                                />
                            </td>
                        </tr>
                    @endforeach                
                </tbody>
            </table>

            <div class="mt-6 pb-5 flex flex-row items-center justify-between mx-3">
                <div>
                    <p class="flex flex-col justify-between w-full truncate">
                        <span class="font-semibold text-primary-800/70 text-lg">Participantes</span>
                        <span class="text-gray-700/55 font-semibold text-md pb-2"
                        x-text="projects.find(p => p.prj_id === selected).prj_participants"></span>
                    </p>
                </div>

                @if ($usrLevel == "Judge")
                    <button wire:click="vote" class="p-2 rounded-lg bg-primary-300/55 text-primary-700 hover:bg-primary-600/80 hover:text-white hover:cursor-pointer">
                        <i class="fad fa-box-ballot p-1"></i>&nbsp;<span class="font-semibold">Votar</span>&nbsp;
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
