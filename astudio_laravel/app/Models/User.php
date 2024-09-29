<?php

namespace App\Models;

use App\Models\Project;
use App\Models\Timesheet;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'date_of_birth',
        'gender',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'date_of_birth' => 'date',
    ];

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_user');
    }

    public function timesheets()
    {
        return $this->hasMany(Timesheet::class);
    }
}