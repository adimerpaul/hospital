<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Query extends Model
{
    use HasFactory;
    protected $fillable=[
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
        'note',
        'exploration',
        'prescription1',
        'prescription2',
        'prescription3',
        'prescription4',
        'prescription5',
        'observations',
        'summary',
        'action',
        'date',
        'time',
        'user_id',
        'hospital_id',
        'patient_id',
    ];
    public function user(){return $this->belongsTo(User::class);}
    public function hospital(){return $this->belongsTo(Hospital::class);}
    public function patient(){return $this->belongsTo(Patient::class);}
}
