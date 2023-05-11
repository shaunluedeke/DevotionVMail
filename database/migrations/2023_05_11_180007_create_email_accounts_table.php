<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('characters_email_account', static function (Blueprint $table) {
            $table->id();
            $table->integer('charId');
            $table->string('address');
            $table->string('password');
            $table->json('loginChars')->default("[]");
            $table->boolean('isActive')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('characters_email_account');
    }
};
