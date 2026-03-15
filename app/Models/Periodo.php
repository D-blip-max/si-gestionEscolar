<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Periodo extends Model
{
    //
    protected $table="periodos";
    protected $fillable = [
        'nombre',
        'gestion_id'];
    
    public function gestion(){  //1 Periodo tiene 1 gestion
        return $this->belongsTo(Gestion::class);
    }   

}
