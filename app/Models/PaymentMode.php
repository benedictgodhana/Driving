<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMode extends Model
{
    use HasFactory;
    protected $table = 'payment_modes';

    protected $fillable = [
        'mode_name',
        'user_id'
    ];

    public function studentPayments()
    {
        return $this->hasMany(StudentPayment::class);
    }

    
}
