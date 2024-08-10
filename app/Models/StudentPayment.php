<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id', 'payment_mode_id', 'payment_amount', 'payment_date', 'reference_number', 'user_id'
    ];


    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function paymentMode()
    {
        return $this->belongsTo(PaymentMode::class);
    }

    // In StudentPayment.php model
public function user()
{
    return $this->belongsTo(User::class);
}

}
