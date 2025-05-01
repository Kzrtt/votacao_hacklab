<div class="mt-10 max-w-md mx-auto p-6 bg-white rounded-lg shadow">
    <h2 class="text-2xl font-semibold mb-4">
        {{ $event->evt_name }} – Contagem Regressiva, {{ $duration }}
    </h2>

    <div
        x-data="countdownTimer({{ $startTimestamp }}, '{{ $duration }}')"
        x-init="init()"
        class="text-4xl font-mono text-center mb-4"
    >
        <span x-text="formatted()"></span>

        <div class="mt-4">
            <button
            @click="reset()"
            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
            >
            Reiniciar
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
      Alpine.data('countdownTimer', (start, target) => ({
        // total de segundos da duração “HH:MM”
        durationSec: (() => {
          const [h, m] = target.split(':').map(Number)
          return h * 3600 + m * 60
        })(),
  
        // quantos segundos faltam
        remaining: 0,
  
        // guarda o setInterval pra poder limpar depois
        timerId: null,
  
        // chamado automaticamente ao montar o componente
        init() {
          const nowSec = Math.floor(Date.now() / 1000)
          const elapsed = nowSec - start
          // se já passou, subtrai; senão, começa do full
          this.remaining = Math.max(this.durationSec - elapsed, 0)
          if (this.remaining > 0) {
            this.startTick()
          }
        },
  
        // inicia / reinicia o setInterval
        startTick() {
          // limpa qualquer intervalo anterior
          if (this.timerId) clearInterval(this.timerId)
  
          this.timerId = setInterval(() => {
            if (this.remaining > 0) {
              this.remaining--
            } else {
              clearInterval(this.timerId)
            }
          }, 1000)
        },
  
        // formata segundos em “HH:MM:SS”
        formatted() {
          const s = this.remaining
          const hh = String(Math.floor(s / 3600)).padStart(2, '0')
          const mm = String(Math.floor((s % 3600) / 60)).padStart(2, '0')
          const ss = String(s % 60).padStart(2, '0')
          return `${hh}:${mm}:${ss}`
        },
  
        // para o botão “Reiniciar”
        reset() {
          this.remaining = this.durationSec
          this.startTick()
        }
      }))
    })
  </script>
  