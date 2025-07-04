<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityFile extends Model
{
    protected $fillable = [
        'plan_activity_id',
        'path',
        'original_name',
        'document_name',
        'document_date',
    ];
    public function planActivity()
    {
        return $this->belongsTo(PlanActivity::class);
    }
}
