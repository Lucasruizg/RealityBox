@extends('layouts.app')

@section('content')
<section class="section container">
    <div class="test-card test-card-wide">
        <span class="tag">Recomendador</span>
        <h1>Encuentra tu RealityBox ideal</h1>
        <p class="test-intro">Responde estas preguntas y te recomendaremos una caja según tus gustos, estilo de premio y presupuesto.</p>

        <form action="{{ route('resultado') }}" method="POST" class="test-form advanced-test">
            @csrf

            <div class="test-question">
                <div>
                    <span class="question-step">1</span>
                    <h2>¿Qué mundo te llama más?</h2>
                </div>

                <div class="option-grid">
                    <label class="test-option">
                        <input type="radio" name="interes" value="gaming" required>
                        <span>Gaming</span>
                    </label>
                    <label class="test-option">
                        <input type="radio" name="interes" value="franquicia">
                        <span>Pokémon, Magic, Marvel</span>
                    </label>
                    <label class="test-option">
                        <input type="radio" name="interes" value="moda">
                        <span>Moda y sneakers</span>
                    </label>
                    <label class="test-option">
                        <input type="radio" name="interes" value="tech">
                        <span>Tech y gadgets</span>
                    </label>
                </div>
            </div>

            <div class="test-question">
                <div>
                    <span class="question-step">2</span>
                    <h2>¿Qué tipo de experiencia prefieres?</h2>
                </div>

                <div class="option-grid">
                    <label class="test-option">
                        <input type="radio" name="estilo" value="cards" required>
                        <span>Coleccionar y abrir sobres</span>
                    </label>
                    <label class="test-option">
                        <input type="radio" name="estilo" value="gaming">
                        <span>Mejorar mi setup</span>
                    </label>
                    <label class="test-option">
                        <input type="radio" name="estilo" value="fitness">
                        <span>Algo útil para entrenar</span>
                    </label>
                    <label class="test-option">
                        <input type="radio" name="estilo" value="lowcost">
                        <span>Abrir rápido y barato</span>
                    </label>
                </div>
            </div>

            <div class="test-question">
                <div>
                    <span class="question-step">3</span>
                    <h2>¿Qué premio te haría más ilusión?</h2>
                </div>

                <div class="option-grid">
                    <label class="test-option">
                        <input type="radio" name="premio" value="tech" required>
                        <span>Un gadget premium</span>
                    </label>
                    <label class="test-option">
                        <input type="radio" name="premio" value="moda">
                        <span>Una prenda o zapatillas</span>
                    </label>
                    <label class="test-option">
                        <input type="radio" name="premio" value="franquicia">
                        <span>Una figura o carta rara</span>
                    </label>
                    <label class="test-option">
                        <input type="radio" name="premio" value="gaming">
                        <span>Periféricos gaming</span>
                    </label>
                </div>
            </div>

            <div class="test-question">
                <div>
                    <span class="question-step">4</span>
                    <h2>¿Qué presupuesto tienes en mente?</h2>
                </div>

                <div class="option-grid option-grid-three">
                    <label class="test-option">
                        <input type="radio" name="presupuesto" value="lowcost" required>
                        <span>Lowcost</span>
                    </label>
                    <label class="test-option">
                        <input type="radio" name="presupuesto" value="medio">
                        <span>Medio</span>
                    </label>
                    <label class="test-option">
                        <input type="radio" name="presupuesto" value="premium">
                        <span>Premium</span>
                    </label>
                </div>
            </div>

            <button type="submit" class="btn btn-open-box">Ver mi recomendación</button>
        </form>
    </div>
</section>
@endsection
