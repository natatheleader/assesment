<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class timesheet extends Model
{
    //
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'task_name',
        'date',
        'hour',
    ];

    /**
     * Get the user that owns the timesheet.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the project that owns the timesheet.
     */
    public function projects(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
