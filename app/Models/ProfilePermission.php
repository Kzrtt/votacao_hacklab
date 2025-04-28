<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfilePermission extends Model
{
    use HasFactory;

    protected $table = 'profile_permissions';
    protected $primaryKey = 'prp_id';
    public $timestamps = false;

    protected $fillable = [
        'prp_area',
        'prp_action',
        'profiles_prf_id'
    ];

    public function getUser() {
        return $this->belongsTo(Profile::class, 'profiles_prf_id', 'prf_id');
    }
}
