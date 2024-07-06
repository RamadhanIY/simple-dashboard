<?php

namespace App\Models;

use App\Models\ProjectFile;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_email_verified'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function createdProjects()
    {
        return $this->hasMany(Project::class, 'created_by');
    }

    public function updatedProjects()
    {
        return $this->hasMany(Project::class, 'updated_by');
    }

    public function createdFiles()
    {
        return $this->hasMany(ProjectFile::class, 'created_by');
    }

    public function updatedFiles()
    {
        return $this->hasMany(ProjectFile::class, 'updated_by');
    }
}
