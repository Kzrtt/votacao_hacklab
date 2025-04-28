<?php
// app/Models/Project.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $table = 'projects';
    protected $primaryKey = 'prj_id';
    public $timestamps = true;

    const CREATED_AT = 'prj_created_at';
    const UPDATED_AT = 'prj_updated_at';

    protected $fillable = [
        'prj_name',
        'prj_stack',
        'prj_participants',
        'event_evt_id',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_evt_id', 'evt_id');
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class, 'project_prj_id', 'prj_id');
    }
}
