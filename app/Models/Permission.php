<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    /**
     * Write code on Method
     *
     * @return response()
    */
    protected $fillable = [
        'id', 'name', 'status'
    ];

    use HasFactory;
}
