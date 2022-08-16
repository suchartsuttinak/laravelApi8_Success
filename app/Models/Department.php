<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $table = 'departments';
    // protected $keyType = 'string'; //กรณี pk เป็น varchar
    // public $incrementing = false; // pk ไม่ได้เป็น uuto increment
    // public $timestamps = false; // ตารางไม่ไม่คอรั่ม create_at , update_at ในตาราง

    public function officers () {
        // return $this->hasMany(Officer::class);
        return $this->hasMany(Officer::class, 'department_id', 'id');
    }


}
