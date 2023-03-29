<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProfileView extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'profile_views';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Get the item relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
    public function user()
    {
        return $this->belongsTo(user::class);
    }
}
