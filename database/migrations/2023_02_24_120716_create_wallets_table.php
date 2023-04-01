<?php

use D3cr33\Wallet\Events\DecreaseWalletEvent;
use D3cr33\Wallet\Events\IncreaseWalletEvent;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->uuid('uuid');
            $table->string('user_id');
            $table->integer('amount');
            $table->integer('balance');
            $table->enum('event_type', [
                IncreaseWalletEvent::EVENT_TYPE,
                DecreaseWalletEvent::EVENT_TYPE
            ]);
            $table->integer('event_count');
            $table->timestamp('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
};
