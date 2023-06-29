<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Query extends Model
{
    use HasFactory;
    protected $fillable=[
        'patient_id',
        'weight',
        'height',
        'sat',
        'tem',
        'ta',
        'fc',
        'fr',
        'blood',
        'pregnant',
        'FUM',
        'SDG',
        'FU',
        'smoker',
        'smokerDescription',
        'alcohol',
        'alcoholDescription',
        'note1',
        'note2',
        'note3',
        'note4',
        'exploration',
        'observations',
        'summary',
        'action',

        'date',
        'time',

        'user_id',
        'hospital_id',
        'tomografias',
        'ecografias',
        'laboratorios',
        'motivo',
        'enfermedad'
    ];
    public function user(){return $this->belongsTo(User::class);}
    public function hospital(){return $this->belongsTo(Hospital::class);}
    public function patient(){return $this->belongsTo(Patient::class);}
    public function queryMedicines(){
        return $this->hasMany(QueryMedicine::class);
    }
}
