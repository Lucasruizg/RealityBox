@extends('layouts.app')

@section('content')
<section class="section container">
    <div class="dashboard-head">
        <div>
            <span class="tag">Inventario</span>
            <h1>Mis aperturas</h1>
            <p>Consulta tus premios, su rareza, valor y estado dentro de RealityBox.</p>
        </div>

        <div class="wallet-card">
            <span>Saldo demo</span>
            <strong>{{ number_format(auth()->user()->demo_balance, 2) }} €</strong>
        </div>
    </div>

    @if(session('status'))
        <div class="dashboard-alert">
            {{ session('status') }}
        </div>
    @endif

    <div class="dashboard-stats">
        <div>
            <span>Total aperturas</span>
            <strong>{{ $stats['total'] }}</strong>
        </div>
        <div>
            <span>Guardados</span>
            <strong>{{ $stats['kept'] }}</strong>
        </div>
        <div>
            <span>Canjeados</span>
            <strong>{{ $stats['redeemed'] }}</strong>
        </div>
        <div>
            <span>Vendidos</span>
            <strong>{{ $stats['sold'] }}</strong>
        </div>
    </div>

    <div class="inventory-grid">
        @forelse($openings as $opening)
            <article class="inventory-card {{ $opening->rarity }}">
                <div class="inventory-card-head">
                    <span class="rarity-badge {{ $opening->rarity }}">{{ $opening->rarity_label }}</span>
                    <span class="inventory-status">{{ $opening->status_label }}</span>
                </div>

                <h2>{{ $opening->reward_name }}</h2>
                <p>{{ $opening->product_name }}</p>

                <div class="reward-values">
                    <div>
                        <span>Valor</span>
                        <strong>{{ number_format($opening->reward_value, 2) }} €</strong>
                    </div>
                    <div>
                        <span>Venta rápida</span>
                        <strong>{{ number_format($opening->sell_price, 2) }} €</strong>
                    </div>
                    <div>
                        <span>Fecha</span>
                        <strong>{{ $opening->created_at->format('d/m/Y') }}</strong>
                    </div>
                </div>

                @if($opening->status === 'pending')
                    <div class="hero-actions">
                        <form action="{{ route('openings.action', [$opening, 'keep']) }}" method="POST">
                            @csrf
                            <input type="hidden" name="redirect_to" value="{{ url()->full() }}">
                            <button class="btn btn-primary" type="submit">Guardar</button>
                        </form>
                        <form action="{{ route('openings.action', [$opening, 'redeem']) }}" method="POST">
                            @csrf
                            <input type="hidden" name="redirect_to" value="{{ url()->full() }}">
                            <button class="btn btn-secondary" type="submit">Canjear</button>
                        </form>
                        <form action="{{ route('openings.action', [$opening, 'sell']) }}" method="POST">
                            @csrf
                            <input type="hidden" name="redirect_to" value="{{ url()->full() }}">
                            <button class="btn btn-dark" type="submit">Vender</button>
                        </form>
                    </div>
                @elseif($opening->status === 'kept')
                    <div class="hero-actions">
                        <form action="{{ route('openings.action', [$opening, 'sell']) }}" method="POST">
                            @csrf
                            <input type="hidden" name="redirect_to" value="{{ url()->full() }}">
                            <button class="btn btn-dark" type="submit">Vender</button>
                        </form>
                    </div>
                @endif
            </article>
        @empty
            <div class="empty-state">
                <h2>Aún no has abierto ninguna caja</h2>
                <p>Explora el catálogo y abre una RealityBox para llenar tu inventario.</p>
                <a href="{{ route('products.index') }}" class="btn btn-open-box">Ver cajas</a>
            </div>
        @endforelse
    </div>

    <div class="pagination-wrap">
        {{ $openings->links() }}
    </div>
</section>
@endsection
