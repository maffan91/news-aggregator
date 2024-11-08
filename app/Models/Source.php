<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Source extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name'];

   /**
     * Get articles for the source.
     */
    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }


}
