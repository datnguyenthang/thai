<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;

    /**
     * Write code on Method
     *
     * @return response()
     */
    protected $fillable = [
        'name', 'discount', 'fromDate', 'toDate', 'status'
    ];
}
