<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemMedia extends Model
{
    use SoftDeletes;

    protected $table = 'item_media';

    protected $fillable = [
        'item_id',
        'media_type',
        'media_mime',
        'media_name',
        'media_path',
        'media_url',
        'sort_order',
        'status',
    ];

    /**
     * Get the item of this media.
    */
    public function item() {
        return $this->belongsTo('App\Item', 'item_id');
    }
}
