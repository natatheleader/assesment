<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    /**
     * Get the user that owns the Project
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Get the timesheet associated with the Project
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function timeSheet()
    {
        return $this->hasOne(timeSheet::class);
    }
}
