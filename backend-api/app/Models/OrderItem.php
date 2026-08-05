<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;
    
    protected $guarded = [];

    protected $casts = [
        'options' => 'array',
        'subtotal' => 'float'
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}
