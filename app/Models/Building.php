<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Building extends Model
{
    use HasFactory;

    protected $fillable = [
        'address',
        'latitude',
        'longitude',
    ];

    /**
     * Get all organizations located in this building.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function organizations(): HasMany
    {
        return $this->hasMany(Organization::class);
    }
}
