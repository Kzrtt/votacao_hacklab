<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    protected $table = 'admins';
    protected $primaryKey = 'adm_id';
    public $timestamps = true;

    const CREATED_AT = 'adm_created_at';
    const UPDATED_AT = 'adm_updated_at';

    // Quais campos podem ser "preenchidos" via mass assignment
    protected $fillable = [
        'adm_fantasy',
    ];
}
