<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
}
class Student extends Model
{
    use HasFactory;

    protected $fillable = ['nim', 'name', 'program_studi', 'angkatan'];
}
