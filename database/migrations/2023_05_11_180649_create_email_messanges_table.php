<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('characters_email_messanges', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('accountId')->constrained('characters_email_account', 'id')->onDelete('cascade');
            $table->string('reciever');
            $table->string('sender');
            $table->string('replyTo');
            $table->text('subject');
            $table->text('text');
            $table->string('folder');
            $table->boolean('recieved');
            $table->integer('status');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('characters_email_messanges');
    }
};
