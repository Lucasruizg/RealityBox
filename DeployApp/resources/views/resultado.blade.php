@extends('layouts.app')

@section('content')
<section class="section container">
    <div class="result-card">
        <span class="tag">Resultado del test</span>
        <h1>Tu RealityBox recomendada</h1>
        <p class="test-intro">
            Según tus respuestas, la categoría que mejor encaja contigo es
            <strong>{{ ucfirst($recommendedCategory ?? 'default') }}</strong>.
        </p>

        @if($product)
            <div class="card result-inner recommendation-card">
                @if($product->image)
                    <img src="{{ asset('images/products/' . $product->image) }}" alt="{{ $product->name }}" class="product-image">
                @else
                    <div class="fake-image">RealityBox</div>
                @endif

                <span class="tag tag-secondary">{{ ucfirst($product->category) }}</span>
                <h2>{{ $product->name }}</h2>
                <p>{{ $product->description }}</p>
                <p><strong>{{ number_format($product->price, 2) }} €</strong></p>

                <div class="hero-actions center">
                    <a href="{{ route('products.show', $product->id) }}" class="btn btn-primary">Ver caja</a>
                    <a href="{{ route('products.open', $product->id) }}" class="btn btn-dark">Abrir online</a>
                    <a href="{{ route('test') }}" class="btn btn-secondary">Repetir test</a>
                </div>
            </div>
        @else
            <p>No hemos encontrado una caja para esa selección.</p>
            <div class="hero-actions center">
                <a href="{{ route('products.index') }}" class="btn btn-primary">Ver catálogo</a>
                <a href="{{ route('test') }}" class="btn btn-secondary">Repetir test</a>
            </div>
        @endif
    </div>
</section>
@endsection
