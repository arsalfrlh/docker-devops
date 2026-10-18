<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
#[Table("products")]
#[Fillable(['product_name','description','price','stock'])]

class Product extends Model
{
    function productMedia(){
        return $this->hasMany(ProductMedia::class,'product_id');
    }
}
