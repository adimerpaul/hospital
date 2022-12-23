<?php

namespace App\Http\Controllers;

use App\Http\Controllers\PDF\PdfController;
use App\Models\Query;
use App\Http\Requests\StoreQueryRequest;
use App\Http\Requests\UpdateQueryRequest;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Luecano\NumeroALetras\NumeroALetras;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QueryController extends Controller{
    public function index(Request $request){return Query::where('hospital_id', $request->user()->hospital_id)->get();}
    public function store(Request $request){
        $request['date'] = date('Y-m-d');
        $request['time'] = date('H:i:s');
        return Query::create($request->all());
    }
    public function show($patient_id){
        return DB::table('queries')
            ->join('users', 'queries.user_id', '=', 'users.id')
            ->select('queries.*', 'users.name as doctor')
            ->where('queries.patient_id', $patient_id)
            ->orderBy('queries.date', 'desc')
            ->orderBy('queries.time', 'desc')
            ->get();
    }
    public function update(Request $request, Query $query){return $query->update($request->all());}
    public function destroy(Query $query){$query->delete();return $query;}
    public function reportHistory($id){
        $pdf = App::make('dompdf.wrapper');
        $pdfController=new PdfController();
        $html=$pdfController->history($id);
        $pdf->loadHTML($html);
        return $pdf->stream();
    }
    public function reportReception($id){
        $pdf = App::make('dompdf.wrapper');
        $pdfController=new PdfController();
        $html=$pdfController->reception($id);
        $pdf->loadHTML($html);
        return $pdf->stream();
    }
    public function reportSaley($patient_id){
        $query=Query::where('patient_id', $patient_id)->with(['user', 'patient','hospital'])->first();
        $pdf = App::make('dompdf.wrapper');
        $detalles= '
                <tr>
                    <td class="border">1</td>
                    <td class="border right">1</td>
                    <td class="border">Unidad</td>
                    <td class="border">Consulta</td>
                    <td class="border right">100</td>
                    <td class="border right">0</td>
                    <td class="border right">100</td>
                </tr>
            ';
        $url = env('URL_BACK')."reportHistory/".$patient_id;
        $qrcode = base64_encode(QrCode::format('svg')->size(200)->errorCorrection('H')->generate($url));
        $formatter = new NumeroALetras();
        $literal = $formatter->toInvoice((float)10, 2, 'Bolivianos');
        $html= ('
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
    </style>
</head>
<body>
<table width="100%"  class="collapse" >
    <tr>
        <td width="33%">
            <div class="bold center text-h2">' . strtoupper( $query->hospital->nameHospital) . '</div>
            <div class="bold center">' . $query->hospital->address . '</div>
            <div class="center">Telefono' . $query->hospital->phone . '</div>
        </td>
        <td></td>
        <td width="120px">
            <table>
                <tr>
                    <td valign=top width="130px">
                        <div class="bold">WEB/CORREO</div>
                        <div class="bold">HISTORIAL N°</div>
                        <div class="bold">CLIENTE</div>
                    </td>
                    <td>
                        <div>' . $query->hospital->web . '</div>
                        <div>' . $query->id . '</div>
                        <div style="width: 120px">' . $query->patient->id . '</div>
                        <br>
                        <br>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <!--tr>
        <td>
            <div class="center">
                ' .$query->hospital->address . '
            </div>
            <div class="center">Teléfono: ' . $query->hospital->address . '</div>
            <div class="center">Oruro</div>
        </td>
    </tr-->
    <tr>
        <td colspan="3">
            <div class="bold center text-h1">
                HISTORIAL CLINICO
            </div>
            <div class="center">
                ( CONSULTA EXTERNA - PEDIATRIA )
            </div>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <table width="100%">
                <tr>
                    <td>
                        <div class="bold">Fecha: </div>
                    </td>
                    <td>
                        <div>'.$query->date.'</div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="bold">Nombre/Razón Social:</div>
                    </td>
                    <td>
                        <div>' . $query->patient->name . '</div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="bold">Dirección:</div>
                    </td>
                    <td>
                        <div>' . $query->patient->address . '</div>
                    </td>
                </tr>
                <!--tr>
                    <td>
                        <div class="bold">Consumo Periodo:</div>
                    </td>
                    <td>
                        <div>' . '0' . '</div>
                    </td>
                </tr-->
            </table>
        </td>
        <td>
            <table width="100%">
                <tr>
                    <td class="right">
                        <div class="bold">NIT/CI/CEX:</div>
                    </td>
                    <td>
                        <div>' . $query->patient->ci.'</div>
                    </td>
                </tr>
                <tr>
                    <td class="right">
                        <div class="bold">Cod. Cliente:</div>
                    </td>
                    <td>
                        <div>' . $query->patient->id . '</div>
                    </td>
                </tr>
                <tr>
                    <td class="right">
                        <div class="bold">Periodo Facturado:</div>
                    </td>
                    <td>
                        <div>' . '0' . '</div>
                    </td>
                </tr>
                 <tr>
                    <td class="right">
                        <div class="bold">Nro Medidor:</div>
                    </td>
                    <td>
                        <div>' . '0' . '</div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="3">
            <table width="100%" class="collapse">
                <tr class="background" >
                    <th class="border" width="50px" >CÓDIGO
                        SERVICIO</th>
                    <th class="border">CANTIDAD</th>
                    <th class="border">UNIDAD DE
                        MEDIDA</th>
                    <th class="border">DESCRIPCIÓN</th>
                    <th class="border" width="60px">PRECIO
                        UNITARIO</th>
                    <th class="border">DESCUENTO</th>
                    <th class="border">SUBTOTAL</th>
                </tr>
                ' . $detalles . '
                <tr>
                    <td colspan="4"></td>
                    <td class="border right text-h5" colspan="2">TOTAL Bs</td>
                    <td class="border right">' . 0 . '</td>
                </tr>
                <!--tr>
                    <td colspan="4"></td>
                    <td class="border right text-h5" colspan="2">(-) DESCUENTO Bs</td>
                    <td class="border right">0</td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                    <td class="border right text-h5" colspan="2">SUBTOTAL A PAGAR Bs</td>
                    <td class="border right">' . '0' . '</td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                    <td class="border right text-h5" colspan="2">(-) AJUSTES NO SUJETOS A IVA Bs</td>
                    <td class="border right">0</td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                    <td class="border right text-h5 bold background" colspan="2">MONTO TOTAL A PAGAR Bs</td>
                    <td class="border right">' . '0'. '</td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                    <td class="border right text-h5" colspan="2">(-) TASAS Bs</td>
                    <td class="border right">0</td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                    <td class="border right text-h5" colspan="2">(-) OTROS PAGOS NO SUJETO IVA Bs</td>
                    <td class="border right">0</td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                    <td class="border right text-h5" colspan="2">(+) AJUSTES NO SUJETOS A IVA Bs</td>
                    <td class="border right">0</td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                    <td class="border right  text-h5 bold background" colspan="2">IMPORTE BASE CRÉDITO FISCAL</td>
                    <td class="border right">' . '0' . '</td>
                </tr-->
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="3" >
            <table width="100%">
                <tr>
                    <td class="center" valign=top>
                        <div class="left bold">Son: ' . $literal . '</div>
                        <div class="text-h5">ESTA FACTURA CONTRIBUYE AL DESARROLLO DEL PAÍS, EL USO ILÍCITO SERÁ SANCIONADO PENALMENTE DE ACUERDO A LEY
                            </div>
                        <div class="text-h5">“Este documento es la Representación Gráfica de un Documento Fiscal Digital emitido en una modalidad de facturación en línea”</div>
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

        $pdf->loadHTML($html);
        return $pdf->stream();
    }
}
