<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use http\Env\Response;
use Stripe\Checkout\Session;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Models\Payment;
use Stripe;
use UnexpectedValueException;

class StripeController extends Controller
{
    public function __construct()
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
    }

    public function pay()
    {
        return view('stripe');
    }


    public function emailer()
    {
        return view('emails/radiology-final-report-mail');
    }

    


    public function stripePost(Request $request)
    {
        try {
            $amount = $request->amount;
            $customer = Stripe\Customer::create(['name' => $request->name, 'email' => $request->email]);
            Stripe\Customer::createSource($customer->id, ['source' => $request->stripeToken]);
            $charge = Stripe\Charge::create([
                "amount" => $amount * 100,
                "currency" => "CHF",
                "customer" => $customer->id,
                "description" => $request->package_name,

            ]);
            if ($charge->status == 'succeeded') {
                $orderReq = new Payment;
                $orderReq->amount = $amount;
                $orderReq->user_email = $request->email;
                $orderReq->transaction_id = $charge->id;
                $orderReq->status = $charge->status;
                $orderReq->package_id = $request->package_id;
                $orderReq->save();
                $responseData = [
                    'status' => $charge->status,
                    'transaction_id' => $charge->id
                ];
                return response()->json(['status' => true, 'massage' => 'payment successfully.', 'data' => array('success' => true, 'resp' => $responseData)], 200);
            } else {
                return response()->json(['status' => false, 'massage' => 'payment failed.'], 400);
            }
        } catch (Exception $e) {
            return response()->json(['status' => false, 'massage' => 'payment failed! ' . $e->getMessage()], 400);
        }
    }

    public function checkout()
    {


        $lineItems[] = [
            'price_data' => [
                'currency' => 'usd',
                'product_data' => [
                    'name' => "t-shirt", // 'images' => [$product->image]
                ],
                'unit_amount' => 5 * 100,
            ],
            'quantity' => 1,
        ];

        $session = Session::create(['line_items' => $lineItems, 'mode' => 'payment', 'success_url' => route('checkout.success', [], true) . "?session_id={CHECKOUT_SESSION_ID}", 'cancel_url' => route('checkout.cancel', [], true),]);

        

        return redirect($session->url);
    }

    public function success(Request $request)
    {
        $sessionId = $request->get('session_id');

        try {

            $session = Session::retrieve($sessionId);
            
            if (!$session) {
                throw new NotFoundHttpException;
            }
            
            $customer = $session->customer_details;

            

            return view('product.checkout-success', compact('customer'));
        } catch (Exception $e) {
            throw new NotFoundHttpException();
        }

    }

    public function cancel()
    {

    }

    public function webhook()
    {
        
        $endpoint_secret = env('STRIPE_WEBHOOK_SECRET');

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try {
            $event = Webhook::constructEvent($payload, $sig_header, $endpoint_secret);
        } catch (UnexpectedValueException $e) {
            // Invalid payload
            return response('', 400);
        } catch (SignatureVerificationException $e) {
            // Invalid signature
            return response('', 400);
        }

        // Handle the event
        switch ($event->type) {
            case 'checkout.session.completed':
                $session = $event->data->object;

                $order = Order::where('session_id', $session->id)->first();
                if ($order && $order->status === 'unpaid') {
                    $order->status = 'paid';
                    $order->save();
                    // Send email to customer
                }

            // ... handle other event types
            default:
                echo 'Received unknown event type ' . $event->type;
        }

        return response('');
    }
}