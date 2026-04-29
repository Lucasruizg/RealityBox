<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function home()
    {
        $featuredProducts = Product::where('is_featured', 1)->take(8)->get();
        $popularProducts = Product::where('is_popular', 1)->take(6)->get();

        $categories = Product::select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('welcome', compact('featuredProducts', 'popularProducts', 'categories'));
    }

    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->filled('q')) {
            $query->where(function ($subquery) use ($request) {
                $subquery->where('name', 'like', '%' . $request->q . '%')
                    ->orWhere('description', 'like', '%' . $request->q . '%');
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('price')) {
            if ($request->price === 'low') {
                $query->where('price', '<=', 5);
            } elseif ($request->price === 'medium') {
                $query->whereBetween('price', [5.01, 30]);
            } elseif ($request->price === 'premium') {
                $query->where('price', '>', 30);
            }
        }

        if ($request->filled('sort')) {
            if ($request->sort === 'price_asc') {
                $query->orderBy('price', 'asc');
            } elseif ($request->sort === 'price_desc') {
                $query->orderBy('price', 'desc');
            } elseif ($request->sort === 'rating') {
                $query->orderBy('rating', 'desc');
            } else {
                $query->latest();
            }
        } else {
            $query->latest();
        }

        $products = $query->paginate(12)->withQueryString();

        $popularProducts = Product::where('is_popular', 1)->take(6)->get();

        $categories = Product::select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('products.index', compact('products', 'popularProducts', 'categories'));
    }

    public function show(Product $product)
    {
        $relatedProducts = Product::where('category', $product->category)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        $rewards = $this->getRewardsByCategory();
        $possibleRewards = $rewards[$product->category] ?? $rewards['default'];
        $probabilities = $this->getRewardProbabilities();

        return view('products.show', compact('product', 'relatedProducts', 'possibleRewards', 'probabilities'));
    }

    public function test()
    {
        return view('test');
    }

    public function resultado(Request $request)
    {
        $request->validate([
            'interes' => 'required|string',
            'estilo' => 'required|string',
            'premio' => 'required|string',
            'presupuesto' => 'required|string',
        ]);

        $scores = [
            'gaming' => 0,
            'fitness' => 0,
            'moda' => 0,
            'franquicia' => 0,
            'tech' => 0,
            'cards' => 0,
            'lowcost' => 0,
        ];

        $answers = [
            $request->interes,
            $request->estilo,
            $request->premio,
        ];

        foreach ($answers as $answer) {
            if (array_key_exists($answer, $scores)) {
                $scores[$answer] += 3;
            }
        }

        if ($request->presupuesto === 'lowcost') {
            $scores['lowcost'] += 4;
            $scores['cards'] += 1;
        } elseif ($request->presupuesto === 'medio') {
            $scores['gaming'] += 1;
            $scores['fitness'] += 1;
            $scores['moda'] += 1;
            $scores['franquicia'] += 1;
            $scores['tech'] += 1;
            $scores['cards'] += 1;
        } elseif ($request->presupuesto === 'premium') {
            $scores['gaming'] += 2;
            $scores['moda'] += 2;
            $scores['tech'] += 2;
            $scores['franquicia'] += 2;
        }

        arsort($scores);
        $recommendedCategory = array_key_first($scores);

        $product = Product::where('category', $recommendedCategory)
            ->orderByDesc('rating')
            ->first();

        return view('resultado', compact('product', 'recommendedCategory', 'scores'));
    }

    public function open(Product $product)
    {
        $probabilities = $this->getRewardProbabilities();

        $rewards = $this->getRewardsByCategory();

        $categoryRewards = $rewards[$product->category] ?? $rewards['default'];

        $rarity = $this->rollRarity();
        $reward = $this->getRandomRewardByRarity($categoryRewards, $rarity);

        if (!$reward) {
            $rarity = 'comun';
            $reward = $this->getRandomRewardByRarity($categoryRewards, $rarity);
        }

        $sellPrice = round($reward['value'] * 0.70, 2);
        $rarityLabel = $this->getRarityLabel($rarity);
        $rarityClass = $rarity;

        return view('products.open', compact(
            'product',
            'reward',
            'sellPrice',
            'rarity',
            'rarityLabel',
            'rarityClass',
            'probabilities'
        ));
    }

    private function rollRarity(): string
    {
        $roll = mt_rand(1, 1000);

        if ($roll <= 720) {
            return 'comun';
        }

        if ($roll <= 920) {
            return 'raro';
        }

        if ($roll <= 992) {
            return 'epico';
        }

        return 'legendario';
    }

    private function getRewardProbabilities(): array
    {
        return [
            'comun' => 72.0,
            'raro' => 20.0,
            'epico' => 7.2,
            'legendario' => 0.8,
        ];
    }

    private function getRandomRewardByRarity(array $categoryRewards, string $rarity): ?array
    {
        $filtered = array_values(array_filter($categoryRewards, function ($reward) use ($rarity) {
            return $reward['rarity'] === $rarity;
        }));

        if (empty($filtered)) {
            return null;
        }

        return $filtered[array_rand($filtered)];
    }

    private function getRarityLabel(string $rarity): string
    {
        return match ($rarity) {
            'comun' => 'Común',
            'raro' => 'Raro',
            'epico' => 'Épico',
            'legendario' => 'Legendario',
            default => 'Común',
        };
    }

    private function getRewardsByCategory(): array
    {
        return [
            'gaming' => [
                ['name' => 'Sticker pack gamer + llavero', 'rarity' => 'comun', 'value' => 4.99],
                ['name' => 'Pulsera gamer', 'rarity' => 'comun', 'value' => 5.99],
                ['name' => 'Mousepad mini', 'rarity' => 'comun', 'value' => 9.99],
                ['name' => 'Taza gamer + mousepad XL', 'rarity' => 'raro', 'value' => 24.99],
                ['name' => 'Gorra gaming', 'rarity' => 'raro', 'value' => 29.99],
                ['name' => 'Auriculares gaming RGB', 'rarity' => 'epico', 'value' => 69.99],
                ['name' => 'Teclado mecánico compacto', 'rarity' => 'epico', 'value' => 89.99],
                ['name' => 'Setup gaming completo: teclado, ratón, auriculares y silla', 'rarity' => 'legendario', 'value' => 599.99],
            ],

            'fitness' => [
                ['name' => 'Pulsera fitness + sticker motivacional', 'rarity' => 'comun', 'value' => 2.99],
                ['name' => 'Toalla gym básica', 'rarity' => 'comun', 'value' => 6.99],
                ['name' => 'Shaker básico', 'rarity' => 'comun', 'value' => 8.99],
                ['name' => 'Shaker premium + toalla gym', 'rarity' => 'raro', 'value' => 17.99],
                ['name' => 'Banda elástica pro', 'rarity' => 'raro', 'value' => 24.99],
                ['name' => 'Pack entrenamiento completo', 'rarity' => 'epico', 'value' => 49.99],
                ['name' => 'Botella térmica + accesorios gym', 'rarity' => 'epico', 'value' => 59.99],
                ['name' => 'Pack fitness premium: smartband, suplementos y material completo', 'rarity' => 'legendario', 'value' => 399.99],
            ],

            'moda' => [
                ['name' => 'Llavero sneaker + stickers', 'rarity' => 'comun', 'value' => 4.99],
                ['name' => 'Calcetines streetwear', 'rarity' => 'comun', 'value' => 9.99],
                ['name' => 'Lace pack sneaker', 'rarity' => 'comun', 'value' => 12.99],
                ['name' => 'Gorra streetwear', 'rarity' => 'raro', 'value' => 24.99],
                ['name' => 'Camiseta urban premium', 'rarity' => 'raro', 'value' => 34.99],
                ['name' => 'Hoodie streetwear', 'rarity' => 'epico', 'value' => 69.99],
                ['name' => 'Pack outfit básico', 'rarity' => 'epico', 'value' => 89.99],
                ['name' => 'Zapatillas edición especial', 'rarity' => 'legendario', 'value' => 599.99],
            ],

            'franquicia' => [
                ['name' => 'Carta random + sticker pack', 'rarity' => 'comun', 'value' => 3.99],
                ['name' => 'Mini accesorio temático', 'rarity' => 'comun', 'value' => 5.99],
                ['name' => 'Poster pequeño coleccionable', 'rarity' => 'comun', 'value' => 8.99],
                ['name' => 'Booster pack + mini póster', 'rarity' => 'raro', 'value' => 19.99],
                ['name' => 'Mini figura temática', 'rarity' => 'raro', 'value' => 29.99],
                ['name' => 'Figura premium + sobres especiales', 'rarity' => 'epico', 'value' => 69.99],
                ['name' => 'Collector bundle temático', 'rarity' => 'epico', 'value' => 89.99],
                ['name' => 'Carta ultra rara / elite collector box', 'rarity' => 'legendario', 'value' => 549.99],
            ],

            'tech' => [
                ['name' => 'Cable organizer + paño tech', 'rarity' => 'comun', 'value' => 3.99],
                ['name' => 'Accesorio USB', 'rarity' => 'comun', 'value' => 7.99],
                ['name' => 'Soporte móvil básico', 'rarity' => 'comun', 'value' => 9.99],
                ['name' => 'Cargador rápido + soporte móvil', 'rarity' => 'raro', 'value' => 22.99],
                ['name' => 'Soporte escritorio pro', 'rarity' => 'raro', 'value' => 29.99],
                ['name' => 'Auriculares bluetooth premium', 'rarity' => 'epico', 'value' => 69.99],
                ['name' => 'Powerbank premium', 'rarity' => 'epico', 'value' => 79.99],
                ['name' => 'Pack tech premium: smartwatch, auriculares y powerbank top', 'rarity' => 'legendario', 'value' => 499.99],
            ],

            'cards' => [
                ['name' => '1 sobre random', 'rarity' => 'comun', 'value' => 1.99],
                ['name' => 'Pack fundas básicas', 'rarity' => 'comun', 'value' => 4.99],
                ['name' => 'Dado + token pack', 'rarity' => 'comun', 'value' => 6.99],
                ['name' => '3 sobres random', 'rarity' => 'raro', 'value' => 9.99],
                ['name' => 'Deck box + booster', 'rarity' => 'raro', 'value' => 19.99],
                ['name' => 'Bundle TCG especial', 'rarity' => 'epico', 'value' => 34.99],
                ['name' => 'Playmat + sobres especiales', 'rarity' => 'epico', 'value' => 49.99],
                ['name' => 'Collector booster / carta chase', 'rarity' => 'legendario', 'value' => 249.99],
            ],

            'lowcost' => [
                ['name' => 'Sticker random', 'rarity' => 'comun', 'value' => 0.20],
                ['name' => 'Mini pegatina premium', 'rarity' => 'comun', 'value' => 0.50],
                ['name' => 'Carta random básica', 'rarity' => 'comun', 'value' => 0.99],
                ['name' => 'Llavero random', 'rarity' => 'raro', 'value' => 1.99],
                ['name' => 'Mini accesorio sorpresa', 'rarity' => 'raro', 'value' => 2.99],
                ['name' => 'Mini premio sorpresa premium', 'rarity' => 'epico', 'value' => 9.99],
                ['name' => 'Pack lowcost top', 'rarity' => 'epico', 'value' => 14.99],
                ['name' => 'Premio sorpresa premium', 'rarity' => 'legendario', 'value' => 49.99],
            ],

            'default' => [
                ['name' => 'Detalle sorpresa básico', 'rarity' => 'comun', 'value' => 3.99],
                ['name' => 'Producto especial', 'rarity' => 'raro', 'value' => 14.99],
                ['name' => 'Premio premium', 'rarity' => 'epico', 'value' => 49.99],
                ['name' => 'Premio legendario', 'rarity' => 'legendario', 'value' => 299.99],
            ],
        ];
    }
}
