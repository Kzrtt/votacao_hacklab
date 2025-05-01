<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventTimer extends Model
{
    use HasFactory;

    protected $table = 'event_timers';
    protected $primaryKey = 'etm_id';

    public $timestamps = true;
    const CREATED_AT = 'etm_created_at';
    const UPDATED_AT = 'etm_updated_at';

    protected $fillable = [
        'etm_started_at',
        'etm_duration',
        'event_evt_id',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, "event_evt_id", 'evt_id');
    }
}
