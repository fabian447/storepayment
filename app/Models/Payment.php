<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'request_id', 'session_id', 'status', 'process_url', 'nonce', 'seed'
    ];

    public function order()
    {
        return $this->hasOne(Order::class, 'id', 'order_id');
    }

    public function setStatus($status)
    {
        switch ($status) {
            case 'APPROVED':
                $this->status = config('payments.statuses.approved');
                break;
            case "REJECTED":
                $this->status = config('payments.statuses.rejected');
                break;
            case "EXPIRED":
                $this->status = config('payments.statuses.expired');
                break;
            default:
                $this->status = config('payments.statuses.pending');;
        }
    }
}
