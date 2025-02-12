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
     * Get the user that owns the TimeSheet
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Get the Project that owns the TimeSheet
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project()
    {
        return $this->belongsTo(project::class, 'project_id', 'id');
    }
}
