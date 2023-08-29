<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use http\Env\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
class StripeController extends Controller
{
    public function pay(){
        return view('stripe');
    }


  public function emailer(){
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
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));


        $charge = \Stripe\Charge::create ([
                "amount" => $request->amount * 100,
                "currency" => "EUR",
                "source" => $request->stripeToken,
                "description" => "Thanks For Payment" 
        ]);
		if($charge->status == 'succeeded'){
			  return response()->json([
						'status' => true,
						'massage' => 'payment successfully.',
						'data' => array('success' => true, 'resp' =>  $charge)
					], 200);
		 }else{
			 return response()->json([
						'status' => false,
						'massage' => 'payment successfully.',
						'data' => array('success' => false, 'resp' =>  $charge)
					], 400);
		 }
    }

    public function checkout()
    {
       
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        $lineItems[] = [
            'price_data' => [
                'currency' => 'usd',
                'product_data' => [
                    'name' => "t-shirt",
                    // 'images' => [$product->image]
                ],
                'unit_amount' => 5* 100,
            ],
            'quantity' => 1,
        ];
    
        $session = \Stripe\Checkout\Session::create([
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('checkout.success', [], true) . "?session_id={CHECKOUT_SESSION_ID}",
            'cancel_url' => route('checkout.cancel', [], true),
        ]);

        // $order = new Order();
        // $order->status = 'unpaid';
        // $order->total_price = $totalPrice;
        // $order->session_id = $session->id;
        // $order->save();

        return redirect($session->url);
    }

    public function success(Request $request)
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
        $sessionId = $request->get('session_id');

        try {
            
            $session = \Stripe\Checkout\Session::retrieve($sessionId);
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
        } catch (\Exception $e) {
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
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            return response('', 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
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
