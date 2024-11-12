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
}
