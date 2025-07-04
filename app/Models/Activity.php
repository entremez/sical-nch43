<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    public function aplicationDocuments()
    {
        return $this->belongsToMany(AplicationDocument::class);
    }
}
