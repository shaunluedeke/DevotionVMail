<?php

namespace App\Console\Commands;

use App\Models\EmailMessange;
use Illuminate\Console\Command;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;

class CheckMailOutgoingCommand extends Command
{

    protected $signature = 'check:mail-outgoing';

    protected $description = 'Check and Send Outgoing Mail';

    public function handle(): void
    {
        $this->info('Check and Send Outgoing Mail');
        $outgoing = EmailMessange::query()->where('recieved', false)->where('status', 0)->get();
        foreach ($outgoing as $mail) {
            if (str_contains(strtolower($mail->reciever), '@devov.de')) {
                $mail->update(['status' => 1]);
                return;
            }
            $this->info('Sending Mail to ' . $mail->reciever);
            Mail::raw($mail->text, static function (Mailable $message) use ($mail) {
                $message->to($mail->reciever);
                $message->from($mail->sender);
                $message->replyTo($mail->replyTo);
                $message->subject($mail->subject);
                $message->metadata('DEVOTION_MAIL_ACCOUNTID', $mail->accountId);
                $message->metadata('DEVOTION_MAIL_ID', $mail->id);
                $message->metadata('DEVOTION_MAIL_CREATED', $mail->created_at);
            });
            $mail->update(['status' => 1]);
        }
        $this->info('Done!');
    }
}
