@extends('layouts.app')

@section('content')
@php
    $rarityNames = [
        'comun' => 'Común',
        'raro' => 'Raro',
        'epico' => 'Épico',
        'legendario' => 'Legendario',
    ];

    $beforeItems = [
        'comun', 'raro', 'comun', 'epico', 'comun',
        'raro', 'comun', 'comun', 'raro', 'epico',
        'comun', 'raro', 'comun', 'comun', 'epico',
        'raro', 'comun', 'raro'
    ];

    $afterItems = [
        'comun', 'raro', 'comun', 'epico', 'comun',
        'raro', 'comun', 'comun', 'raro', 'epico'
    ];
@endphp

<section class="section container">
    <div class="open-box-wrapper">
        @if(session('status'))
            <div class="dashboard-alert">
                {{ session('status') }}
            </div>
        @endif

        <span class="tag">Apertura online</span>
        <h1>Has abierto {{ $product->name }}</h1>
        <p class="open-box-subtitle">La ruleta está girando. Cuando se detenga, el marcador señalará tu rareza final.</p>

        <div class="lootbox-stage">
            <div class="lootbox-window" id="lootboxWindow">
                <div class="lootbox-track" id="lootboxTrack">
                    @foreach($beforeItems as $item)
                        <div class="lootbox-item {{ $item }}">
                            {{ $rarityNames[$item] }}
                        </div>
                    @endforeach

                    <div class="lootbox-item {{ $rarityClass }}" id="winnerItem">
                        {{ $rarityLabel }}
                    </div>

                    @foreach($afterItems as $item)
                        <div class="lootbox-item {{ $item }}">
                            {{ $rarityNames[$item] }}
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="lootbox-pointer" aria-hidden="true"></div>
        </div>

        <div id="openingMessage" class="opening-message">
            Abriendo caja...
        </div>

        <div class="reward-card hidden-reward {{ $rarityClass }}" id="rewardCard">
            <div class="rarity-badge {{ $rarityClass }}">
                {{ $rarityLabel }}
            </div>

            <h2>{{ $reward['name'] }}</h2>

            <div class="reward-values">
                <div>
                    <span>Rareza</span>
                    <strong>{{ $rarityLabel }}</strong>
                </div>
                <div>
                    <span>Valor estimado</span>
                    <strong>{{ number_format($reward['value'], 2) }} €</strong>
                </div>
                <div>
                    <span>Venta rápida</span>
                    <strong>{{ number_format($sellPrice, 2) }} €</strong>
                </div>
            </div>
        </div>

        <div class="hero-actions center hidden-reward" id="rewardActions">
            @auth
                @if($opening->status === 'pending')
                    <form action="{{ route('openings.action', [$opening, 'keep']) }}" method="POST">
                        @csrf
                        <input type="hidden" name="redirect_to" value="{{ route('openings.show', $opening) }}">
                        <button class="btn btn-primary" type="submit">Quedármelo</button>
                    </form>
                    <form action="{{ route('openings.action', [$opening, 'redeem']) }}" method="POST">
                        @csrf
                        <input type="hidden" name="redirect_to" value="{{ route('openings.show', $opening) }}">
                        <button class="btn btn-secondary" type="submit">Canjearlo</button>
                    </form>
                    <form action="{{ route('openings.action', [$opening, 'sell']) }}" method="POST">
                        @csrf
                        <input type="hidden" name="redirect_to" value="{{ route('openings.show', $opening) }}">
                        <button class="btn btn-dark" type="submit">Venderlo</button>
                    </form>
                @else
                    <span class="inventory-status">Estado: {{ $opening->status_label }}</span>
                @endif
            @else
                <button class="btn btn-primary" type="button" disabled>Quedármelo</button>
                <button class="btn btn-secondary" type="button" disabled>Canjearlo</button>
                <button class="btn btn-dark" type="button" disabled>Venderlo</button>
                <a href="{{ route('login') }}" class="btn btn-primary">Inicia sesión para guardarlo</a>
            @endauth
        </div>

        <div class="probability-box">
            <h3>Probabilidades</h3>
            <div class="probability-grid">
                <div class="prob comun">Común: {{ $probabilities['comun'] }}%</div>
                <div class="prob raro">Raro: {{ $probabilities['raro'] }}%</div>
                <div class="prob epico">Épico: {{ $probabilities['epico'] }}%</div>
                <div class="prob legendario">Legendario: {{ $probabilities['legendario'] }}%</div>
            </div>
        </div>

        <div class="hero-actions center">
            <a href="{{ route('products.open', $product->id) }}" class="btn btn-open-box">Volver a abrir la caja</a>
            <a href="{{ route('products.show', $product->id) }}" class="btn btn-secondary">Volver a la caja</a>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Seguir explorando</a>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const track = document.getElementById('lootboxTrack');
    const windowEl = document.getElementById('lootboxWindow');
    const winnerItem = document.getElementById('winnerItem');
    const rewardCard = document.getElementById('rewardCard');
    const rewardActions = document.getElementById('rewardActions');
    const openingMessage = document.getElementById('openingMessage');
    let hasFinished = false;

    function centerWinner() {
        const winnerCenter = winnerItem.offsetLeft + (winnerItem.offsetWidth / 2);
        const windowCenter = windowEl.offsetWidth / 2;
        const finalOffset = windowCenter - winnerCenter;

        track.style.setProperty('--lootbox-final-offset', finalOffset + 'px');
        if (hasFinished) {
            track.style.transform = 'translateX(' + finalOffset + 'px)';
            return;
        }

        track.classList.remove('spin-track');

        requestAnimationFrame(function () {
            track.classList.add('spin-track');
        });
    }

    centerWinner();
    window.addEventListener('resize', centerWinner);

    setTimeout(function () {
        hasFinished = true;
        openingMessage.innerText = 'Premio desbloqueado';
        winnerItem.classList.add('winner');
        rewardCard.classList.remove('hidden-reward');
        rewardActions.classList.remove('hidden-reward');
        rewardCard.classList.add('show-reward');
    }, 4300);
});
</script>
@endsection
