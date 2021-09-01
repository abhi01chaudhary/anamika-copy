<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'firstname', 'lastname', 'email', 'vendor_name',
        'address', 'phone'
    ];

    protected $appends = ['text'];

    public function getTextAttribute()
    {
        return $this->attributes['vendor_name'];
    }

}
