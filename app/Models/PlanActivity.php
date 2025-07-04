<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanActivity extends Model
{
    protected $fillable = [
        'process_id',
        'activity_id',
    ];
    public function process()
    {
        return $this->belongsTo(Process::class);
    }

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function files()
    {
        return $this->hasMany(ActivityFile::class);
    }

}
