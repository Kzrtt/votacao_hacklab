<?php
// app/Models/Evaluation.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    use HasFactory;

    protected $table = 'evaluations';
    protected $primaryKey = 'eva_id';
    public $timestamps = true;

    const CREATED_AT = 'eva_created_at';
    const UPDATED_AT = 'eva_updated_at';

    protected $fillable = [
        'project_prj_id',
        'judge_jdg_id',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_prj_id', 'prj_id');
    }

    public function judge()
    {
        return $this->belongsTo(Judge::class, 'judge_jdg_id', 'jdg_id');
    }

    public function scores()
    {
        return $this->hasMany(EvaluationScore::class, 'evaluation_eva_id', 'eva_id');
    }
}
