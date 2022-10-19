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

    const MEDIA_TYPE_VIDEO = 'video';
    const MEDIA_TYPE_IMAGE = 'image';
    const MEDIA_TYPE_CSV = 'csv';
    const MEDIA_TYPE_XLS = 'xls';
    const MEDIA_TYPE_DOC = 'doc';
    const MEDIA_TYPE_PDF = 'pdf';

    /**
     * Get the item of this media.
    */
    public function item() {
        return $this->belongsTo('App\Item', 'item_id');
    }
}
