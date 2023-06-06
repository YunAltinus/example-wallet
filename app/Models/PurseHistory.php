<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurseHistory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'userId',
        'walletId',
        'currency',
        'transfer',
        'transaction',
        'amount',
    ];

    /**
     * @param integer $purseId
     * @param array $queryData
     * @return App\Models\Purse
     */
    public function fetchPurseHistory($userId, $queryData)
    {
        if (!empty($queryData)) {
            return self::where("userId", $userId)->where(function ($query) use ($queryData) {
                return $query
                    ->where('transaction', "LIKE", "%{$queryData["transactionType"]}%")
                    ->where('transfer', "LIKE", "%{$queryData["transferType"]}%");
            })->orderBy('created_at', 'desc')->get();
        } else {
            return self::where("userId", $userId)->orderBy('created_at', 'desc')->get();
        }
    }

    /**
     * @param array $amount
     * @return App\Models\PurseHistory
     */
    public function addAmountToHistoryOfPurse($amount)
    {
        return self::create($amount);
    }
}
