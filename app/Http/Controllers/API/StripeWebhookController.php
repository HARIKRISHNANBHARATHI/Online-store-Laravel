<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\PaymentService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Event;
use Stripe\Webhook;

class StripeWebhookController extends Controller
{
    protected PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $webhookSecret = config('services.stripe.webhook_secret', env('STRIPE_WEBHOOK_SECRET'));

        $event = null;

        try {
            if ($webhookSecret && $sigHeader) {
                $event = Webhook::constructEvent($payload, $sigHeader, $webhookSecret);
            } else {
                $event = json_decode($payload, true);
            }
        } catch (Exception $e) {
            Log::error("Stripe Webhook Signature Verification Error: " . $e->getMessage());
            return response()->json(['error' => 'Webhook Signature Verification Failed'], 400);
        }

        $type = is_array($event) ? ($event['type'] ?? null) : $event->type;
        $dataObject = is_array($event) ? ($event['data']['object'] ?? []) : $event->data->object;

        Log::info("Stripe Webhook Event Received: {$type}");

        switch ($type) {
            case 'payment_intent.succeeded':
                $intentId = is_array($dataObject) ? ($dataObject['id'] ?? null) : $dataObject->id;
                $orderId = is_array($dataObject) ? ($dataObject['metadata']['order_id'] ?? null) : ($dataObject->metadata->order_id ?? null);

                if ($orderId) {
                    $order = Order::find($orderId);
                    if ($order) {
                        $this->paymentService->markOrderAsPaid($order, $intentId, is_array($dataObject) ? $dataObject : $dataObject->toArray());
                    }
                }
                break;

            case 'payment_intent.payment_failed':
                $intentId = is_array($dataObject) ? ($dataObject['id'] ?? null) : $dataObject->id;
                $orderId = is_array($dataObject) ? ($dataObject['metadata']['order_id'] ?? null) : ($dataObject->metadata->order_id ?? null);

                if ($orderId) {
                    $order = Order::find($orderId);
                    if ($order) {
                        $this->paymentService->handlePaymentFailed($order, $intentId, is_array($dataObject) ? $dataObject : $dataObject->toArray());
                    }
                }
                break;
        }

        return response()->json(['status' => 'success']);
    }
}
