<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanFile extends Model
{
    protected $table = 'plan_files'; // <-- Esto fuerza el nombre correcto

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