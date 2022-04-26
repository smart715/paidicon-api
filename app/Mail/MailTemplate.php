<?php

namespace App\Mail;

use App\Models\EmailTemplate;
use App\Models\Order;
use App\Models\Package;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MailTemplate extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var EmailTemplate
     */
    private $emailTemplate;

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

    /**
     * @param User|null $user
     * @param Order|null $order
     * @param Package|null $package
     */
    public function __construct(
        EmailTemplate $emailTemplate,
        ?User $user = null,
        ?Order $order = null,
        ?Package $package = null,
        ?Transaction $transaction = null
    ) {
        $this->emailTemplate = $emailTemplate;

        $this->subject = $this->emailTemplate->subject;

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
    }


    public function build()
    {
        return $this->subject($this->parseContent($this->emailTemplate->subject))
            ->view('email.template', ['content' => $this->parseContent($this->getHtmlLayout())]);
    }

    public function getHtmlLayout(): string
    {
        return '<header>' . $this->emailTemplate->header . '</header>' . $this->emailTemplate->body . $this->emailTemplate->signature . '<footer>' . $this->emailTemplate->footer . '</footer>';
    }

    public function parseContent($template)
    {
        $properties =call_user_func('get_object_vars', $this);
        $parsed = preg_replace_callback('/{{(.*?)}}/', function ($matches) use($properties) {
            list($param, $index) = $matches;
            if(isset($properties[$index])) {
                return $properties[$index];
            }
            return '';
        }, $template);

        return $parsed;
    }

}


