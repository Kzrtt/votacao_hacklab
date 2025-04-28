<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;

    protected $table = 'persons';
    protected $primaryKey = 'pes_id';

    public $timestamps = true;
    const CREATED_AT = 'pes_created_at';
    const UPDATED_AT = 'pes_updated_at';

    protected $fillable = [
        'pes_name',
        'pes_email',
        'pes_function',
    ];
}
