<?php

namespace App\Http\Controllers;

use App\Models\BoxOpening;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function home()
    {
        $featuredProducts = Product::where('is_featured', 1)->take(8)->get();
        $popularProducts = Product::where('is_popular', 1)->take(6)->get();
        $categories = $this->productCategories();

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

        if ($request->sort === 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($request->sort === 'price_desc') {
            $query->orderBy('price', 'desc');
        } elseif ($request->sort === 'rating') {
            $query->orderBy('rating', 'desc');
        } else {
            $query->latest();
        }

        $products = $query->paginate(12)->withQueryString();
        $popularProducts = Product::where('is_popular', 1)->take(6)->get();
        $categories = $this->productCategories();

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

    public function dashboard()
    {
        $products = Product::latest()->paginate(10);
        $totalProducts = Product::count();
        $featuredCount = Product::where('is_featured', 1)->count();
        $popularCount = Product::where('is_popular', 1)->count();
        $averagePrice = Product::avg('price') ?? 0;
        $categories = $this->productCategories();

        return view('dashboard', compact(
            'products',
            'totalProducts',
            'featuredCount',
            'popularCount',
            'averagePrice',
            'categories'
        ));
    }

    public function create()
    {
        $categories = $this->dashboardCategories();
        $product = new Product();

        return view('dashboard-form', compact('product', 'categories'));
    }

    public function store(Request $request)
    {
        Product::create($this->validatedProductData($request));

        return redirect()
            ->route('dashboard')
            ->with('status', 'Caja creada correctamente.');
    }

    public function edit(Product $product)
    {
        $categories = $this->dashboardCategories();

        return view('dashboard-form', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $product->update($this->validatedProductData($request));

        return redirect()
            ->route('dashboard')
            ->with('status', 'Caja actualizada correctamente.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()
            ->route('dashboard')
            ->with('status', 'Caja eliminada correctamente.');
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

        foreach ([$request->interes, $request->estilo, $request->premio] as $answer) {
            if (array_key_exists($answer, $scores)) {
                $scores[$answer] += 3;
            }
        }

        if ($request->presupuesto === 'lowcost') {
            $scores['lowcost'] += 4;
            $scores['cards'] += 1;
        } elseif ($request->presupuesto === 'medio') {
            foreach (['gaming', 'fitness', 'moda', 'franquicia', 'tech', 'cards'] as $category) {
                $scores[$category] += 1;
            }
        } elseif ($request->presupuesto === 'premium') {
            foreach (['gaming', 'moda', 'tech', 'franquicia'] as $category) {
                $scores[$category] += 2;
            }
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
        $user = auth()->user();

        if ($user && $user->demo_balance < $product->price) {
            return redirect()
                ->route('products.show', $product)
                ->with('status', 'No tienes saldo demo suficiente para abrir esta caja.');
        }

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
        $opening = BoxOpening::create([
            'user_id' => $user?->id,
            'session_id' => session()->getId(),
            'product_id' => $product->id,
            'product_name' => $product->name,
            'reward_name' => $reward['name'],
            'rarity' => $rarity,
            'reward_value' => $reward['value'],
            'sell_price' => $sellPrice,
            'status' => 'pending',
        ]);

        if ($user) {
            $user->decrement('demo_balance', $product->price);
            $user->refresh();
        }

        return view('products.open', compact(
            'product',
            'opening',
            'reward',
            'sellPrice',
            'rarity',
            'rarityLabel',
            'rarityClass',
            'probabilities'
        ));
    }

    public function inventory()
    {
        $user = auth()->user();

        $openings = $user->boxOpenings()
            ->with('product')
            ->latest()
            ->paginate(12);

        $stats = [
            'total' => $user->boxOpenings()->count(),
            'kept' => $user->boxOpenings()->where('status', 'kept')->count(),
            'redeemed' => $user->boxOpenings()->where('status', 'redeemed')->count(),
            'sold' => $user->boxOpenings()->where('status', 'sold')->count(),
        ];

        return view('inventory', compact('openings', 'stats'));
    }

    public function updateOpeningStatus(BoxOpening $boxOpening, string $action)
    {
        abort_unless($boxOpening->user_id === auth()->id(), 403);

        if ($boxOpening->status !== 'pending') {
            return back()->with('status', 'Esta recompensa ya fue gestionada.');
        }

        $status = match ($action) {
            'keep' => 'kept',
            'redeem' => 'redeemed',
            'sell' => 'sold',
        };

        $boxOpening->update(['status' => $status]);

        if ($status === 'sold') {
            auth()->user()->increment('demo_balance', $boxOpening->sell_price);
        }

        return redirect()
            ->route('inventory')
            ->with('status', 'Recompensa actualizada correctamente.');
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

    private function dashboardCategories(): array
    {
        return [
            'gaming',
            'fitness',
            'moda',
            'franquicia',
            'tech',
            'cards',
            'lowcost',
        ];
    }

    private function productCategories()
    {
        return Product::select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');
    }

    private function validatedProductData(Request $request): array
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'image' => [
                'nullable',
                'string',
                'max:255',
                function (string $attribute, mixed $value, \Closure $fail) {
                    if ($value && !file_exists(public_path('images/products/' . $value))) {
                        $fail('La imagen indicada no existe en public/images/products.');
                    }
                },
            ],
            'category' => ['required', 'string', Rule::in($this->dashboardCategories())],
            'rating' => 'required|numeric|min:0|max:5',
            'reviews' => 'required|integer|min:0',
        ]);

        $data['is_popular'] = $request->boolean('is_popular');
        $data['is_featured'] = $request->boolean('is_featured');

        return $data;
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
        return config('rewards');
    }
}
