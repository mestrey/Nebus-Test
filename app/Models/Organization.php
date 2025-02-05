<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'building_id',
        'phone_numbers',
    ];

    /**
     * Get the building where this organization is located.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class);
    }

    /**
     * Get all activities associated with this organization.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function activities(): BelongsToMany
    {
        return $this->belongsToMany(Activity::class);
    }

    /**
     * Get all phone numbers as an array.
     *
     * @return array
     */
    public function getPhoneNumbersAttribute($value): array
    {
        return json_decode($value, true) ?? [];
    }

    /**
     * Set phone numbers as a JSON string.
     *
     * @param array $value
     * @return void
     */
    public function setPhoneNumbersAttribute(array $value)
    {
        $this->attributes['phone_numbers'] = json_encode($value);
    }
}
