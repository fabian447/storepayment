<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name', 'customer_email', 'customer_mobile', 'status'
    ];

    public function payments() 
    {
        return $this->hasMany(Payment::class, 'order_id', 'id');
    }

    public function getLastPayment() 
    {
        return $this->payments()->orderBy('id','DESC')->first();
    }
}
