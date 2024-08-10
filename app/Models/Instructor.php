<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Instructor extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'tel',
        'id_number',
        'user_id'
    ];

    protected $dates = ['deleted_at'];

    /**
     * Get the user that created the instructor.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
