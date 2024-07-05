<?php

namespace App\Models;

use App\Models\User;
use App\Models\ProjectFile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory;
    use HasFactory;

    protected $fillable = [
        'project_name', 'project_description', 'deadline', 'created_by', 'updated_by'
    ];

    public function files()
    {
        return $this->hasMany(ProjectFile::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
