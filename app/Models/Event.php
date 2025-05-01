<?php
// app/Models/Event.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $table = 'events';
    protected $primaryKey = 'evt_id';
    public $timestamps = true;

    const CREATED_AT = 'evt_created_at';
    const UPDATED_AT = 'evt_updated_at';

    protected $fillable = [
        'evt_name',
        'evt_description',
    ];

    public function projects()
    {
        return $this->hasMany(Project::class, 'event_evt_id', 'evt_id');
    }

    public function criteria()
    {
        return $this->hasMany(Criterion::class, 'event_evt_id', 'evt_id');
    }

    public function judges()
    {
        return $this->hasMany(Judge::class, 'event_evt_id', 'evt_id');
    }

    public function timer()
    {
        return $this->hasOne(\App\Models\EventTimer::class);
    }
}
