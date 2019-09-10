<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * Class BlogPost
 * @package App\Models
 *
 * @property \App\Models\BlogCategory   $category
 * @property \App\Models\User           $user
 * @property string                     $title
 * @property string                     $slug
 * @property string                     $content_html
 * @property string                     $content_raw
 * @property string                     $excerpt
 * @property string                     $published_at
 * @property string                     $is_published
 * @property boolean
 */
class BlogPost extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'category_id',
        'excerpt',
        'content_raw',
        'is_published',
        'published_at',
        'user_id'
    ];

    /**
     * Categories post.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        // Post belongs to category
        return $this->belongsTo(BlogCategory::class);
    }

    /**
     * Author post.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        // Post belong user
        return $this->belongsTo(User::class);
    }
}
