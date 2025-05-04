<div x-data class="flex flex-col items-center mt-8 px-50 w-full">
    @php $isJudge = ($usrLevel === 'Judge'); @endphp

    <x-ts-modal 
        title="Nova duração do Crônometro" 
        wire="changeDurationModal" 
        center
        size="5xl"
    >
        <div>
            <!-- SELECT de SubÁrea -->
            <div>
                <label class="block text-sm font-medium text-gray-600/70 mb-2">Nova Duração (HH:MM)</label>
                <div class="flex rounded-md border border-gray-300 text-gray-700/50 focus-within:ring-1 focus-within:ring-primary-500/30">
                    <div class="flex items-center px-3 bg-gray-50 border-r border-gray-300 rounded-l-md">
                        <i class="fad fa-stopwatch"></i>
                    </div>
                    
                    <input
                        type="time"
                        wire:model.defer="stopwatchDuration"
                        class="w-full border-none rounded-md px-3 py-2 focus:outline-none focus:ring-0"
                    />

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
                    wire:click="changeStopwatchDuration"
                    class="p-2 px-4 rounded-lg bg-green-100 text-green-600 hover:bg-green-600 hover:text-white hover:cursor-pointer transition"
                >
                    <i class="fad fa-check-circle p-1"></i>
                    <span class="font-semibold">Confirmar</span>
                </button>
            </div>

            <x-slot:footer>
                <div class="w-full flex justify-end items-center mt-2">
                    <span class="text-sm text-black/40">Alteração de Duração</span>                    
                </div>
            </x-slot:footer>
        </div>
    </x-ts-modal>

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
                <i class="fad fa-stopwatch text-2xl text-primary-800/90"></i>
            </div>
            <div class="flex flex-col">
                <p class="text-lg font-semibold text-primary-800 mb-1">Crônometro do Evento</p>
                <p class="text-sm text-primary-600">{{ $isJudge ? "Cronômetro de Duração do Evento" : "Selecione um evento para apresentar o crônometro" }}</p>
            </div>
        </div>

        @if (!$isJudge)
            <button wire:click="openModal" class="p-2 rounded-lg bg-primary-300/55 text-primary-700 hover:bg-primary-600/80 hover:text-white hover:cursor-pointer">
                <i class="fad fa-ticket-alt p-1"></i>&nbsp;<span class="font-semibold">Selecionar Evento</span>&nbsp;
            </button> 
        @endif
    </div>

    @if ($event)
        <div class="flex flex-col items-center justify-between w-full mt-6 bg-white rounded-lg p-4 shadow">
            <h2 class="text-2xl font-semibold mb-8 mt-4 text-center text-primary-900/40">
                {{ $event->evt_name }}
            </h2>

            <div
                x-data="countdownTimer({{ $startTimestamp }})"
                x-init="init()"
                class="text-center"
            >
                <span
                    class="text-6xl text-primary-800/90 font-mono"
                    x-text="isFinished ? 'Finalizado!!' : formatted()"
                ></span>

                @if (!$isJudge)
                    <div class="mt-10 space-x-2">
                        <button
                            @click="start(); $wire.startTimer()"
                            x-show="!timerId && !isFinished"
                            class="bg-success-300/55 text-success-700 p-2 px-4 rounded-lg hover:bg-success-600/80 hover:text-white hover:cursor-pointer"
                        >
                            <i class="fad fa-play-circle p-1"></i>
                            <span class="font-semibold">Iniciar</span>
                        </button>

                        <!-- Mostrar “Reiniciar” se estiver rodando OU já tiver terminado -->
                        <button
                            @click="restart(); $wire.resetTimer()"
                            x-show="timerId || isFinished"
                            class="bg-primary-300/55 text-primary-700 p-2 px-4 rounded-lg hover:bg-primary-600/80 hover:text-white hover:cursor-pointer"
                        >
                            <i class="fad fa-sync-alt p-1"></i>
                            <span class="font-semibold">Reiniciar</span>
                        </button>

                        <button wire:click="openChangeDurationModal" class="bg-primary-600/80 text-white p-2 px-4 rounded-lg hover:bg-primary-300/55 hover:text-primary-700 hover:cursor-pointer">
                            <i class="fad fa-stopwatch p-1"></i>
                            <span class="font-semibold">Duração</span>
                        </button>
                    </div>
                @else 
                    <div class="mt-10">
                        <h4 class="text-lg font-semibold mb-8 mt-4 text-center text-primary-900/40">
                            O Crônometro é manipulado pelo Administrador do evento
                        </h4>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>

<script>
    document.addEventListener('alpine:init', () => {
      Alpine.data('countdownTimer', (initialTimestamp) => ({
        // bidirecional com Livewire
        duration:       @entangle('duration'),
        startTimestamp: @entangle('startTimestamp'),
        remaining:      0,
        timerId:        null,
    
        // calcula segundos totais
        get durationSec() {
          const [h, m] = this.duration.split(':').map(Number)
          return h * 3600 + m * 60
        },
    
        // sinaliza quando chegou a zero
        get isFinished() {
          return this.remaining <= 0
        },
    
        init() {
          // 1) importa o valor que veio na renderização
          this.startTimestamp = initialTimestamp
    
          // 2) calcula o restante e retoma se estiver no meio
          this.calcRemaining()
          if (this.startTimestamp > 0 && this.remaining > 0) {
            this.startTick()
          }
    
          // 3) toda vez que duration mudar, refaz remaining
          this.$watch('duration', () => this.calcRemaining())
    
          // 4) e toda vez que startTimestamp mudar (inclusive ao trocar de evento),
          //    refaz e retoma o tick
          this.$watch('startTimestamp', () => {
            this.calcRemaining()
            if (this.startTimestamp > 0 && this.remaining > 0) {
              this.startTick()
            } else {
              clearInterval(this.timerId)
              this.timerId = null
            }
          })
        },
    
        // apenas dispara o backend — o watcher vai cuidar de iniciar o tick
        start() {
          if (this.timerId) return
          this.$wire.startTimer()
        },
    
        // cria o setInterval de decremento
        startTick() {
          if (this.timerId) return
          this.timerId = setInterval(() => {
            if (this.remaining > 0) {
              this.remaining--
            } else {
              clearInterval(this.timerId)
              this.timerId = null
            }
          }, 1000)
        },
    
        // reinicia tudo
        restart() {
          this.$wire.resetTimer()
        },
    
        // calcula o remaining de acordo com o timestamp
        calcRemaining() {
          const now = Math.floor(Date.now()/1000)
          if (this.startTimestamp > 0) {
            const elapsed = now - this.startTimestamp
            this.remaining = Math.max(this.durationSec - elapsed, 0)
          } else {
            this.remaining = this.durationSec
          }
        },
    
        // formata HH:MM:SS
        formatted() {
          const s  = this.remaining
          const hh = String(Math.floor(s/3600)).padStart(2,'0')
          const mm = String(Math.floor((s%3600)/60)).padStart(2,'0')
          const ss = String(s%60).padStart(2,'0')
          return `${hh}:${mm}:${ss}`
        }
      }))
    })
    </script>