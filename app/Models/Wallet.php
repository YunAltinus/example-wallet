<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\WalletHistory;
use App\Services\ExchangeService;

class Wallet extends Model
{
    use HasFactory;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'userId',
        'currency',
        'amount'
    ];

    public function history()
    {
        return $this->hasMany(WalletHistory::class, "purseId");
    }

    /**
     * @param array $data
     * @param integer $userId
     * @return App\Models\Wallet
     */
    public function fetchOrCreateWallet($data, $userId)
    {
        return self::firstOrCreate(["userId" => $userId, 'currency' => $data['currency']], [
            'userId' => $userId,
            'currency' => $data['currency']
        ]);
    }

    /**
     * @param integer $userId
     * @return App\Models\Wallet
     */
    public function fetchWallets($userId)
    {
        return self::where("userId", $userId)->get();
    }

    /**
     * @param integer $userId
     * @return App\Models\Wallet
     */
    public function fetchWalletHistory($userId)
    {
        return self::where("userId", $userId)->with("history")->get();
    }

    /**
     * @param array $wallet
     * @return App\Models\Wallet
     */
    public function createWallet($wallet)
    {
        return self::create($wallet);
    }

    /**
     * @param array $amountData
     * @param integer $userId
     * @return App\Models\Wallet
     */
    public function updateWallet($amountData, $userId)
    {
        $purseOfUser = self::where("userId", $userId)->where("id", $amountData["walletId"])->first();
        
        if ($purseOfUser['currency'] != $amountData['currency']) {
            $exchangeService = new ExchangeService;

            $exchangeInfo = $exchangeService->fetchPairConversion($amountData['amount'], $amountData['currency'], $purseOfUser->currency);
            $amountData["amount"] = $exchangeInfo["conversion_result"];
        }

        if ($amountData["transaction"] === 1) {
            $purseOfUser->totalAmount += $amountData["amount"];
        } else {
            $purseOfUser->totalAmount -= $amountData["amount"];
        }

        $result = $purseOfUser->save();

        if ($result) {
            $WalletHistory = new WalletHistory;

            $WalletHistory->addAmountToHistoryOfWallet([
                "userId" => $userId,
                "walletId" => $amountData["walletId"],
                ...$amountData
            ]);
        }

        return $purseOfUser;
    }
}
