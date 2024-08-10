<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'application_type_id',
    ];

    /**
     * Get the application type associated with the activity.
     */
    public function applicationType()
    {
        return $this->belongsTo(ApplicationType::class);
    }

    /**
     * Get the applications associated with the activity.
     */
    public function applications()
    {
        return $this->hasMany(Application::class);

    }



    public function institutions()
    {
        return $this->belongsToMany(Institution::class, 'institution_activity');
    }
}
