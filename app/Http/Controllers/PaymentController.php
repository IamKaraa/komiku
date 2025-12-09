<?php

namespace App\Http\Controllers;

use App\Models\Comic;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Configure Midtrans
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    /**
     * Show checkout page for comic purchase
     */
    public function checkout($comicId)
    {
        $comic = Comic::findOrFail($comicId);

        // Check if comic is paid and user hasn't purchased it
        if (!$comic->isPaid()) {
            return redirect()->route('comic.detail', $comic->id)
                ->with('error', 'This comic is free and doesn\'t require payment.');
        }

        if (Auth::user()->hasPurchased($comic)) {
            return redirect()->route('comic.detail', $comic->id)
                ->with('info', 'You have already purchased this comic.');
        }

        // For demo purposes, set a fixed price. In real app, this should be configurable
        $price = 25000; // 25,000 IDR

        return view('user.payment.checkout', compact('comic', 'price'));
    }

    /**
     * Create payment transaction
     */
    public function createPayment(Request $request, $comicId)
    {
        $request->validate([
            'payment_method' => 'required|string|in:gopay,qris,bank_transfer',
        ]);

        $comic = Comic::findOrFail($comicId);
        $user = Auth::user();

        // Check if user already purchased
        if ($user->hasPurchased($comic)) {
            return response()->json(['error' => 'Already purchased'], 400);
        }

        // For demo, fixed price
        $amount = 25000;

        // Create unique order ID
        $orderId = 'ORDER-' . time() . '-' . $user->id . '-' . $comic->id;

        // Create purchase record
        $purchase = Purchase::create([
            'user_id' => $user->id,
            'comic_id' => $comic->id,
            'order_id' => $orderId,
            'amount' => $amount,
            'currency' => 'IDR',
            'status' => 'pending',
        ]);

        // Prepare Midtrans transaction data
        $transactionDetails = [
            'order_id' => $orderId,
            'gross_amount' => $amount,
        ];

        $customerDetails = [
            'first_name' => $user->name,
            'email' => $user->email,
        ];

        $itemDetails = [
            [
                'id' => 'COMIC-' . $comic->id,
                'price' => $amount,
                'quantity' => 1,
                'name' => $comic->title,
            ]
        ];

        // Set payment method based on request
        $enabledPayments = [];
        switch ($request->payment_method) {
            case 'gopay':
                $enabledPayments = ['gopay'];
                break;
            case 'qris':
                $enabledPayments = ['qris'];
                break;
            case 'bank_transfer':
                $enabledPayments = ['bank_transfer'];
                break;
        }

        $transactionData = [
            'transaction_details' => $transactionDetails,
            'customer_details' => $customerDetails,
            'item_details' => $itemDetails,
            'enabled_payments' => $enabledPayments,
        ];

        try {
            $snapToken = Snap::getSnapToken($transactionData);

            return response()->json([
                'snap_token' => $snapToken,
                'order_id' => $orderId,
            ]);
        } catch (\Exception $e) {
            Log::error('Midtrans payment creation failed: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to create payment'], 500);
        }
    }

    /**
     * Handle payment notification from Midtrans
     */
    public function notification(Request $request)
    {
        $notification = $request->all();

        // Verify notification signature
        $orderId = $notification['order_id'];
        $statusCode = $notification['status_code'];
        $grossAmount = $notification['gross_amount'];
        $signatureKey = $notification['signature_key'];

        // Find purchase by order_id
        $purchase = Purchase::where('order_id', $orderId)->first();

        if (!$purchase) {
            Log::error('Purchase not found for order_id: ' . $orderId);
            return response()->json(['status' => 'error'], 404);
        }

        // Update purchase status based on notification
        switch ($statusCode) {
            case '200':
                $purchase->update([
                    'status' => 'success',
                    'transaction_id' => $notification['transaction_id'] ?? null,
                    'payment_type' => $notification['payment_type'] ?? null,
                    'payment_details' => json_encode($notification),
                    'paid_at' => now(),
                ]);
                Log::info('Payment successful for order: ' . $orderId);
                break;

            case '201':
                $purchase->update([
                    'status' => 'pending',
                    'payment_details' => json_encode($notification),
                ]);
                break;

            case '202':
                $purchase->update([
                    'status' => 'failed',
                    'payment_details' => json_encode($notification),
                ]);
                Log::warning('Payment failed for order: ' . $orderId);
                break;

            default:
                Log::warning('Unknown payment status for order: ' . $orderId . ' - Status: ' . $statusCode);
                break;
        }

        return response()->json(['status' => 'ok']);
    }

    /**
     * Check payment status
     */
    public function checkStatus($orderId)
    {
        try {
            $status = \Midtrans\Transaction::status($orderId);

            $purchase = Purchase::where('order_id', $orderId)->first();

            if ($purchase) {
                // Update status based on current transaction status
                $transactionStatus = $status->transaction_status;

                switch ($transactionStatus) {
                    case 'settlement':
                        $purchase->update([
                            'status' => 'success',
                            'paid_at' => now(),
                            'payment_details' => json_encode($status),
                        ]);
                        break;
                    case 'pending':
                        $purchase->update(['status' => 'pending']);
                        break;
                    case 'deny':
                    case 'cancel':
                    case 'expire':
                    case 'failure':
                        $purchase->update(['status' => 'failed']);
                        break;
                }
            }

            return response()->json($status);
        } catch (\Exception $e) {
            Log::error('Failed to check payment status: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to check status'], 500);
        }
    }

    /**
     * Payment success page
     */
    public function success(Request $request)
    {
        $orderId = $request->get('order_id');
        $purchase = Purchase::where('order_id', $orderId)->first();

        if (!$purchase || $purchase->user_id !== Auth::id()) {
            return redirect()->route('dashboard')->with('error', 'Invalid payment reference.');
        }

        return view('user.payment.success', compact('purchase'));
    }

    /**
     * Payment failed page
     */
    public function failed(Request $request)
    {
        $orderId = $request->get('order_id');
        $purchase = Purchase::where('order_id', $orderId)->first();

        return view('user.payment.failed', compact('purchase'));
    }

    /**
     * Payment unfinished page
     */
    public function unfinished(Request $request)
    {
        return view('user.payment.unfinished');
    }
}
