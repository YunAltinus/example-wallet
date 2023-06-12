<?php

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
        Schema::create('wallet_histories', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('userId')->constrained("users")->onDelete('cascade');
            // $table->foreignId('walletId')->constrained("wallets")->onDelete('cascade');
            $table->bigInteger('userId')->unsigned();
            $table->bigInteger('walletId')->unsigned();
            $table->string("currency");
            $table->enum("transaction", [1, 2]);
            $table->enum("transfer", [1, 2])->default(2);
            $table->decimal("amount", 19, 4);
            $table->timestamps();
        });
    }


    /**
     * @param integer $userId
     * @return App\Models\WalletHistory
     */
    public function fetchWalletHistory($userId)
    {
        return self::where("userId", $userId)->get();
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_histories');
    }
};
