<?php
// app/Models/Criterion.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Criterion extends Model
{
    use HasFactory;

    protected $table = 'criteria';
    protected $primaryKey = 'crt_id';
    public $timestamps = true;

    const CREATED_AT = 'crt_created_at';
    const UPDATED_AT = 'crt_updated_at';

    protected $fillable = [
        'crt_name',
        'crt_weight',
        'event_evt_id',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_evt_id', 'evt_id');
    }

    public function scores()
    {
        return $this->hasMany(EvaluationScore::class, 'criteria_crt_id', 'crt_id');
    }
}
