<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'products_id',
        'stores_id',
        'purchase_date',
        'lat',
        'lng',
        'price',
        'users_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'products_id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'stores_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}