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
        Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
    }

    public function pay()
    {
        return view('stripe');
    }


    public function emailer()
    {
        return view('emails/radiology-final-report-mail');
    }

    // public function makePayment(Request $request){

    //     $customer = \Stripe\Customer::create([
    //         'email' => $_POST['stripeEmail'],
    //         'source' => $_POST['stripeToken'],
    //       ]);

    //       \Stripe\Stripe::setApiKey('sk_test_51MneWGSDcpngmiAPOjUTxI43MHLb7OOjYVncVeRI9yKy8sDw7T898E7HlDjO6czP2k8F85DFY88RT35J3RYapHpf00SxePX90m');

    //       $charge = \Stripe\Charge::create([
    //         'customer' => $customer->id,
    //         'description' => 'T-shirt',
    //         'amount' => 500,
    //         'currency' => 'inr',
    //       ]);

    //       dd($charge);
    // }


    public function stripePost(Request $request)
    {
        try {
            //dd($request->stripeToken);


            $amount = $request->amount;

            // $customer_obj = json_decode($request->customer);

            $customer = Stripe\Customer::create(['name' => $request->name, 'email' => $request->email]);

            Stripe\Customer::createSource($customer->id, ['source' => $request->stripeToken]);
            $charge = Stripe\Charge::create(["amount" => $amount * 100, "currency" => "CHF", "customer" => $customer->id, "description" => $request->package_name,

            ]);

            if ($charge->status == 'succeeded') {
                // $payment_subscription = Payment::create(['amount' => $amount * 100, 'user_email' =>  $customer_obj->email, 'transaction_id' => $charge->id, 'package_id' => $product_obj->id, 'status' => $charge->status]);
                $customerArr = ['amount' => $amount, 'user_email' => $request->email, 'transaction_id' => $request->email, 'status' => $request->email, 'package_id' => $request->package_id,];

                return response()->json(['status' => true, 'massage' => 'payment successfully.', 'data' => array('success' => true, 'resp' => $charge)], 200);
            } else {

                return response()->json(['status' => false, 'massage' => 'payment failed.'], 400);
            }
        } catch (Exception $e) {
            //echo $e->getMessage();;
            return response()->json(['status' => false, 'massage' => 'payment failed! ' . $e->getMessage()], 400);

        }
    }
    public function saveStripeOrder(Request $request)
    {
        try{
            $newReq = new Payment;
            $newReq->amount = $request->amount;
            $newReq->user_email = $request->user_email;
            $newReq->transaction_id = $request->transaction_id;
            $newReq->status = $request->status;
            $newReq->package_id = $request->package_id;
            $newReq->save();
            
        
            return response()->json([
                'status' => true,
                'massage' => 'Order saved successfully.'
                
            ], 200);
        } catch (Exception $e) {
            
            return response()->json([
                'status' => false,
                'massage' => 'Error! ' . $e->getMessage()
            ], 400);

        }
    }

    public function checkout()
    {


        $lineItems[] = ['price_data' => ['currency' => 'usd', 'product_data' => ['name' => "t-shirt",// 'images' => [$product->image]
        ], 'unit_amount' => 5 * 100,], 'quantity' => 1,];

        $session = Session::create(['line_items' => $lineItems, 'mode' => 'payment', 'success_url' => route('checkout.success', [], true) . "?session_id={CHECKOUT_SESSION_ID}", 'cancel_url' => route('checkout.cancel', [], true),]);

        // $order = new Order();
        // $order->status = 'unpaid';
        // $order->total_price = $totalPrice;
        // $order->session_id = $session->id;
        // $order->save();

        return redirect($session->url);
    }

    public function success(Request $request)
    {
        $sessionId = $request->get('session_id');

        try {

            $session = Session::retrieve($sessionId);
            //dd($session->customer_details->name);
            if (!$session) {
                throw new NotFoundHttpException;
            }
            //$customer = \Stripe\Customer::retrieve($session->customer_details);
            $customer = $session->customer_details;

            // $order = Order::where('session_id', $session->id)->first();
            // if (!$order) {
            //     throw new NotFoundHttpException();
            // }
            // if ($order->status === 'unpaid') {
            //     $order->status = 'paid';
            //     $order->save();
            // }

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
        // This is your Stripe CLI webhook secret for testing your endpoint locally.
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
