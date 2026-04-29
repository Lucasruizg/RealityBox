@extends('layouts.app')

@section('content')
<section class="section container">
    <div class="dashboard-head">
        <div>
            <span class="tag">Panel de gestión</span>
            <h1>Dashboard RealityBox</h1>
            <p>Gestiona cajas, categorías, precios, imágenes, populares y destacadas.</p>
        </div>

        <a href="{{ route('dashboard.products.create') }}" class="btn btn-open-box">Crear caja</a>
    </div>

    @if(session('status'))
        <div class="dashboard-alert">
            {{ session('status') }}
        </div>
    @endif

    <div class="dashboard-stats">
        <div>
            <span>Total cajas</span>
            <strong>{{ $totalProducts }}</strong>
        </div>
        <div>
            <span>Destacadas</span>
            <strong>{{ $featuredCount }}</strong>
        </div>
        <div>
            <span>Populares</span>
            <strong>{{ $popularCount }}</strong>
        </div>
        <div>
            <span>Precio medio</span>
            <strong>{{ number_format($averagePrice, 2) }} €</strong>
        </div>
    </div>

    <div class="dashboard-panel">
        <div class="dashboard-panel-head">
            <div>
                <h2>Cajas disponibles</h2>
                <p>{{ $categories->count() }} categorías activas en catálogo.</p>
            </div>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Ver tienda</a>
        </div>

        <div class="dashboard-table-wrap">
            <table class="dashboard-table">
                <thead>
                    <tr>
                        <th>Caja</th>
                        <th>Categoría</th>
                        <th>Precio</th>
                        <th>Rating</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                        <tr>
                            <td>
                                <div class="dashboard-product">
                                    @if($product->image)
                                        <img src="{{ asset('images/products/' . $product->image) }}" alt="{{ $product->name }}">
                                    @else
                                        <div class="dashboard-product-placeholder">RB</div>
                                    @endif
                                    <div>
                                        <strong>{{ $product->name }}</strong>
                                        <span>{{ $product->image ?: 'Sin imagen' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>{{ ucfirst($product->category) }}</td>
                            <td>{{ number_format($product->price, 2) }} €</td>
                            <td>{{ $product->rating }} / 5</td>
                            <td>
                                <div class="dashboard-flags">
                                    @if($product->is_popular)
                                        <span>Popular</span>
                                    @endif
                                    @if($product->is_featured)
                                        <span>Destacada</span>
                                    @endif
                                    @if(!$product->is_popular && !$product->is_featured)
                                        <span>Normal</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="dashboard-actions">
                                    <a href="{{ route('products.show', $product->id) }}" class="btn btn-secondary">Ver</a>
                                    <a href="{{ route('dashboard.products.edit', $product->id) }}" class="btn btn-dark">Editar</a>
                                    <form action="{{ route('dashboard.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('¿Eliminar esta caja?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="pagination-wrap">
            {{ $products->links() }}
        </div>
    </div>
</section>
@endsection
