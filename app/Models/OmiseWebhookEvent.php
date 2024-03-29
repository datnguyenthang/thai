<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OmiseWebhookEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'orderCode', 'eventType', 'eventChargeid', 'eventStatus', 'eventData'];
}
