<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
#[Table("product_media")]
#[Fillable(['product_id','type','media_path','duration'])]

class ProductMedia extends Model
{
    //
}
