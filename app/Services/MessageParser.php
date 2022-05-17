<?php

namespace App\Services;

use App\Models\ApiKey;
use App\Models\Order;
use App\Models\Package;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;

class MessageParser
{

    /** User properties */
    public $fullName;
    public $company;
    public $referralCode;
    public $localId;
    public $uuid;
    public $createdAt;
    public $updatedAt;

    /** Order properties */
    public $orderUuid;
    public $orderPaymentMethod;
    public $orderAmount;
    public $orderDiscount;
    public $orderTotalPayable;
    public $orderPackageStatus;
    public $orderStatus;
    public $orderCreatedAt;
    public $orderUpdatedAt;

    /** Package properties */
    public $packageUuid;
    public $packageName;
    public $packagePrice;
    public $packageFeatures;
    public $packageCreatedAt;
    public $packageUpdatedAt;

    /** Transaction properties */
    public $transactionUuid;
    public $transactionUserId;
    public $transactionType;
    public $transactionReferrerId;
    public $transactionUpdatedByUser;
    public $transactionCreatedAt;
    public $transactionUpdatedAt;

    /** @var ApiKey properties */
    public $apiKeyUuid;
    public $apiKeyPackageFeatures;
    public $expires;
    public $keyDaysLeft;


    /**
     * @param User|null $user
     * @param Order|null $order
     * @param Package|null $package
     */
    public function __construct(
        ?User $user = null,
        ?Order $order = null,
        ?Package $package = null,
        ?Transaction $transaction = null,
        ?ApiKey $apiKey = null
    ) {
        if ($user) {
            $this->fullName = $user->full_name;
            $this->company = $user->company;
            $this->referralCode = $user->referral_code;
            $this->localId = $user->local_id;
            $this->uuid = $user->uuid;
            $this->createdAt = $user->created_at;
            $this->updatedAt = $user->updatedAt;
        }

        if ($order) {
            $this->orderUuid = $order->uuid;
            $this->orderPaymentMethod = $order->payment_method;
            $this->orderAmount = $order->amount;
            $this->orderDiscount = $order->discount;
            $this->orderTotalPayable = $order->total_payable;
            $this->orderPackageStatus = $order->package_status;
            $this->orderStatus = $order->status;
            $this->orderCreatedAt = $order->createdAt;
            $this->orderUpdatedAt = $order->updatedAt;
        }

        if ($package) {
            $this->packageUuid = $package->uuid;
            $this->packageName = $package->name;
            $this->packagePrice = $package->price;
            $this->packageFeatures = $package->feature;
            $this->packageCreatedAt = $package->createdAt;
            $this->packageUpdatedAt = $package->updatedAt;
        }

        if ($transaction) {
            $this->transactionUuid = $transaction->uuid;
            $this->transactionUserId = $transaction->user_id;
            $this->transactionType = $transaction->type;
            $this->transactionReferrerId = $transaction->referrer_id;
            $this->transactionUpdatedByUser = $transaction->updated_by_user;
            $this->transactionCreatedAt = $transaction->created_at;
            $this->transactionUpdatedAt = $transaction->updated_at;
        }

        if($apiKey) {
            $this->apiKeyUuid = $apiKey->uuid;
            $this->apiKeyPackageFeatures = $apiKey->package_features;
            $this->expires = $apiKey->expires;
            $this->keyDaysLeft = Carbon::today()->diffInDays($apiKey->expires);
        }
    }

    public function parseContent($template)
    {
        $properties =call_user_func('get_object_vars', $this);
        $parsed = preg_replace_callback('/{{(.*?)}}/', function ($matches) use($properties) {
            list($_, $index) = $matches;
            if(isset($properties[$index])) {
                return $properties[$index];
            }
            return '';
        }, $template);

        return $parsed;
    }

}
