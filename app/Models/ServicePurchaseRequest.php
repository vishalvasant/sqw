<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicePurchaseRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_number',
        'vendor_id',
        'requested_by',
        'request_date',
        'description',
        'status'
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function items()
    {
        return $this->hasMany(ServicePurchaseRequestItem::class);
    }

    public function services(){
        return $this->belongsToMany(ProductService::class, 'service_purchase_request_items', 'service_purchase_request_id', 'service_id')->withPivot('quantity', 'description','price');
    }
}
