<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ppff extends Model
{
    public function estudiantes()
    {
        return ($this->hasMany(Estudiante::class));
    }
}
