<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'description', 'unit_price', 'item_code', 'quantity'
    ];

    protected $appends = ['text'];

    public function getTextAttribute()
    {
        return $this->attributes['item_code'].' - '.$this->attributes['description'];
    }
}
