<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Auth;

class ActivityLog extends Model
{
    public $timestamps = false;
    protected $table = 'activity_log';
    protected $fillable = ['log_name', 'description', 'subject_type', 'subject_id', 'causer_type', 'causer_id', 'properties'];

    protected $casts = [
        'properties' => 'array',
        'created_at' => 'datetime',
    ];

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    public function causer(): MorphTo
    {
        return $this->morphTo();
    }

    public static function record(string $description, string $logName = 'default', array $properties = []): void
    {
        $causer = Auth::user();

        static::create([
            'log_name'    => $logName,
            'description' => $description,
            'causer_type' => $causer ? get_class($causer) : null,
            'causer_id'   => $causer?->id,
            'properties'  => $properties,
            'created_at'  => now(),
        ]);
    }
}
