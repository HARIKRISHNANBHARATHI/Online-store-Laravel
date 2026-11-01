@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto space-y-8">
    <div class="border-b border-slate-200 pb-4">
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Payment Confirmation</h1>
        <p class="text-xs text-slate-500">Step 2 of 2: Secure Payment Processing (Order #{{ $order->order_number }})</p>
    </div>

    <!-- Order Total Banner -->
    <div class="bg-indigo-900 text-white p-6 rounded-2xl shadow-lg flex items-center justify-between">
        <div>
            <span class="text-xs text-indigo-300 font-medium">Order Number: #{{ $order->order_number }}</span>
            <h2 class="text-2xl font-black mt-1">Total Due: ${{ number_format($order->total, 2) }}</h2>
        </div>
        <div class="text-right text-xs text-indigo-200">
            <p>{{ $order->items->count() }} Items</p>
            <p class="text-[10px] bg-emerald-500 text-white font-bold px-2 py-0.5 rounded mt-1">Stripe Test Mode</p>
        </div>
    </div>

    <!-- Payment Container -->
    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-sm space-y-6">
        <div class="border-b border-slate-100 pb-4">
            <h3 class="font-bold text-slate-900 text-sm flex items-center">
                <i class="fa-solid fa-credit-card text-indigo-600 mr-2"></i> Pay with Credit Card / Stripe
            </h3>
            <p class="text-xs text-slate-500 mt-1">Transactions are securely processed with 256-bit encryption.</p>
        </div>

        <!-- Simulated Stripe Card Form -->
        <form action="{{ route('checkout.confirm', $order->id) }}" method="POST" id="payment-form" class="space-y-4">
            @csrf
            <input type="hidden" name="payment_intent" value="{{ $clientSecret ?? 'pi_mock_' . uniqid() }}">

            <div class="space-y-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Cardholder Name</label>
                    <input type="text" value="{{ auth()->user()->name }}" required class="w-full text-xs rounded-lg border-slate-300">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Card Number (Stripe Test)</label>
                    <div class="relative">
                        <input type="text" value="4242 •••• •••• 4242" readonly class="w-full text-xs font-mono rounded-lg border-slate-300 bg-slate-50 text-slate-600 pl-10">
                        <i class="fa-brands fa-cc-visa absolute left-3 top-2.5 text-indigo-600 text-base"></i>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Expiry Date</label>
                        <input type="text" value="12 / 28" readonly class="w-full text-xs font-mono rounded-lg border-slate-300 bg-slate-50 text-slate-600 text-center">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">CVC / CVV</label>
                        <input type="text" value="123" readonly class="w-full text-xs font-mono rounded-lg border-slate-300 bg-slate-50 text-slate-600 text-center">
                    </div>
                </div>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full bg-emerald-600 text-white font-extrabold text-sm py-4 rounded-xl hover:bg-emerald-700 transition shadow-lg flex items-center justify-center space-x-2">
                    <i class="fa-solid fa-lock"></i>
                    <span>Confirm & Pay ${{ number_format($order->total, 2) }}</span>
                </button>
            </div>
        </form>

        <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 text-xs text-slate-500 flex items-start space-x-3">
            <i class="fa-solid fa-circle-info text-indigo-600 text-sm mt-0.5"></i>
            <div>
                <strong class="text-slate-700">Interview Demonstration Note:</strong>
                <p class="mt-0.5">Clicking "Confirm & Pay" triggers <code class="bg-slate-200 px-1 py-0.5 rounded text-[11px] font-mono">PaymentService::markOrderAsPaid()</code> inside a database transaction, reserves stock, updates payment status, and dispatches the <code class="bg-slate-200 px-1 py-0.5 rounded text-[11px] font-mono">OrderPlaced</code> event.</p>
            </div>
        </div>
    </div>
</div>
@endsection
