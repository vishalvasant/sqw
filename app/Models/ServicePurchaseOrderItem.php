<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicePurchaseOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_purchase_order_id',
        'service_id',
        'quantity',
        'unit_price',
        'total_price'
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(ServicePurchaseOrder::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
