<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateReferralTransactionRequest;
use App\Http\Requests\CreateOrderTransactionRequest;
use App\Models\AdminSetting;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Stripe\StripeClient;

class TransactionController extends Controller
{
    public function order(CreateOrderTransactionRequest $request)
    {
        $stripe = new StripeClient(config('services.stripe.secret'));

        $order = Order::find($request->get('order_id'));
        if ($request->get('amount') <= $order->amount) {
            if ($request->get('type') === 'ACH') {
                $customer = $stripe->customers->create();
                $paymentIntent = $stripe->paymentIntents->create(
                    [
                        'amount' => $request->get('amount') * 100,
                        'currency' => 'usd',
                        'setup_future_usage' => 'off_session',
                        'customer' => $customer->id,
                        'payment_method_types' => ['us_bank_account'],
                        'payment_method_options' => [
                            'us_bank_account' => [
                                'financial_connections' => [
                                    'permissions' => ['payment_method', 'balances'],
                                ],
                            ],
                        ],
                    ]
                );
                $transaction = Transaction::create(
                    [
                        'uuid' => (string)Str::orderedUuid(),
                        'stripe_id' => $paymentIntent->id,
                        'amount' => $request->get('amount'),
                        'type' => 3,
                        'user_id' => auth()->id(),
                        'order_id' => $order->id,
                        'status' => 1,
                        'bank_account' => 'bank_account',
                        'referrer_id' => $request->has('referral_code') ?
                            User::query()->find(
                                'referral_code',
                                $request->get('referral_code')
                            ) :
                            null
                    ]
                );

                return response()->json(['client_secret' => $paymentIntent->client_secret]);
            } else if ($request->get('type') === 'card') {
                    
                $customer = $stripe->customers->create();
                $paymentIntent = $stripe->paymentIntents->create(
                    [
                        'amount' => $request->get('amount') * 100,
                        'currency' => 'usd',
                        'setup_future_usage' => 'off_session',
                        'customer' => $customer->id,
                        'payment_method_types' => ['card'],
                        'payment_method_options' => [
                            'card' => [
                                "request_three_d_secure"=> "automatic"
                            ],
                        ],
                    ]
                );
                $transaction = Transaction::create(
                    [
                        'uuid' => (string)Str::orderedUuid(),
                        'stripe_id' => $paymentIntent->id,
                        'amount' => $request->get('amount'),
                        'type' => 3,
                        'user_id' => auth()->id(),
                        'order_id' => $order->id,
                        'status' => 1,
                        'referrer_id' => $request->has('referral_code') ?
                            User::query()->find(
                                'referral_code',
                                $request->get('referral_code')
                            ) :
                            null
                    ]
                );
                return response()->json(['client_secret' => $paymentIntent->client_secret]);
            }
            try {
                $token = $stripe->tokens->create([
                    'card' => [
                        'number' => $request->get('card_number'),
                        'exp_month' => $request->get('exp_month'),
                        'exp_year' => $request->get('exp_year'),
                        'cvc' => $request->get('cvc'),
                    ]
                ]);

                if (!isset($token['id'])) {
                    Log::warning('failed to create token');
                    return response()->json(['message' => 'Could not make a transaction token'], 500);
                }

                $charge = $stripe->charges->create([
                    'source' => $token['id'],
                    'currency' => 'USD',
                    'amount' => $request->get('amount') * 100,
                    'description' => 'Charge order #' . $order->uuid,
                ]);
            } catch (\Exception $exception) {
                Log::error("Stripe Charge Exception: " . $exception->getMessage());
                return response()->json(['message' => 'Could not make a charge'], 500);
            }
            if ($charge['status'] == 'succeeded') {
                $transaction = Transaction::create(
                    [
                        'uuid' => (string)Str::orderedUuid(),
                        'stripe_id' => $charge['id'],
                        'amount' => $request->get('amount'),
                        'type' => 3,
                        'user_id' => auth()->id(),
                        'order_id' => $order->id,
                        'status' => 3,
                        'referrer_id' => $request->has('referral_code') ?
                            User::query()->find(
                                'referral_code',
                                $request->get('referral_code')
                            ) :
                            null
                    ]
                );
                $user = auth()->user();
                Log::info(
                    'User #' . $user->uuid . ' ' . $user->full_name . ' Have created Transaction #' . $transaction->uuid
                );
                return response()->json(['message' => 'Transaction created successfully!']);
            }
        }
        return response()->json(['message' => 'Amount is higher than order sum']);
    }

    public function refund($id, CreateReferralTransactionRequest $request)
    {
        $stripe = new StripeClient(config('services.stripe.secret'));

        $transaction = Transaction::find($id);
        $amount = $request->get('amount', $transaction->amount);
        if ($amount <= $transaction->amount) {
            try {
                $refund = $stripe->refunds->create([
                    'charge' => $transaction->stripe_id,
                    'amount' => $amount * 100
                ]);
            } catch (\Exception $exception) {
                Log::error("Stripe Charge Exception: " . $exception->getMessage());
                return response()->json(['message' => 'Could not make a refund'], 500);
            }
            if ($refund['status'] == 'succeeded') {
                $refundEntity = Transaction::create(
                    [
                        'uuid' => (string)Str::orderedUuid(),
                        'stripe_id' => $refund['id'],
                        'amount' => $amount,
                        'type' => 2,
                        'user_id' => auth()->id(),
                        'order_id' => $transaction->order_id,
                        'status' => 3,
                        'referrer_id' => null
                    ]
                );
                $transaction->update(['refund_id' => $refundEntity->id]);
                $user = auth()->user();
                Log::info(
                    'User #' . $user->uuid . ' ' . $user->full_name . ' Have created Transaction #' . $transaction->uuid
                );

                return response()->json(['message' => 'Transaction created successfully!']);
            }
        }
        return response()->json(['message' => "Refund amount can't  be higher that transaction amount!"], 412);
    }

    public function requestReferralPayment(CreateReferralTransactionRequest $request)
    {
        $user = auth()->user();
        $amount = $request->get('amount', $user->referral_balance);
        $settings = AdminSetting::first();
        if (
            $settings && $amount >= $settings->minimimum_referral_payout_amount
            && $user->referral_balance >= $amount
        ) {
            $transaction = Transaction::create(
                [
                    'uuid' => (string)Str::orderedUuid(),
                    'stripe_id' => null,
                    'amount' => $amount,
                    'type' => 1,
                    'user_id' => $user->id,
                    'order_id' => null,
                    'status' => 1,
                    'referrer_id' => null,
                    'card' => $request->get('card')
                ]
            );
            $user->referral_balance = $user->referral_balance - $amount;
            $user->save();
            Log::info(
                'User #' . $user->uuid . ' ' . $user->full_name . ' Have created Transaction #' . $transaction->uuid
            );
            return response()->json($transaction);
        }

        return response()->json(['message' => 'Wrong amount'], 412);
    }

    public function editReferralPaymentRequest(int $id, Request $request)
    {
        $transaction = Transaction::find($id);
        $stripe = new StripeClient(config('services.stripe.secret'));
        if (!Gate::check('isSuperAdmin') || $transaction->status !== 1) {
            return response()->json('Forbidden!', 403);
        }
        $transactionUser = $transaction->user;

        $request->validate(['approve' => 'required|boolean']);
        if (!$request->get('approve')) {
            try {
                DB::transaction(function () use ($transaction, $transactionUser) {
                    $transaction->update(['status' => 2]);
                    $transactionUser->referral_balance = $transactionUser->referral_balance + $transaction->amount;
                    $transactionUser->save();
                });
                $user = auth()->user();
                Log::info(
                    'User #' . $user->uuid . ' ' . $user->full_name . ' Have updated Transaction #' . $transaction->uuid
                );
                return response()->json(['message' => 'Transaction request canceled successfully']);
            } catch (\Exception $exception) {
                Log::error($exception->getMessage());
                return response()->json('Something went wrong', 500);
            }
        }
        $transaction->update(['status' => 3]);
        return response()->json(['message' => 'Transaction request approved successfully']);
        /*$stripeAccount = $stripe
            ->accounts->create([
                                   'business_type' => 'individual',
                                   'type' => 'custom',
                                   'default_currency' => 'USD',
                                   'capabilities' => [
                                       'card_payments' => ['requested' => true],
                                       'transfers' => ['requested' => true],
                                   ],
                                   'tos_acceptance' => [
                                       'date' => time(),
                                       'ip' => '95.142.87.135',
                                   ]
                               ]);
        if ($transaction->card) {
            $token = $stripe->tokens->create([
                                                 'card' => [
                                                     'number' => 4242424242424242,
                                                     'exp_month' => 9,
                                                     'exp_year' => 2023
                                                 ]
                                             ]
            );

            $externalAccount = $stripe->accounts
                ->createExternalAccount($stripeAccount,
                                        [
                                            'external_account' => [
                                                'object' => 'bank_account',
                                                'country' => $transactionUser->country,
                                                'currency' => 'USD',
                                                'account_number' => $transaction->card
                                            ]
                                        ]
                );
        } else {
            $externalAccount = $stripe->accounts
                ->createExternalAccount($stripeAccount,
                                        [
                                            'external_account' => [
                                                'object' => 'bank_account',
                                                'country' => $transactionUser->country,
                                                'currency' => 'USD',
                                                'account_number' => $transaction->card
                                            ]
                                        ]
                );
        }


        $transfer = $stripe->payouts->create([
                                                 'amount' => $transaction->amount,
                                                 'currency' => 'USD',
                                                 'destination' => $externalAccount
                                             ]);
        if ($transfer['status'] == 'succeeded') {
            $transaction::update([
                                     'status' => 3,
                                     'stripe_id' => $transfer['id'],
                                     'destination' => '4242424242424242'
                                 ]);
        }*/
    }

    public function index()
    {
        $transactions = Transaction::all()->toArray();
        return array_reverse($transactions);
        $user = auth()->user();
        Log::info('User #' . $user->uuid . ' ' . $user->full_name . ' Entered Transaction index');
    }

    public function show($id)
    {
        $transaction = Transaction::find($id);
        $user = auth()->user();
        Log::info('User #' . $user->uuid . ' ' . $user->full_name . ' Have watched Transaction #' . $transaction->uuid);
        return response()->json($transaction);
    }

    public function destroy($id)
    {
        $transaction = Transaction::find($id);
        $transaction->delete();
        $user = auth()->user();
        Log::info('User #' . $user->uuid . ' ' . $user->full_name . ' Deleted Transaction #' . $transaction->uuid);
        return response()->json('Transaction deleted!');
    }

    public function updateACHPayment(Request $request)
    {
        $transaction = Transaction::where('stripe_id', $request->get('id'))
            ->where('bank_account', 'bank_account')->firstOrFail();
        $transaction->status = $request->status;
        $transaction->save();
        return response()->json(['message' => 'Status updated']);
    }
    public function updateCreditPayment(Request $request)
    {
        $transaction = Transaction::where('stripe_id', $request->get('id'))
            ->firstOrFail();
        $transaction->status = $request->status;
        $transaction->save();
        return response()->json(['message' => 'Status updated']);
    }
}
