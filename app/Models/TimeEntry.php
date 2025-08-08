<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class TimeEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'project_id',
        'task_id',
        'description',
        'start_time',
        'end_time',
        'duration_minutes',
        'hourly_rate',
        'is_billable',
        'is_running',
        'date',
        'metadata',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'date' => 'date',
        'is_billable' => 'boolean',
        'is_running' => 'boolean',
        'hourly_rate' => 'decimal:2',
        'metadata' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function scopeRunning($query)
    {
        return $query->where('is_running', true);
    }

    public function scopeBillable($query)
    {
        return $query->where('is_billable', true);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForProject($query, $projectId)
    {
        return $query->where('project_id', $projectId);
    }

    public function scopeInDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    public function stop()
    {
        if (!$this->is_running) {
            return;
        }

        $endTime = now();
        $durationMinutes = $this->start_time->diffInMinutes($endTime);

        $this->update([
            'end_time' => $endTime,
            'duration_minutes' => $durationMinutes,
            'is_running' => false,
        ]);
    }

    public function getDurationHoursAttribute()
    {
        return round($this->duration_minutes / 60, 2);
    }

    public function getBillableAmountAttribute()
    {
        if (!$this->is_billable || !$this->hourly_rate) {
            return 0;
        }

        return round($this->duration_hours * $this->hourly_rate, 2);
    }

    public function getCurrentDurationAttribute()
    {
        if (!$this->is_running) {
            return $this->duration_minutes;
        }

        return $this->start_time->diffInMinutes(now());
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($timeEntry) {
            if (!$timeEntry->date) {
                $timeEntry->date = $timeEntry->start_time->toDateString();
            }
        });
    }
}
