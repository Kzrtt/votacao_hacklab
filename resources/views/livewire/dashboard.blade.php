<div 
    x-data="{
        revealedCount: 0
    }" 
    class="flex flex-col items-center mt-8 px-50 w-full">

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

    @if($usrLevel == "Admin")
        <div class="flex items-center justify-between w-full bg-white rounded-lg p-4 shadow">
            <!-- Lado esquerdo: ícone + textos -->
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 flex items-center justify-center bg-tertiary-200/80 rounded-lg">
                    <i class="fad fa-trophy text-xl text-primary-800/90"></i>
                </div>
                <div class="flex flex-col">
                    <p class="text-lg font-semibold text-primary-800 mb-1">Resultado do Evento</p>
                    <p class="text-sm text-primary-600">Selecione um evento para apresentar os resultados</p>
                </div>
            </div>
        
            @if($usrLevel == "Admin") 
                <!-- Botão à direita -->
                <button wire:click="openModal" class="p-2 rounded-lg bg-primary-300/55 text-primary-700 hover:bg-primary-600/80 hover:text-white hover:cursor-pointer">
                    <i class="fad fa-ticket-alt p-1"></i>&nbsp;<span class="font-semibold">Selecionar Evento</span>&nbsp;
                </button>
            @endif
        </div>
    @else
        <div class="flex items-center justify-between w-full bg-white rounded-lg p-4 shadow">
            <!-- Lado esquerdo: ícone + textos -->
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 flex items-center justify-center bg-tertiary-200/80 rounded-lg">
                    <i class="fad fa-box-ballot text-xl text-primary-800/90"></i>
                </div>
                <div class="flex flex-col">
                    <p class="text-lg font-semibold text-primary-800 mb-1">Olá {{ auth()->user()->getPerson->pes_name }}, por favor lançe suas notas!</p>
                    <p class="text-sm text-primary-600">Os resultados do evento serão apresentados pelo Admin</p>
                </div>
            </div>
        
            @if($usrLevel == "Judge") 
                <!-- Botão à direita -->
                <button wire:click="goVote" class="p-2 rounded-lg bg-primary-300/55 text-primary-700 hover:bg-primary-600/80 hover:text-white hover:cursor-pointer">
                    <i class="fad fa-box-ballot p-1"></i>&nbsp;<span class="font-semibold">Votar</span>&nbsp;
                </button>
            @endif
        </div>
    @endif

    @php $isJudge = ($usrLevel === 'Judge'); @endphp

    @if (!$isJudge)
        <div class="w-full">
            <div class="mt-8 w-full grid grid-cols-5 space-x-10">
                @foreach ($report as $key => $project)
                    <div
                        x-data="{ revealed: false }"
                        @click="if(!revealed) {
                            revealed = true; 
                            revealedCount++;
                        }"
                        :class="revealed ? '' : 'filter blur-md cursor-pointer'" 
                        class="bg-gray-100 relative rounded-lg shadow hover:shadow-lg hover:scale-[1.01] hover:cursor-pointer transition-all duration-200">
                        <div class="flex items-center justify-center w-20 h-20 bg-white rounded-full absolute top-6 right-3">
                            <div class="flex items-center justify-center w-17 h-17 bg-tertiary-200/80 rounded-full">
                                <p class="text-primary-800/90 font-bold text-2xl">{{ $key + 1 }}°</p>
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

                                <p class="flex flex-col justify-between w-full truncate">
                                    <span class="font-semibold text-primary-800/70 text-lg">Nota Final</span>
                                    <span class="text-gray-700/55 font-semibold text-md pb-2">
                                        {{ $project['final_average'] }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        @if ($evtId)
            <div class="w-full">
                <hr class="border-t-2 border-dashed border-primary-800/90 my-10">
            </div>
        @endif
    @endif

    @if (count($report) > 0 || $isJudge)
        <div x-show="revealedCount === {{ count($report) }} || {{ $isJudge }}" class="mb-10 w-full space-y-6 {{ $isJudge ? "mt-5" : "" }}">
            @foreach($report as $project)
                <div x-data="{ open: false }" class="overflow-hidden rounded-lg">
                    <!-- Cabeçalho do card -->
                    <div
                        @click="open = !open"
                        class="flex items-center justify-between w-full bg-white rounded-lg p-4 shadow cursor-pointer"
                    >
                        <div class="flex items-center space-x-4">
                            <div class="w-14 h-14 flex items-center justify-center bg-tertiary-200/80 rounded-lg">
                                <i class="fad fa-chevron-down text-xl text-primary-800/90"
                                    :class="open ? 'transform rotate-180' : ''"></i>
                            </div>
                            <div class="flex flex-col">
                                <p class="text-lg font-semibold text-primary-800 mb-1">
                                    {{ $project['prj_name'] }} ({{ $project['prj_participants'] }} participantes)
                                </p>
                                <p class="text-sm text-primary-600">
                                    Média Final:
                                    <span class="text-primary-600">{{ number_format($project['final_average'], 2, '.', '') }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                
                    <!-- Conteúdo detalhado -->
                    <div
                        x-cloak
                        x-show="open"
                        x-transition.opacity
                        class="mt-4 space-y-8"
                    >
                        @foreach($project['judges'] as $judge)
                            <div class="bg-white rounded-lg overflow-hidden shadow">
                                <div class="bg-primary-800/90 px-4 py-2">
                                    <p class="text-sm font-medium text-white">
                                        Juiz: {{ $judge['judge_name'] }} —
                                        Média Ponderada:
                                        <span class="font-semibold">{{ number_format($judge['score'], 2, '.', '') }}</span>
                                    </p>
                                </div>
                
                                <table class="min-w-full bg-white">
                                    <thead class="bg-primary-800/90">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-sm font-medium text-white">Critério</th>
                                            <th class="px-4 py-3 text-right text-sm font-medium text-white">Nota</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @foreach($judge['evaluation_scores'] as $criterionName => $score)
                                            <tr>
                                                <td class="px-4 py-3 text-gray-800">
                                                    <span class="font-semibold text-black/55">{{ $criterionName }}</span>
                                                </td>
                                                    <td class="px-4 py-3 text-right">
                                                    <span class="font-semibold text-gray-700/55">
                                                        {{ number_format($score, 1, '.', '') }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
