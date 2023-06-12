<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletExchange extends Model
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

    /**
     * @param int $userId
     * @param array $supportedCodes
     * @return App\Models\WalletExchange
     */
    public function createSupportedCodes($userId, $supportedCodes)
    {
        foreach ($supportedCodes as $supportedCode) {
            self::create([
                "userId" => $userId,
                "currency" => $supportedCode[0]
            ]);
        }
    }

    /**
     * @param int $userId
     * @return App\Models\WalletExchange
     */
    public function fetchWalletExchanges($userId)
    {
        return self::where("userId", $userId)->get();
    }

    /**
     * @param array $exchangeData
     * @param int $userId
     * @param int|string $amount
     * @param boolean $isFisrt
     * @return boolean
     */
    public function updateWalletExchange($exchangeData, $userId, $amount, $isFisrt)
    {
        foreach ($exchangeData['conversion_rates'] as $currency => $exchangeRate) {
            $exchange = self::where('userId', $userId)->where('currency', $currency)->first();

            if ($isFisrt) {
                $exchange->amount = $exchangeRate * $amount;
            } else {
                $exchange->amount += $exchangeRate * $amount;
            }

            $exchange->save();
        }

        return true;
    }
}
