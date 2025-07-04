<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Process extends Model
{
    protected $fillable = [
        'name',
        'aplication_document_id',
    ];
    public function aplicationDocument()
    {
        return $this->belongsTo(AplicationDocument::class);
    }

    public function planActivities()
    {
        return $this->hasMany(PlanActivity::class);
    }

    public function certificate()
    {
        return $this->hasOne(Certificate::class);
    }
}
