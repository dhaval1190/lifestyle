<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MediaDetailsVisits extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'media_details_visits';

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
        return $this->belongsTo(User::class);
    }
}