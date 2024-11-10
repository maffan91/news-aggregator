<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Source extends Model
{
    use HasFactory;
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

    public function setApiKeyAttribute($value)
    {
        $this->attributes['api_key'] = encrypt($value);
    }

    public function setApiSecretAttribute($value)
    {
        $this->attributes['api_secret'] = encrypt($value);
    }

    // Decrypt when accessed
    public function getApiKeyAttribute($value)
    {
        return decrypt($value);
    }

    public function getApiSecretAttribute($value)
    {
        return decrypt($value);
    }


}
