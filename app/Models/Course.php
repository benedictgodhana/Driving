<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    // The attributes that are mass assignable
    protected $fillable = [
        'course_code',
        'course_name',
        'user_id',
        'number_lessons',
        'amount',
    ];

    // Define the table associated with the model (optional if different from pluralized model name)
    protected $table = 'courses';

    // Define the relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
