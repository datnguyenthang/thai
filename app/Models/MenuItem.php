<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;
    /**
     * Write code on Method
     *
     * @return response()
     */
    protected $fillable = [
        'name', 'url', 'parent_id', 'status'
    ];

    public function subMenus(){
        return $this->hasMany(MenuItem::class, 'parent_id');
    }
}
