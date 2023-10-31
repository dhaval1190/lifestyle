<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SearchHistory extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'search_history';
    protected $fillable = [
        'search_input', 'user_id'
    ];

    /**
     * Get the state that owns the city.
     */
    
}
