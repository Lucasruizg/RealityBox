<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BoxOpening extends Model
{
    protected $fillable = [
        'user_id',
        'session_id',
        'product_id',
        'product_name',
        'reward_name',
        'rarity',
        'reward_value',
        'sell_price',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'reward_value' => 'decimal:2',
            'sell_price' => 'decimal:2',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getRarityLabelAttribute(): string
    {
        return match ($this->rarity) {
            'comun' => 'Común',
            'raro' => 'Raro',
            'epico' => 'Épico',
            'legendario' => 'Legendario',
            default => 'Común',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'kept' => 'Guardado',
            'redeemed' => 'Canjeado',
            'sold' => 'Vendido',
            default => 'Pendiente',
        };
    }
}
