@extends('layouts.app')

@section('content')
<section class="section container">
    <div class="product-detail">
        <div class="product-box-visual">
            @if($product->image)
                <img src="{{ asset('images/products/' . $product->image) }}" alt="{{ $product->name }}" class="product-detail-image">
            @else
                <div class="fake-box">
                    <span>RealityBox</span>
                </div>
            @endif
        </div>

        <div>
            <span class="tag tag-secondary">{{ ucfirst($product->category) }}</span>
            <h1>{{ $product->name }}</h1>
            <p class="product-description">{{ $product->description }}</p>

            <div class="product-stats">
                <div><strong>{{ number_format($product->price, 2) }} €</strong><span>Precio</span></div>
                <div><strong>{{ $product->rating }}</strong><span>Rating</span></div>
                <div><strong>{{ $product->reviews }}</strong><span>Reviews</span></div>
            </div>

            <div class="hero-actions">
                <a href="{{ route('products.open', $product->id) }}" class="btn btn-open-box">Abrir caja</a>
                <a href="{{ route('products.index') }}" class="btn btn-secondary">Volver</a>
            </div>
        </div>
    </div>
</section>

<section class="section container">
    <div class="section-head">
        <h2>Objetos que pueden tocar</h2>
        <p>Estos premios salen en esta caja según su rareza y probabilidad oficial.</p>
    </div>

    <div class="rewards-list">
        @foreach($possibleRewards as $possibleReward)
            @php
                $rarityLabels = [
                    'comun' => 'Común',
                    'raro' => 'Raro',
                    'epico' => 'Épico',
                    'legendario' => 'Legendario',
                ];

                $rarity = $possibleReward['rarity'];
            @endphp

            <div class="reward-preview {{ $rarity }}">
                <div>
                    <span class="rarity-badge {{ $rarity }}">{{ $rarityLabels[$rarity] }}</span>
                    <h3>{{ $possibleReward['name'] }}</h3>
                </div>

                <div class="reward-preview-meta">
                    <div>
                        <span>Valor</span>
                        <strong>{{ number_format($possibleReward['value'], 2) }} €</strong>
                    </div>
                    <div>
                        <span>Probabilidad</span>
                        <strong>{{ $probabilities[$rarity] }}%</strong>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>

@if($relatedProducts->count())
<section class="section container">
    <div class="section-head">
        <h2>Más de esta categoría</h2>
    </div>

    <div class="grid cards-grid">
        @foreach($relatedProducts as $item)
            <div class="card compact">
                @if($item->image)
                    <img src="{{ asset('images/products/' . $item->image) }}" alt="{{ $item->name }}" class="product-image">
                @else
                    <div class="fake-image">RealityBox</div>
                @endif

                <h3>{{ $item->name }}</h3>
                <p>{{ number_format($item->price, 2) }} €</p>
                <a href="{{ route('products.show', $item->id) }}" class="btn btn-dark">Ver caja</a>
            </div>
        @endforeach
    </div>
</section>
@endif
@endsection
