<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    public $timestamps = false;
    protected $table = 'activity_log';
    protected $fillable = ['log_name', 'description', 'subject_type', 'subject_id', 'causer_type', 'causer_id', 'properties'];

    protected $casts = [
        'properties' => 'array',
        'created_at' => 'datetime',
    ];

    public static function record(string $description, string $logName = 'default', array $properties = []): void
    {
        static::create([
            'log_name'    => $logName,
            'description' => $description,
            'properties'  => $properties,
            'created_at'  => now(),
        ]);
    }
}
