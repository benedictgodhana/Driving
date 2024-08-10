<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationType extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Get the applications associated with the application type.
     */
    public function applications()
    {
        return $this->hasMany(Application::class);
    }


    public function activities()
    {
        return $this->hasMany(Activity::class);
    }


    public function institutions()
    {
        return $this->belongsToMany(Institution::class, 'application_type_institution');
    }
}
