<?php

namespace App\Http\Controllers\PDF;

use App\Models\Query;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use DateTime;
class PdfController
{
    function history($id){
        $query=Query::where('id', $id)->with(['user', 'patient','hospital'])->first();
        $queries=Query::where('patient_id', $query->patient_id)->get();
        $queryMedicinesText='';
        $queryMedicines=$query->queryMedicines;
        foreach ($queryMedicines as $queryMedicine){
            $queryMedicinesText.='<tr>
                    <td colspan="4">
                        <div class=" text-h4">'.$queryMedicine->medicine.'  '.$queryMedicine->amount.' '.$queryMedicine->note.' '.$queryMedicine->number.' '.$queryMedicine->unit.' '.$queryMedicine->time.' '.$queryMedicine->via.' '.$queryMedicine->diagnosis.'</div>
                    </td>
                </tr>';
        }
//        return $query;

        $url = env('URL_BACK')."reportHistory/".$id;
        $qrcode = base64_encode(QrCode::format('svg')->size(200)->errorCorrection('H')->generate($url));
        $content='<tr>
        <td colspan="3">
            <div class="bold center text-h1">
                DOCUMENTACIÓN CLÍNICA
            </div>
            <div class="center">
                ( CONSULTA EXTERNA - PEDIATRIA )
            </div>
        </td>
    </tr>
    <tr>
        <td colspan="3">
            <table width="100%">
                <tr>
                    <td colspan="4">
                        <div class="bold text-h5"> <i>DATOS DEL PACIENTE</i></div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="bold right">PACIENTE:</div>
                    </td>
                    <td>
                        <div>'.$query->patient->lastname.' '.$query->patient->name.'</div>
                    </td>
                    <td>
                        <div class="bold text-right">FECHA NACIMIENTO:</div>
                    </td>
                    <td>
                        <div>'.$this->formatDate($query->patient->birthday).'</div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="bold right">DIRECCION:</div>
                    </td>
                    <td>
                        <div>'.$query->patient->address.'</div>
                    </td>
                    <td>
                        <div class="bold right">SEXO:</div>
                    </td>
                    <td>
                        <div>'.$query->patient->sex.'</div>
                    </td>
                </tr>
                <tr>
                    <td colspan="4">
                        <div class="bold"><i>DATOS DE LA CONSULTA:</i></div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="bold right">No DE CONSULTA:</div>
                    </td>
                    <td>
                        <div>'.count($queries).'</div>
                    </td>
                    <td>
                        <div class="bold right">EDAD:</div>
                    </td>
                    <td>
                        <div>'.$this->formatAge($query->patient->birthday).'</div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="bold right">Fec/Hr  CONSULTA:</div>
                    </td>
                    <td>
                        <div>'.$this->formatDate($query->date).' '.$this->formatTime($query->time).'</div>
                    </td>
                    <td>
                        <div class="bold right">ESPECIALIDAD:</div>
                    </td>
                    <td>
                        <div>'.$query->user->specialty.'</div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="bold right">PESO KG:</div>
                    </td>
                    <td>
                        <div>'.$query->weight.'</div>
                    </td>
                    <td>
                        <div class="bold right">ALTURA MTS:</div>
                    </td>
                    <td>
                        <div>'.$query->height.'</div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="bold right">SATURACION O2:</div>
                    </td>
                    <td>
                        <div>'.$query->sat.'</div>
                    </td>
                    <td>
                        <div class="bold right">TEMPERATURA:</div>
                    </td>
                    <td>
                        <div>'.$query->tem.'</div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="bold right">IMC:</div>
                    </td>
                    <td>
                        <div>'.$this->formatIMC($query->weight, $query->height).'</div>
                    </td>
                    <td>
                        <div class="bold right">IMC CLASIFICACION:</div>
                    </td>
                    <td>
                        <div>'.$this->formatIMCText($this->formatIMC($query->weight, $query->height)).'</div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="bold right">TENSION ARTERIAL:</div>
                    </td>
                    <td>
                        <div>'.$query->ta.'</div>
                    </td>
                    <td>
                        <div class="bold right">FRERCUENCIA CARDIACA:</div>
                    </td>
                    <td>
                        <div>'.$query->fc.'</div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="bold right">FRERCUENCIA RESPIRATORIA:</div>
                    </td>
                    <td>
                        <div>'.$query->fr.'</div>
                    </td>
                    <td>
                        <div class="bold right"></div>
                    </td>
                    <td>
                        <div></div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="bold right">DOCTOR:</div>
                    </td>
                    <td colspan="3">
                        <div>'.$query->user->name.'</div>
                    </td>
                </tr>
                <tr>
                    <td colspan="4">
                        <div class="bold text-h5"> <i>PREGUNTAS GINECOLOGICAS</i></div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="bold right">¿ESTA EMBARAZADA?</div>
                    </td>
                    <td>
                        <div>'.$query->pregnant.'</div>
                    </td>
                    <td>
                        <div class="bold text-right">FECHA ULTIMA MENSTRUACION:</div>
                    </td>
                    <td>
                        <div>'.($query->pregnant=="SI"?$this->formatDate($query->FUM):"").'</div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="bold right">SEMANAS DE GESTACION:</div>
                    </td>
                    <td>
                        <div>'.($query->pregnant=="SI"?$this->calculateGestationWeeks($query->FUM):"").'</div>
                    </td>
                    <td>
                        <div class="bold text-right">FONDO UTERINO:</div>
                    </td>
                    <td>
                        <div>'.($query->pregnant=="SI"?$query->FU:"").'</div>
                    </td>
                </tr>
                 <tr>
                    <td colspan="4">
                        <div class="bold text-h5"> <i>PREGUNTAS GENERALES</i></div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="bold right">¿FUMA?</div>
                    </td>
                    <td>
                        <div>'.$query->smoker.'</div>
                    </td>
                    <td>
                        <div class="bold text-right">DESCRIPCION:</div>
                    </td>
                    <td>
                        <div>'.($query->smoker=="SI"?$query->smokerDescription:"").'</div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="bold right">¿TOMA ALCOHOL?</div>
                    </td>
                    <td>
                        <div>'.$query->alcohol.'</div>
                    </td>
                    <td>
                        <div class="bold text-right">DESCRIPCION:</div>
                    </td>
                    <td>
                        <div>'.($query->alcohol=="SI"?$query->alcoholicDescription:"").'</div>
                    </td>
                </tr>
                <tr>
                    <td colspan="4">
                        <div class="bold text-h5"> <i>NOTAS MEDICAS</i></div>
                    </td>
                </tr>
                <tr>
                    <td colspan="4">
                        <div>'.$query->note1.'</div>
                    </td>
                    <td colspan="4">
                        <div>'.$query->note2.'</div>
                    </td>
                    <td colspan="4">
                        <div>'.$query->note3.'</div>
                    </td>
                    <td colspan="4">
                        <div>'.$query->note4.'</div>
                    </td>
                </tr>
                <tr>
                    <td colspan="4">
                        <div class="bold text-h5"> <i>EXPLORACION FISICA</i></div>
                    </td>
                </tr>
                <tr>
                    <td colspan="4">
                        <div>'.$query->exploration.'</div>
                    </td>
                </tr>
                <tr>
                    <td colspan="4">
                        <div class="bold text-h5"> <i>RECETA MEDICA</i></div>
                    </td>
                </tr>
                '.$queryMedicinesText.'
                <tr>
                    <td colspan="4">
                        <div class="bold text-h5"> <i>OBSEVACIONES</i></div>
                    </td>
                </tr>
                <tr>
                    <td colspan="4">
                        <div>'.$query->observations.'</div>
                    </td>
                </tr>
                <tr>
                    <td colspan="4">
                        <div class="bold text-h5"> <i>INFORMACION LABORAL</i></div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="bold right">RESUMEN MEDICO:</div>
                    </td>
                    <td>
                        <div>'.$query->summary.'</div>
                    </td>
                    <td>
                        <div class="bold right">MEDIDA DE ACCION:</div>
                    </td>
                    <td>
                        <div>'.$query->action.'</div>
                    </td>
                </tr>
                                <tr>
                    <td>
                        <div class="bold right">RAYOS X - TOMOGRAFIA:</div>
                    </td>
                    <td>
                        <div>'.$query->tomografias.'</div>
                    </td>
                    <td>
                        <div class="bold right">ECOGRAFIA:</div>
                    </td>
                    <td>
                        <div>'.$query->ecografias.'</div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="bold right">LABORATORIOS:</div>
                    </td>
                    <td>
                        <div>'.$query->laboratorios.'</div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>';

        $html= $this->headerFooter($query,$queries,$content,$qrcode);
        return $html;
    }
    function reception($id){
        $query=Query::where('id', $id)->with(['user', 'patient','hospital'])->first();
        $queries=Query::where('patient_id', $query->patient_id)->get();
        $queryMedicinesText='';
        $queryMedicines=$query->queryMedicines;
        foreach ($queryMedicines as $queryMedicine){
            $queryMedicinesText.='<tr>
                    <td colspan="4">
                        <div class=" text-h4">'.$queryMedicine->medicine.'  '.$queryMedicine->amount.' '.$queryMedicine->note.' '.$queryMedicine->number.' '.$queryMedicine->unit.' '.$queryMedicine->time.' '.$queryMedicine->via.' '.$queryMedicine->diagnosis.'</div>
                    </td>
                </tr>';
        }
        $url = env('URL_BACK')."reportReception/".$id;
        $qrcode = base64_encode(QrCode::format('svg')->size(200)->errorCorrection('H')->generate($url));
        $content='<tr>
        <td colspan="3">
            <div class="bold center text-h1">
                RECETA DE MEDICA
            </div>
            <div class="center">
                ( CONSULTA EXTERNA - PEDIATRIA )
            </div>
        </td>
    </tr>
    <tr>
        <td colspan="3">
            <table width="100%">
                <tr>
                    <td colspan="4">
                        <div class="bold text-h5"> <i>DATOS DEL PACIENTE</i></div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="bold right">PACIENTE:</div>
                    </td>
                    <td>
                        <div>'.$query->patient->lastname.' '.$query->patient->name.'</div>
                    </td>
                    <td>
                        <div class="bold text-right">FECHA NACIMIENTO:</div>
                    </td>
                    <td>
                        <div>'.$this->formatDate($query->patient->birthday).'</div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="bold right">DIRECCION:</div>
                    </td>
                    <td>
                        <div>'.$query->patient->address.'</div>
                    </td>
                    <td>
                        <div class="bold right">SEXO:</div>
                    </td>
                    <td>
                        <div>'.$query->patient->sex.'</div>
                    </td>
                </tr>
                <tr>
                    <td colspan="4">
                        <div class="bold text-h5"> <i>RECETA MEDICA</i></div>
                    </td>
                </tr>
                '.$queryMedicinesText.'
                <tr>
                    <td colspan="4">
                        <div class="bold text-h5"> <i>OBSEVACIONES</i></div>
                    </td>
                </tr>
                <tr>
                    <td colspan="4">
                        <div>'.$query->observations.'</div>
                    </td>
                </tr>
                <tr>
                    <td colspan="4">
                        <div class="bold text-h5"> <i>INFORMACION LABORAL</i></div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="bold right">RESUMEN MEDICO:</div>
                    </td>
                    <td>
                        <div>'.$query->summary.'</div>
                    </td>
                    <td>
                        <div class="bold right">MEDIDA DE ACCION:</div>
                    </td>
                    <td>
                        <div>'.$query->action.'</div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>';

        $html= $this->headerFooter($query,$queries,$content,$qrcode);
        return $html;
    }
    public function calculateGestationWeeks($date)
    {
        if ($date == null || $date == '' || $date == '0000-00-00') {
            return 0;
        }
        $date = new \DateTime($date);
        $now = new \DateTime();
        $diff = $now->diff($date);
        $weeks = floor($diff->days / 7);
        if ($weeks < 50) {
            return $weeks;
        }
        return 0;
    }
    public function formatDate($date)
    {
        $date = explode('-', $date);
        return $date[2] . '/' . $date[1] . '/' . $date[0];
    }
    public function formatTime($time)
    {
        $time = explode(':', $time);
        return $time[0] . ':' . $time[1];
    }
    public function formatAge($date)
    {
        $date = explode('-', $date);
        $date = $date[2] . '/' . $date[1] . '/' . $date[0];
        $date = new DateTime($date);
        $now = new DateTime();
        $year = $interval = $now->diff($date);
        $month = $interval->format('%m');
        $day = $interval->format('%d');
        return $year->format('%y') . ' AÑOS ' . $month . ' MESES ' . $day . ' DÍAS';
    }
    public function formatIMC($weight, $height)
    {
        if ($height == 0 || $weight == 0) {
            return 0;
        }
        $imc = $weight / ($height * $height);
        return number_format($imc, 2);
    }
    public function formatIMCText($number)
    {
        if ($number == 0) {
            return 'SIN DATOS';
        }
        if ($number < 18.5) {
            return 'BAJO PESO';
        } elseif ($number >= 18.5 && $number <= 24.9) {
            return 'NORMAL';
        } elseif ($number >= 25 && $number <= 29.9) {
            return 'SOBREPESO';
        } elseif ($number >= 30 && $number <= 34.9) {
            return 'OBESIDAD GRADO I';
        } elseif ($number >= 35 && $number <= 39.9) {
            return 'OBESIDAD GRADO II';
        } elseif ($number >= 40) {
            return 'OBESIDAD GRADO III';
        }
    }
    public function headerFooter($query,$queries,$content,$qrcode){
        return ('
        <!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        *{
            font-size: 12px;
            padding: 0;
            margin: 0;
        }
        .p-1{
            padding: 1px;
        }
        .p-2{
            padding: 2px;
        }
        .p-3{
            padding: 3px;
        }
        .p-4{
            padding: 4px;
        }
        .p-5{
            padding: 5px;
        }
        .p-6{
            padding: 6px;
        }
        .p-7{
            padding: 7px;
        }
        .p-8{
            padding: 8px;
        }
        .p-9{
            padding: 9px;
        }
        .p-10{
            padding: 10px;
        }
        .px-1{
            padding-left: 1px;
            padding-right: 1px;
        }
        .px-2{
            padding-left: 2px;
            padding-right: 2px;
        }
        .px-3{
            padding-left: 3px;
            padding-right: 3px;
        }
        .px-4{
            padding-left: 4px;
            padding-right: 4px;
        }
        .px-5{
            padding-left: 5px;
            padding-right: 5px;
        }
        .px-6{
            padding-left: 6px;
            padding-right: 6px;
        }
        .px-7{
            padding-left: 7px;
            padding-right: 7px;
        }
        .px-8{
            padding-left: 8px;
            padding-right: 8px;
        }
        .px-9{
            padding-left: 9px;
            padding-right: 9px;
        }
        .px-10{
            padding-left: 10px;
            padding-right: 10px;
        }
        .px-11{
            padding-left: 11px;
            padding-right: 11px;
        }
        .px-12{
            padding-left: 12px;
            padding-right: 12px;
        }
        .px-13{
            padding-left: 13px;
            padding-right: 13px;
        }
        .px-14{
            padding-left: 14px;
            padding-right: 14px;
        }
        .px-15{
            padding-left: 15px;
            padding-right: 15px;
        }
        .px-16{
            padding-left: 16px;
            padding-right: 16px;
        }
        .px-17{
            padding-left: 17px;
            padding-right: 17px;
        }
        .px-18{
            padding-left: 18px;
            padding-right: 18px;
        }
        .px-19{
            padding-left: 19px;
            padding-right: 19px;
        }
        .px-20{
            padding-left: 20px;
            padding-right: 20px;
        }
        .py-1{
            padding-top: 1px;
            padding-bottom: 1px;
        }
        .py-2{
            padding-top: 2px;
            padding-bottom: 2px;
        }
        .py-3{
            padding-top: 3px;
            padding-bottom: 3px;
        }
        .py-4{
            padding-top: 4px;
            padding-bottom: 4px;
        }
        .py-5{
            padding-top: 5px;
            padding-bottom: 5px;
        }
        .py-6{
            padding-top: 6px;
            padding-bottom: 6px;
        }
        .py-7{
            padding-top: 7px;
            padding-bottom: 7px;
        }
        .py-8{
            padding-top: 8px;
            padding-bottom: 8px;
        }
        .py-9{
            padding-top: 9px;
            padding-bottom: 9px;
        }
        .py-10{
            padding-top: 10px;
            padding-bottom: 10px;
        }
        .bold{
            font-weight: bold;
        }
        .text-h1{
            font-size: 20px;
        }
        .text-h2{
            font-size: 16px;
        }
        .text-h5{
            font-size: 8px;
        }
        .text-h6{
            font-size: 7px;
        }
        .center{
            text-align: center;
        }
        .right{
            text-align: right;
        }
        .border{
            border: 1px solid black
        }
        .collapse{
            border-collapse: collapse;
        }
        .background{
            background: #edf2f7
        }
        .overflow-visible {
          white-space: initial;
        }
        .text-h1{
            font-size: 20px;
        }
        .text-h2{
            font-size: 16px;
        }
        .text-h3{
            font-size: 14px;
        }
        .text-h4{
            font-size: 12px;
        }
        .text-h5{
            font-size: 8px;
        }
        .text-h6{
            font-size: 7px;
        }
        .text-right{
            text-align: right;
        }
        .text-center{
            text-align: center;
        }
    </style>
</head>
<body class="px-20 py-10">
<table width="100%"  class="collapse" >
    <tr>
        <td width="33%">
            <div class="bold center">'.strtoupper($query->hospital->nameHospital).'</div>
            <div class="bold center">'.$query->hospital->address.'</div>
            <div class="bold center">'.$query->hospital->web.'</div>
            <div class="center">Tel '.$query->hospital->phone.'</div>
        </td>
        <td></td>
        <td width="120px">
            <table>
                <tr>
                    <td valign=top width="130px">
                        <div class="bold">HISTORIAL N°:</div>
                        <div class="bold">CLIENTE HISTORIA:</div>
                    </td>
                    <td>
                        <div>'.$query->id.'</div>
                        <div style="width: 120px">' . count($queries) . '</div>
                        <br>
                        <br>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
'.$content.'
    <tr>
        <td colspan="3" >
        <br><br><br><br><br><br><br>
            <table width="100%">
                <tr>
                    <td class="center" valign=top>
                        <div class="bold">Atendido por:</div>
                        <div class=""> '.$query->user->name.'</div>
                        <div class="text-h5"> '.$query->user->specialty.'</div>
                        <div class="text-h5"> '.$query->user->ci.'</div>
                    </td>
                    <td>
                        <img width="95px" src="data:image/png;base64,' . $qrcode . '">
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
');
    }
}
