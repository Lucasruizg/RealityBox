@extends('layouts.app')

@section('content')
@php
    $isEditing = $product->exists;
@endphp

<section class="section container">
    <div class="dashboard-head">
        <div>
            <span class="tag">{{ $isEditing ? 'Editar caja' : 'Nueva caja' }}</span>
            <h1>{{ $isEditing ? 'Actualizar RealityBox' : 'Crear RealityBox' }}</h1>
            <p>Completa los datos del producto. La imagen debe existir en public/images/products.</p>
        </div>

        <a href="{{ route('dashboard') }}" class="btn btn-secondary">Volver al dashboard</a>
    </div>

    @if($errors->any())
        <div class="dashboard-alert dashboard-alert-error">
            Revisa los campos marcados antes de guardar.
        </div>
    @endif

    <form
        action="{{ $isEditing ? route('dashboard.products.update', $product->id) : route('dashboard.products.store') }}"
        method="POST"
        class="dashboard-form"
    >
        @csrf
        @if($isEditing)
            @method('PUT')
        @endif

        <div class="dashboard-form-grid">
            <label>
                Nombre
                <input type="text" name="name" value="{{ old('name', $product->name) }}" required>
                @error('name') <span>{{ $message }}</span> @enderror
            </label>

            <label>
                Categoría
                <select name="category" required>
                    @foreach($categories as $category)
                        <option value="{{ $category }}" @selected(old('category', $product->category) === $category)>
                            {{ ucfirst($category) }}
                        </option>
                    @endforeach
                </select>
                @error('category') <span>{{ $message }}</span> @enderror
            </label>

            <label>
                Precio
                <input type="number" name="price" step="0.01" min="0" value="{{ old('price', $product->price) }}" required>
                @error('price') <span>{{ $message }}</span> @enderror
            </label>

            <label>
                Imagen
                <input type="text" name="image" value="{{ old('image', $product->image) }}" placeholder="gamer-pro.png">
                @error('image') <span>{{ $message }}</span> @enderror
            </label>

            <label>
                Rating
                <input type="number" name="rating" step="0.1" min="0" max="5" value="{{ old('rating', $product->rating ?? 4.8) }}" required>
                @error('rating') <span>{{ $message }}</span> @enderror
            </label>

            <label>
                Reviews
                <input type="number" name="reviews" min="0" value="{{ old('reviews', $product->reviews ?? 0) }}" required>
                @error('reviews') <span>{{ $message }}</span> @enderror
            </label>
        </div>

        <label>
            Descripción
            <textarea name="description" rows="5" required>{{ old('description', $product->description) }}</textarea>
            @error('description') <span>{{ $message }}</span> @enderror
        </label>

        <div class="dashboard-switches">
            <label>
                <input type="checkbox" name="is_popular" value="1" @checked(old('is_popular', $product->is_popular))>
                Marcar como popular
            </label>

            <label>
                <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $product->is_featured))>
                Marcar como destacada
            </label>
        </div>

        <div class="hero-actions">
            <button type="submit" class="btn btn-open-box">{{ $isEditing ? 'Guardar cambios' : 'Crear caja' }}</button>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</section>
@endsection
