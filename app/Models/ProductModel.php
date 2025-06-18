<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductModel extends Model
{
    protected $table = 'product_models'; // opcional si no es inferible
    protected $primaryKey = 'id'; // importante si no usás 'id' como PK

    protected $fillable = [
        'name',
        'brand_id',
        'designation_id',
        'description',
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class, 'designation_id');
    }
}
