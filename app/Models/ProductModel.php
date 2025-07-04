<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductModel extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'brand_id', 'designation_id', 'descripcion'];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }

    public function certificates()
    {
        return $this->belongsToMany(Certificate::class);
    }
}
