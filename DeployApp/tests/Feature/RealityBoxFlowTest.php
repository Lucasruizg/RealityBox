<?php

namespace Tests\Feature;

use App\Models\BoxOpening;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RealityBoxFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_catalog_loads(): void
    {
        Product::create($this->productData());

        $this->get(route('products.index'))
            ->assertOk()
            ->assertSee('RealityBox Gamer Pro');
    }

    public function test_open_box_creates_valid_opening_and_discount_balance(): void
    {
        $user = User::factory()->create(['demo_balance' => 100]);
        $product = Product::create($this->productData(['price' => 25]));

        $this->actingAs($user)
            ->get(route('products.open', $product))
            ->assertOk()
            ->assertViewHas('rarity', fn (string $rarity) => in_array($rarity, ['comun', 'raro', 'epico', 'legendario'], true))
            ->assertViewHas('reward');

        $this->assertDatabaseCount('box_openings', 1);
        $this->assertSame('75.00', $user->fresh()->demo_balance);
    }

    public function test_selling_pending_reward_adds_sell_price_to_balance(): void
    {
        $user = User::factory()->create(['demo_balance' => 50]);
        $product = Product::create($this->productData());
        $opening = BoxOpening::create([
            'user_id' => $user->id,
            'session_id' => 'test',
            'product_id' => $product->id,
            'product_name' => $product->name,
            'reward_name' => 'Premio test',
            'rarity' => 'raro',
            'reward_value' => 20,
            'sell_price' => 14,
            'status' => 'pending',
        ]);

        $this->actingAs($user)
            ->post(route('openings.action', [$opening, 'sell']))
            ->assertRedirect(route('inventory'));

        $this->assertSame('sold', $opening->fresh()->status);
        $this->assertSame('64.00', $user->fresh()->demo_balance);
    }

    private function productData(array $overrides = []): array
    {
        return array_merge([
            'name' => 'RealityBox Gamer Pro',
            'description' => 'Caja gaming premium para tests.',
            'price' => 49.99,
            'image' => null,
            'category' => 'gaming',
            'rating' => 4.8,
            'reviews' => 10,
            'is_featured' => true,
            'is_popular' => true,
        ], $overrides);
    }
}
