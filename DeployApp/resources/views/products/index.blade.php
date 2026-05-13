@extends('layouts.app')

@section('content')
<section class="section container">
    <div class="section-head">
        <h1>Todas las cajas</h1>
        <p>Busca, filtra y descubre tu próxima RealityBox.</p>
    </div>

    <form method="GET" action="{{ route('products.index') }}" class="filters">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar caja...">

        <select name="category">
            <option value="">Todas las categorías</option>
            @foreach($categories as $category)
                <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                    {{ ucfirst($category) }}
                </option>
            @endforeach
        </select>

        <select name="price">
            <option value="">Todos los precios</option>
            <option value="low" {{ request('price') == 'low' ? 'selected' : '' }}>Low cost</option>
            <option value="medium" {{ request('price') == 'medium' ? 'selected' : '' }}>Media</option>
            <option value="premium" {{ request('price') == 'premium' ? 'selected' : '' }}>Premium</option>
        </select>

        <select name="sort">
            <option value="">Orden por defecto</option>
            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Precio menor</option>
            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Precio mayor</option>
            <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Mejor valoradas</option>
        </select>

        <button type="submit" class="btn btn-primary">Aplicar</button>
    </form>
</section>

@if($popularProducts->count())
<section class="section container">
    <div class="section-head">
        <h2>Las más populares</h2>
    </div>

    <div class="grid cards-grid">
        @foreach($popularProducts as $product)
            <div class="card compact">
                @if($product->image_url)
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="product-image">
                @else
                    <div class="fake-image">RealityBox</div>
                @endif

                <span class="tag">Top</span>
                <h3>{{ $product->name }}</h3>
                <p>{{ number_format($product->price, 2) }} €</p>
                <a href="{{ route('products.show', $product->id) }}" class="btn btn-dark">Ver</a>
            </div>
        @endforeach
    </div>
</section>
@endif

<section class="section container">
    <div class="grid cards-grid">
        @forelse($products as $product)
            <div class="card">
                @if($product->image_url)
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="product-image">
                @else
                    <div class="fake-image">RealityBox</div>
                @endif

                <span class="tag tag-secondary">{{ ucfirst($product->category) }}</span>
                <h3>{{ $product->name }}</h3>
                <p>{{ $product->description }}</p>

                <div class="card-meta">
                    <span>{{ number_format($product->price, 2) }} €</span>
                    <span>⭐ {{ $product->rating }} ({{ $product->reviews }})</span>
                </div>

                <div class="card-actions">
                    <a href="{{ route('products.show', $product->id) }}" class="btn btn-dark">Ver detalle</a>
                    <a href="{{ route('products.open', $product->id) }}" class="btn btn-secondary">Abrir online</a>
                </div>
            </div>
        @empty
            <p>No se han encontrado productos.</p>
        @endforelse
    </div>

    <div class="pagination-wrap">
        {{ $products->links() }}
    </div>
</section>
@endsection
