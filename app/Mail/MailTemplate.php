<?php

namespace App\Mail;

use App\Models\ApiKey;
use App\Models\EmailTemplate;
use App\Models\Order;
use App\Models\Package;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Services\MessageParser;

class MailTemplate extends Mailable
{
    use Queueable, SerializesModels;

    /** @var EmailTemplate */
    private $emailTemplate;

    /** @var MessageParser */
    private $messageParser;

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
        ?Transaction $transaction = null,
        ?ApiKey $apiKey = null
    ) {
        $this->emailTemplate = $emailTemplate;
        $this->messageParser = new MessageParser($user, $order, $package, $transaction, $apiKey);
    }


    public function getHtmlLayout(): string
    {
        return view('email.template_content',
                    [
                        'header' => $this->emailTemplate->header,
                        'body' => $this->emailTemplate->body . $this->emailTemplate->signature,
                        'footer' => $this->emailTemplate->footer
                    ]
        )->render();
    }

    public function build()
    {
        return $this->subject($this->messageParser->parseContent($this->emailTemplate->subject))
            ->view('email.template', ['content' => $this->messageParser->parseContent($this->getHtmlLayout())]);
    }


}


