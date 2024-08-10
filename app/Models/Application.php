<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'application_type_id',
        'institution_id',
        'activity_id',
        'preferred_start_date',
        'description',
        'cover_letter',
        'status',
        'location_id'
    ];

    /**
     * Get the user associated with the application.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the application type associated with the application.
     */
    public function applicationType()
    {
        return $this->belongsTo(ApplicationType::class);
    }

    /**
     * Get the institution associated with the application.
     */
    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    /**
     * Get the activity associated with the application.
     */
    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class); // Adjust based on your setup
    }
}
