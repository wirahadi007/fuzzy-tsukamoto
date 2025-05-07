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
        'employee_count',
        'working_hours',
        'priority_scale',
        'priority_level',
        'processing_time',
        'assigned_to'
    ];

    protected $casts = [
        'deadline' => 'datetime',
        'employee_count' => 'integer',
        'working_hours' => 'integer',
        'priority_scale' => 'integer',
        'priority_level' => 'integer'
    ];

    public function getEmployeeMembershipAttribute()
    {
        $x = $this->employee_count;
        return [
            'sedikit' => $this->calculateMembership($x, 1, 2, 3),
            'sedang' => $this->calculateMembership($x, 2, 3, 4),
            'banyak' => $this->calculateMembership($x, 3, 4, 5)
        ];
    }

    public function getWorkingHoursMembershipAttribute()
    {
        $x = $this->working_hours;
        return [
            'rendah' => $this->calculateMembership($x, 5, 15, 25),
            'sedang' => $this->calculateMembership($x, 20, 30, 40),
            'tinggi' => $this->calculateMembership($x, 35, 45, 56)
        ];
    }

    public function getPriorityScaleMembershipAttribute()
    {
        $x = $this->priority_scale;
        return [
            'rendah' => $this->calculateMembership($x, 1, 1.5, 2),
            'sedang' => $this->calculateMembership($x, 1.5, 2.5, 3.5),
            'tinggi' => $this->calculateMembership($x, 3, 3.5, 4)
        ];
    }

    private function calculateMembership($x, $a, $b, $c)
    {
        if ($x <= $a || $x >= $c) return 0;
        if ($x == $b) return 1;
        if ($x < $b) return ($x - $a) / ($b - $a);
        return ($c - $x) / ($c - $b);
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}

