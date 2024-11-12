<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPreference extends Model
{
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'source_ids',
        'category_ids',
        'author_ids',
    ];
    /**
     * Get the user that owns the preference.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function setSourceIdsAttribute($value)
    {
        $this->attributes['source_ids'] = json_encode($value);
    }

    public function setCategoryIdsAttribute($value)
    {
        $this->attributes['category_ids'] = json_encode($value);
    }

    public function setAuthorIdsAttribute($value)
    {
        $this->attributes['author_ids'] = json_encode($value);
    }

    public function getSourceIdsAttribute($value)
    {
        return json_decode($value, false);
    }

    public function getCategoryIdsAttribute($value)
    {
        return json_decode($value, false);
    }

    public function getAuthorIdsAttribute($value)
    {
        return json_decode($value, false);
    }

}
