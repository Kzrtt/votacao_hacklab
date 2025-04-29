<?php
// app/Models/EvaluationScore.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationScore extends Model
{
    use HasFactory;

    protected $table = 'evaluation_scores';
    protected $primaryKey = 'evs_id';
    public $timestamps = false;

    protected $fillable = [
        'evaluation_eva_id',
        'criteria_crt_id',
        'evs_score',
    ];

    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class, 'evaluation_eva_id', 'eva_id');
    }

    public function criterion()
    {
        return $this->belongsTo(Criterion::class, 'criteria_crt_id', 'crt_id');
    }
}
