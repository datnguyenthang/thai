<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    use HasFactory;

    /**
     * Write code on Method
     *
     * @return response()
     */
    protected $fillable = [
        'name', 'agentType', 'code', 'type', 'manager', 'email', 'phone', 'line', 'location', 'paymentType', 'agentContractType', 'status'
    ];
}
