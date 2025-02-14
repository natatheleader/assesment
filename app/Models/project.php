<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class project extends Model
{
    //
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'status',
    ];

    public function attributeValues()
    {
        return $this->hasMany(AttributeValues::class, 'entity_id');
    }

    public function getAttribute($key)
    {
        // First check if it's a regular attribute
        $value = parent::getAttribute($key);
        
        if ($value !== null) {
            return $value;
        }

        // Then check if it's a dynamic attribute
        $attributeValue = $this->attributeValues()
            ->whereHas('attribute', function ($query) use ($key) {
                $query->where('name', $key);
            })
            ->first();

        return $attributeValue ? $attributeValue->value : null;
    }

    /**
     * The users that belong to the project.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Get the timesheet for the project.
     */
    public function timesheets(): HasMany
    {
        return $this->hasMany(Timesheet::class);
    }
}
