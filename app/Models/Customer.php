<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'firstname', 'lastname', 'email',
        'address'
    ];

    protected $appends = ['text'];

    public function getTextAttribute()
    {
        return $this->attributes['firstname']. ' - '.$this->attributes['lastname'];
    }
}
