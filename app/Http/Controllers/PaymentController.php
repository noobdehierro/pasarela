<?php

namespace App\Http\Controllers;

use App\Mail\PaymentCompleted;
use App\Mail\PaymentLink;
use App\Models\Payment;
use App\Models\User;
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
        $user = User::find(auth()->user()->id);

        $order = Payment::create([
            'user_id' => $user->id,
            'name' => $request->input('nombre'),
            'last_name' => $request->input('apellidos'),
            'amount' => $request->input('monto'),
            'description' => $request->input('descripcion'),
            'quotation_number' => $request->input('numero_cotizacion'),
            'email' => $request->input('correo'),
            'brand' => $request->input('marca'),
            'url' => '',
            'status' => 'pending',
            'user_name' => $user->name,
            'id_sucursal' => $user->id_sucursal
        ]);

        $orderId = $order->id;

        $data = $request->only(['nombre', 'apellidos', 'monto', 'descripcion', 'numero_cotizacion', 'correo', 'marca']);

        $data['order_id'] = $orderId;

        $url = env('APP_URL');
        $tail = http_build_query($data); // Convierte los datos en una query string
        $encTail = base64_encode($tail); // Codificar el tail en Base64
        $finalUrl = $url . '/payment' . '?' . $encTail;

        $order->update(['url' => $finalUrl]);

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
        $order_id = $this->getQueryVariable("order_id");

        if (empty($nombre) || empty($apellidos) || empty($monto) || empty($descripcion) || empty($correo) || empty($marca) || empty($order_id)) {
            return redirect('/404');
        }

        $PAYPAL_MODE = env('PAYPAL_MODE');

        if ($PAYPAL_MODE === 'sandbox') {
            $PAYPAL_CLIENT_ID = env('PAYPAL_SANDBOX_CLIENT_ID');
        } else {
            $PAYPAL_CLIENT_ID = env('PAYPAL_LIVE_CLIENT_ID');
        }

        return view('payments.payment', compact('nombre', 'apellidos', 'monto', 'descripcion', 'numero_cotizacion', 'correo', 'marca', 'order_id', 'PAYPAL_CLIENT_ID'));
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
        $payments = Payment::where('user_id', auth()->id())->orderBy('id', 'desc')->paginate(10);
        return view('payments.index', compact('payments'));
    }

    public function show(Payment $payment)
    {
        return view('payments.show', compact('payment'));
    }

    public function paymentWebhook(Request $request)
    {

        $order_id = $request->input('order_id');
        $status = $request->input('status');

        $data = Payment::where('id', $order_id)->update(['status' => $status]);

        $payment = Payment::find($order_id);

        try {
            Mail::to($payment['email'])->send(new PaymentCompleted($payment));
            return response()->json(['status' => 'success', 'data' => $request->all(), 'payment' => $payment]);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'error' => $th->getMessage(), 'payment' => $payment]);
        }
    }

    public function updateStatus(Request $request)
    {
        return $request->all();
    }
}
