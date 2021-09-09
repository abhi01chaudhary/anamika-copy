<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'firstname', 'lastname', 'email',
        'address', 'phone'
    ];

    protected $appends = ['text'];

    public function getTextAttribute()
    {
        return $this->attributes['firstname'].'  '.$this->attributes['lastname'];
    }

    public function invoice(){
        return $this->belongsTo(Invoice::class);
    }

}
