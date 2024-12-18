<?php

namespace App\Http\Controllers;

use App\Mail\PaymentLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller
{
    public function sendPaymentLink(Request $request)
    {
        $request->validate([
            "nombre" => "required",
            "apellidos" => "required",
            "monto" => "required",
            "descripcion" => "required",
            "numero_cotizacion" => "required",
            "correo" => "required",
            "marca" => "required",
        ]);

        $data = $request->only(['nombre', 'apellidos', 'monto', 'descripcion', 'numero_cotizacion', 'correo', 'marca']);

        $url = env('APP_URL');
        $tail = http_build_query($data); // Convierte los datos en una query string
        $encTail = base64_encode($tail); // Codificar el tail en Base64
        $finalUrl = $url . '/payment' . '?' . $encTail;

        $details = array_merge($data, ['url' => $finalUrl]);

        Mail::to($data['correo'])->send(new PaymentLink($details));

        return back()->with('success', 'El enlace de pago ha sido enviado correctamente.')
            ->with('form_data', $details); // Añade los datos a la sesión
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
