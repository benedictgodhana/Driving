<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Institution extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'location_id',
        'description',
    ];

    /**
     * Get the location associated with the institution.
     */
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * Get the applications associated with the institution.
     */
    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function applicationTypes()
    {
        return $this->belongsToMany(ApplicationType::class, 'application_type_institution');
    }
}
