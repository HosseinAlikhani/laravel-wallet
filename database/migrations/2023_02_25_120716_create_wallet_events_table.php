<?php

use D3cr33\Wallet\Core\Events\DecreaseWalletEvent;
use D3cr33\Wallet\Core\Events\IncreaseWalletEvent;
use D3cr33\Wallet\Core\Events\LockWalletEvent;
use D3cr33\Wallet\Core\Events\UnLockWalletEvent;
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
        Schema::create('wallet_events', function (Blueprint $table) {
            $table->uuid('uuid');
            $table->string('user_id');
            $table->integer('amount');
            $table->enum('event_type', [
                IncreaseWalletEvent::EVENT_TYPE,
                DecreaseWalletEvent::EVENT_TYPE,
                LockWalletEvent::EVENT_TYPE,
                UnLockWalletEvent::EVENT_TYPE
            ]);
            $table->integer('event_count');
            $table->json('detail');
            $table->timestamp('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_events');
    }
};
