<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PurseHistory;

class Purse extends Model
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
        'totalAmount',
    ];

    public function history()
    {
        return $this->hasMany(PurseHistory::class, "purseId");
    }

    /**
     * @param integer $userId
     * @return App\Models\Purse
     */
    public function fetchPurses($userId)
    {
        return self::where("userId", $userId)->get();
    }

    /**
     * @param integer $userId
     * @return App\Models\Purse
     */
    public function fetchPurseHistory($userId)
    {
        return self::where("userId", $userId)->with("history")->get();
    }

    /**
     * @param array $purse
     * @return App\Models\Purse
     */
    public function createPurse($purse)
    {
        return self::create($purse);
    }

    /**
     * @param array $amountData
     * @param integer $userId
     * @return App\Models\Purse
     */
    public function updatePurse($amountData, $userId)
    {
        $purseOfUser = self::where("userId", $userId)->where("id", $amountData["walletId"])->first();

        if ($amountData["transaction"] === 1) {
            $purseOfUser->totalAmount += $amountData["amount"];
        } else {
            $purseOfUser->totalAmount -= $amountData["amount"];
        }

        $result = $purseOfUser->save();

        if ($result) {
            $purseHistory = new PurseHistory;

            $purseHistory->addAmountToHistoryOfPurse([
                "userId" => $userId,
                "purseId" => $amountData["walletId"],
                "currency" => $purseOfUser->currency,
                ...$amountData
            ]);
        }

        return $purseOfUser;
    }
}
