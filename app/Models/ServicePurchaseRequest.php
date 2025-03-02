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
}
