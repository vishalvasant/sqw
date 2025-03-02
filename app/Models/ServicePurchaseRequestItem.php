<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicePurchaseRequestItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_purchase_request_id',
        'service_id',
        'quantity',
        'description'
    ];

    public function purchaseRequest()
    {
        return $this->belongsTo(ServicePurchaseRequest::class);
    }

    public function service()
    {
        return $this->belongsTo(ProductService::class);
    }
}
