<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttributeValues extends Model
{
    //
    protected $fillable = ['attribute_id', 'entity_id', 'value'];

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'entity_id');
    }
}
