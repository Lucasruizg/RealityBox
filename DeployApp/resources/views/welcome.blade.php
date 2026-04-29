@extends('layouts.app')

@section('content')
@php
    $heroImages = ['gamer-pro.png', 'pokemon.png', 'sneakers.png', 'tech.png', 'booster.png'];
@endphp

<section class="home-hero">
    <div class="hero-showcase" aria-hidden="true">
        @foreach($heroImages as $index => $image)
            <img src="{{ asset('images/products/' . $image) }}" alt="" class="hero-float hero-float-{{ $index + 1 }}">
        @endforeach
    </div>

    <div class="container home-hero-inner">
        <span class="badge">Mystery boxes premium</span>
        <h1>Abre cajas sorpresa con premios reales, rarezas y emoción online.</h1>
        <p class="hero-text">
            Elige tu temática favorita, revisa lo que puede tocarte y abre la caja con una ruleta visual.
        </p>

        <form action="{{ route('products.index') }}" method="GET" class="hero-search">
            <input type="text" name="q" placeholder="Busca gaming, Pokémon, sneakers, tech...">
            <button type="submit" class="btn btn-primary">Buscar</button>
        </form>

        <div class="hero-actions">
            <a href="{{ route('products.index') }}" class="btn btn-open-box">Ver cajas</a>
            <a href="{{ route('test') }}" class="btn btn-secondary">Hacer test</a>
        </div>

        <div class="hero-trust">
            <div><strong>72%</strong><span>Común</span></div>
            <div><strong>20%</strong><span>Raro</span></div>
            <div><strong>7.2%</strong><span>Épico</span></div>
            <div><strong>0.8%</strong><span>Legendario</span></div>
        </div>
    </div>
</section>

<section class="section container home-categories">
    <div class="section-head split-head">
        <div>
            <h2>Elige tu universo</h2>
            <p>Accede directo a las categorías principales de RealityBox.</p>
        </div>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Ver catálogo</a>
    </div>

    <div class="category-showcase">
        @foreach($categories as $category)
            <a href="{{ route('products.index', ['category' => $category]) }}" class="category-tile">
                <span>{{ ucfirst($category) }}</span>
                <strong>Explorar</strong>
            </a>
        @endforeach
    </div>
</section>

@if($popularProducts->count())
<section class="section container">
    <div class="section-head split-head">
        <div>
            <h2>Cajas en tendencia</h2>
            <p>Las aperturas que más llaman la atención ahora mismo.</p>
        </div>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Todas las cajas</a>
    </div>

    <div class="product-carousel">
        @foreach($popularProducts as $product)
            <article class="carousel-card">
                @if($product->image)
                    <img src="{{ asset('images/products/' . $product->image) }}" alt="{{ $product->name }}">
                @else
                    <div class="fake-image">RealityBox</div>
                @endif

                <div class="carousel-card-body">
                    <span class="tag">Popular</span>
                    <h3>{{ $product->name }}</h3>
                    <div class="card-meta">
                        <span>{{ number_format($product->price, 2) }} €</span>
                        <span>{{ ucfirst($product->category) }}</span>
                    </div>
                    <a href="{{ route('products.show', $product->id) }}" class="btn btn-dark">Ver caja</a>
                </div>
            </article>
        @endforeach
    </div>
</section>
@endif

<section class="section container">
    <div class="experience-panel">
        <div>
            <span class="tag tag-secondary">Cómo funciona</span>
            <h2>Una compra sencilla, pero con momento de apertura.</h2>
        </div>

        <div class="experience-steps">
            <div>
                <strong>1</strong>
                <h3>Elige caja</h3>
                <p>Gaming, moda, tech, cartas, franquicias o lowcost.</p>
            </div>
            <div>
                <strong>2</strong>
                <h3>Revisa premios</h3>
                <p>Cada caja muestra objetos, valor, rareza y probabilidad.</p>
            </div>
            <div>
                <strong>3</strong>
                <h3>Abre online</h3>
                <p>La ruleta revela tu recompensa final con una animación especial.</p>
            </div>
        </div>
    </div>
</section>

@if($featuredProducts->count())
<section class="section container">
    <div class="section-head">
        <h2>Selección destacada</h2>
        <p>Cajas preparadas para entrar directo a la experiencia.</p>
    </div>

    <div class="grid cards-grid">
        @foreach($featuredProducts as $product)
            <div class="card">
                @if($product->image)
                    <img src="{{ asset('images/products/' . $product->image) }}" alt="{{ $product->name }}" class="product-image">
                @else
                    <div class="fake-image">RealityBox</div>
                @endif

                <span class="tag tag-secondary">Destacada</span>
                <h3>{{ $product->name }}</h3>
                <p>{{ $product->description }}</p>
                <div class="card-meta">
                    <span>{{ number_format($product->price, 2) }} €</span>
                    <span>{{ ucfirst($product->category) }}</span>
                </div>
                <a href="{{ route('products.show', $product->id) }}" class="btn btn-dark">Ver detalle</a>
            </div>
        @endforeach
    </div>
</section>
@endif
@endsection
