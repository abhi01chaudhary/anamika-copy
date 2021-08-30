<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseCounter extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'prefix',
        'value'
    ];
}
