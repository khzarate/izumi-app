<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $table = 'tbl_students';
    public $timestamps = true;

    protected $fillable = [
        'student_name',
        'contact_number',
        'email_address',
        'address',
        'created_at',
        'updated_at'
    ];
}
