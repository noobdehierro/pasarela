<?php

namespace App\Http\Controllers;

use App\Mail\PaymentLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller
{
    public function sendPaymentLink(Request $request)
    {
        // dd($request->all());
        $request->validate([
            "nombre" => "required",
            "apellidos" => "required",
            "monto" => "required",
            "descripcion" => "required",
            "numero_cotizacion" => "required",
            "correo" => "required",
            "marca" => "required",
        ]);

        $nombre = $request->input('nombre');
        $apellidos = $request->input('apellidos');
        $monto = $request->input('monto');
        $descripcion = $request->input('descripcion');
        $numero_cotizacion = $request->input('numero_cotizacion');
        $correo = $request->input('correo');
        $marca = $request->input('marca');

        $url = env('APP_URL');

        $tail = 'nombre=' . $nombre . '&apellidos=' . $apellidos . '&monto=' . $monto . '&descripcion=' . $descripcion . '&numero_cotizacion=' . $numero_cotizacion . '&correo=' . $correo . '&marca=' . $marca;
        $encTail = base64_encode($tail); // Codificar el tail en Base64
        $finalUrl = $url . '/payment' . '?' . $encTail;

        $details = [
            'nombre' => $nombre,
            'apellidos' => $apellidos,
            'monto' => $monto,
            'descripcion' => $descripcion,
            'numero_cotizacion' => $numero_cotizacion,
            'correo' => $correo,
            'marca' => $marca,
            'url' => $finalUrl
        ];

        Mail::to($correo)->send(new PaymentLink($details));

        return back()->with('success', 'El enlace de pago ha sido enviado correctamente.');
    }

    public function payment()
    {
        $nombre = $this->getQueryVariable("nombre");
        $apellidos = $this->getQueryVariable("apellidos");
        $monto = $this->getQueryVariable("monto");
        $descripcion = $this->getQueryVariable("descripcion");
        $numero_cotizacion = $this->getQueryVariable("numero_cotizacion");
        $correo = $this->getQueryVariable("correo");
        $marca = $this->getQueryVariable("marca");

        if (empty($nombre) || empty($apellidos) || empty($monto) || empty($descripcion) || empty($numero_cotizacion) || empty($correo) || empty($marca)) {
            return redirect('/404');
        }

        $PAYPAL_MODE = env('PAYPAL_MODE');

        if ($PAYPAL_MODE === 'sandbox') {
            $PAYPAL_CLIENT_ID = env('PAYPAL_SANDBOX_CLIENT_ID');
        } else {
            $PAYPAL_CLIENT_ID = env('PAYPAL_LIVE_CLIENT_ID');
        }

        return view('payment', compact('nombre', 'apellidos', 'monto', 'descripcion', 'numero_cotizacion', 'correo', 'marca', 'PAYPAL_CLIENT_ID'));
    }

    function getQueryVariable($variable)
    {
        $query = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '';
        $dec = base64_decode($query);

        $vars = explode("&", $dec);

        foreach ($vars as $var) {
            $pair = explode("=", $var);
            if ($pair[0] === $variable) {
                return $pair[1];
            }
        }
        return false;
    }
}
