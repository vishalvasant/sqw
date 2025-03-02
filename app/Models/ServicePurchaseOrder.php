<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicePurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'service_purchase_request_id',
        'vendor_id',
        'order_date',
        'status'
    ];

    public function purchaseRequest()
    {
        return $this->belongsTo(ServicePurchaseRequest::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function items()
    {
        return $this->hasMany(ServicePurchaseOrderItem::class);
    }
}
