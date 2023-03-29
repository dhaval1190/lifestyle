<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MediaDetail extends Model
{
    const MEDIA_TYPE = [
        'ebook'    => 'E Book',
        'podcast'  => 'Podcast',
        'video'    => 'Video'
    ];

    const EBOOK_MEDIA_TYPE = [
        'ebook'    => 'E Book'
    ];

    const PODCAST_MEDIA_TYPE = [
        'podcast'  => 'Podcast'
    ];

    const VIDEO_MEDIA_TYPE = [
        'video'    => 'Video'
    ];

    protected $table = 'media_details';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'media_type',
        'media_name',
        'media_description',
        'media_image',
        'media_cover',
        'media_url',
    ];
     /**
     * Get the visits relationship.
     *
     * @return HasMany
     */
    public function mediadetailsvisits()
    {
        return $this->hasMany(MediaDetailsVisits::class);
    }
}
