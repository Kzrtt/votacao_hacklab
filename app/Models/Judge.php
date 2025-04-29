<?php
// app/Models/Judge.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Judge extends Model
{
    use HasFactory;

    protected $table = 'judges';
    protected $primaryKey = 'jdg_id';
    public $timestamps = true;

    const CREATED_AT = 'jdg_created_at';
    const UPDATED_AT = 'jdg_updated_at';

    protected $fillable = [
        'jdg_name',
        'jdg_obs',
        'jdg_email',
        'jdg_password',
        'event_evt_id',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_evt_id', 'evt_id');
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class, 'judge_jdg_id', 'jdg_id');
    }
}
