<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseRequestItem extends Model
{
    use HasFactory;

    protected $fillable = ['purchase_request_id', 'product_id', 'quantity','price'];

    public function purchaseRequest()
    {
        return $this->belongsTo(PurchaseRequest::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
    public function getRemainingQuantityAttribute()
    {
        return $this->quantity - $this->fulfilled_quantity;
    }
}

