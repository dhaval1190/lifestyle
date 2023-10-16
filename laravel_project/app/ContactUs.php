<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactUs extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'contact_us';
    protected $fillable = [
        'email', 'user_type', 'message'
    ];

    /**
     * Get the state that owns the city.
     */
    
}
