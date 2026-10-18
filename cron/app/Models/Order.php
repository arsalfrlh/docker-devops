<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
#[Table("orders")]
#[Fillable(['product_id','user_id','quantity','total_payment','expired_at','status'])]

class Order extends Model
{
    function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    function product(){
        return $this->belongsTo(Product::class,'product_id');
    }
}
