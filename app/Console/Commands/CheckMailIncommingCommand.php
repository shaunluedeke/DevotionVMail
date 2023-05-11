<?php

namespace App\Console\Commands;

use App\Models\EmailAccount;
use App\Models\EmailMessange;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Webklex\IMAP\Facades\Client;

class CheckMailIncommingCommand extends Command
{
    protected $signature = 'check:mail-incomming';

    protected $description = 'Checking Incomming Mail';

    public function handle(): void
    {
        $accounts = EmailAccount::query()->where('isActive', true)->get();
        $client = Client::account('default');
        try {
            $client->connect();
            $folders = $client->getFolders();
            foreach ($folders as $folder) {
                try {
                    $messages = $folder->messages()->all()->get();
                    foreach ($messages as $message) {
                        $this->info('Checking Mail from ' . $message->getFrom()[0]->mail);
                        $rejectedTo = [];
                        foreach ($message->getTo()->all() as $to) {
                            $account = $accounts->where('address', strtolower($to->mail))->first();
                            if ($account === null) {
                                $rejectedTo[] = $to->mail;
                                continue;
                            }
                            $this->info('Mail to ' . $to->mail . ' is accepted');
                            $mail = new EmailMessange();
                            $mail->accountId = $account->id;
                            $mail->reciever = $to->mail;
                            $mail->sender = $message->getFrom()[0]->personal;
                            $mail->replyTo = $message->getReplyTo()[0]->mail;
                            $mail->subject = $message->getSubject();
                            $mail->text = $message->getHTMLBody();
                            $mail->folder = $folder->name;
                            $mail->recieved = true;
                            $mail->status = 0;
                            $mail->save();
                        }
                        if (count($rejectedTo) > 0) {
                            $this->info('Mail to ' . implode(', ', $rejectedTo) . ' is rejected');
                            $to = $message->getFrom()[0]->mail;
                            if(count($rejectedTo) < 2) {
                                Mail::raw('Der User mit der Email Adresse "' . $rejectedTo[0] . '" wurde nicht gefunden! Dein Title war: "'.$message->subject.'" Mit der Nachricht: "'.$message->getTextBody().'"', static function ($message) use ($to) {
                                    $message->to($to);
                                    $message->from('system@devov.de');
                                    $message->subject('Email nicht gefunden');
                                });
                                continue;
                            }
                            Mail::raw('Die User mit den Email Adressen "' . implode(', ', $rejectedTo) . '" wurden nicht gefunden! Dein Title war: "'.$message->subject.'" Mit der Nachricht: "'.$message->getTextBody().'"', static function ($message) use ($to) {
                                $message->to($to);
                                $message->from('system@devov.de');
                                $message->subject('Email nicht gefunden');
                            });
                        }
                        $message->delete();
                    }
                }catch (Exception $e){
                    echo $e->getMessage();
                }
            }
        }catch (Exception $e){
            echo $e->getMessage();
        }
    }
}
