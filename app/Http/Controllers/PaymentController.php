<?php

namespace App\Http\Controllers;

use App\Mail\PaymentLink;
use App\Models\Payment;
use Illuminate\Http\JsonResponse;
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
            "numero_cotizacion" => "nullable",
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

        $userId = auth()->user()->id;

        try {
            Payment::create([
                'user_id' => $userId,
                'name' => $data['nombre'],
                'last_name' => $data['apellidos'],
                'amount' => $data['monto'],
                'description' => $data['descripcion'],
                'quotation_number' => $data['numero_cotizacion'],
                'email' => $data['correo'],
                'brand' => $data['marca'],
                'url' => $finalUrl,
                'status' => 'pending'
            ]);
            return back()->with('success', 'El enlace de pago ha sido enviado correctamente.')
                ->with('form_data', $details); // Añade los datos a la sesión
        } catch (\Throwable $th) {
            dd($th);
            return back()->with('error', 'Ocurrio un error al enviar el enlace de pago.')
                ->with('form_data', $details); // Añade los datos a la sesión
        }
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

        if (empty($nombre) || empty($apellidos) || empty($monto) || empty($descripcion) || empty($correo) || empty($marca)) {
            return redirect('/404');
        }

        $PAYPAL_MODE = env('PAYPAL_MODE');

        if ($PAYPAL_MODE === 'sandbox') {
            $PAYPAL_CLIENT_ID = env('PAYPAL_SANDBOX_CLIENT_ID');
        } else {
            $PAYPAL_CLIENT_ID = env('PAYPAL_LIVE_CLIENT_ID');
        }

        return view('payments.payment', compact('nombre', 'apellidos', 'monto', 'descripcion', 'numero_cotizacion', 'correo', 'marca', 'PAYPAL_CLIENT_ID'));
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

    public function payments()
    {
        $payments = Payment::where('user_id', auth()->id())->paginate(10);
        return view('payments.index', compact('payments'));
    }

    public function show(Payment $payment)
    {
        return view('payments.show', compact('payment'));
    }

    public function paymentWebhook()
    {
        return 'hola';
    }
}
