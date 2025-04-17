<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'name',
        'client_name',
        'division_id',
        'deadline',
        'difficulty_level',
        'priority_level',
        'processing_time',
        'assigned_to'
    ];

    protected $casts = [
        'deadline' => 'datetime'
    ];

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}

