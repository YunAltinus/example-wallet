<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Wallet;
use App\Models\WalletHistory;
use App\Services\ExchangeService;
use App\Http\Controllers\HelperController;
use App\Models\WalletExchange;

class WalletController extends HelperController
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetchWalletsOfUser(Request $request)
    {
        try {
            $walletModel = new Wallet;

            $wallets = $walletModel->fetchWallets($request->user()->id);

            return $this->sendResponse($wallets);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetchWalletHistory(Request $request)
    {
        try {
            $walletHistoryModel = new WalletHistory;

            $wallets = $walletHistoryModel->fetchWalletHistory($request->user()->id, $request->query());

            return $this->sendResponse($wallets);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetchWalletExchange(Request $request)
    {
        try {
            $walletExchangeModel = new WalletExchange;

            $exchanges = $walletExchangeModel->fetchWalletExchanges($request->user()->id);

            return $this->sendResponse($exchanges);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createWallet(Request $request)
    {
        $currency = $request->all();

        $validator = Validator::make($currency, [
            "currency" => "required|unique:wallets"
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();

            return $this->sendError($errors["currency"][0], 422);
        }

        try {
            $walletModel = new Wallet;

            $wallet = $walletModel->createWallet([
                "userId" => $request->user()->id,
                ...$currency
            ], $request->user()->id);

            $wallet["totalAmount"] = "0.0000";

            return $this->sendResponse($wallet);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addAmountToWallet(Request $request)
    {
        $amountData = $request->all();

        $validator = Validator::make($amountData, [
            "currency" => "required",
            "transaction" => "required",
            "amount" => "required",
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error', 422, $validator->errors());
        }

        try {
            $walletModel = new Wallet;

            $walletExchangeModel = new WalletExchange;
            $exchangeService = new ExchangeService;

            $wallet = $walletModel->fetchOrCreateWallet($amountData, $request->user()->id);

            if ($amountData["transaction"] === 1) {
                $wallet->amount += $amountData["amount"];
            } else {
                if ($amountData["amount"] > $wallet->amount) {
                    return $this->sendError('The balance in your account is insufficient', 400);
                }
                
                $wallet->amount -= $amountData["amount"];
            }

            $wallet->save();

            $wallets = $walletModel->fetchWallets($request->user()->id);

            $isFirst = true;

            foreach ($wallets as $wallet) {
                $exchangeData = $exchangeService->fetchCurrencyByExchange($wallet->currency);

                $result = $walletExchangeModel->updateWalletExchange($exchangeData, $request->user()->id, $wallet->amount, $isFirst);
                
                if ($result) {
                    $walletHistory = new WalletHistory;
    
                    $walletHistory->addAmountToHistoryOfWallet([
                        "userId" => $request->user()->id,
                        "walletId" => $wallet->id,
                        ...$amountData
                    ]);
                }

                $isFirst = false;
            }

            return $this->sendResponse($wallets);
        } catch (\Exception $e) {
            dd($e);
            return $this->sendError($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function exchangeWalletToWallet(Request $request)
    {
        try {
            $currencyInfo = $request->all();
            $userId = $request->user()->id;

            $walletModel = new Wallet;
            $walletExchangeModel = new WalletExchange;
            $exchangeService = new ExchangeService;

            $toWallet = $walletModel->fetchOrCreateWallet([
                "userId" => $userId,
                "currency" => $currencyInfo['toCurrency']
            ], $userId);

            $fromWallet = $walletModel->fetchOrCreateWallet([
                "userId" => $userId,
                "currency" => $currencyInfo['fromCurrency']
            ], $userId);

            $exchangeInfo = $exchangeService->fetchPairConversion($currencyInfo["amount"], $currencyInfo["fromCurrency"], $currencyInfo["toCurrency"]);
            
            if ($currencyInfo["amount"] > $fromWallet->amount) {
                return $this->sendError('The balance in your account is insufficient', 400);
            }

            $fromWallet->amount -= $currencyInfo["amount"];
            $toWallet->amount += $exchangeInfo["conversion_result"];

            $toWallet->save();
            $fromWallet->save();

            $wallets = $walletModel->fetchWallets($request->user()->id);

            $isFirst = true;

            foreach ($wallets as $wallet) {
                $exchangeData = $exchangeService->fetchCurrencyByExchange($wallet->currency);

                $result = $walletExchangeModel->updateWalletExchange($exchangeData, $request->user()->id, $wallet->amount, $isFirst);

                $isFirst = false;
            }

            if ($result) {
                $walletHistory = new WalletHistory;

                $fromWalletData = [
                    "userId" => $userId,
                    "amount" => $fromWallet->amount,
                    "currency" => $currencyInfo["fromCurrency"],
                    "walletId" => $currencyInfo["fromWalletId"],
                    "transaction" => 2,
                    "transfer" => 1
                ];

                $toWalletData = [
                    "userId" => $userId,
                    "amount" => $toWallet->amount,
                    "currency" => $currencyInfo["toCurrency"],
                    "walletId" => $toWallet->id,
                    "transaction" => 1,
                    "transfer" => 1
                ];

                $walletHistory->addAmountToHistoryOfWallet($toWalletData);
                $walletHistory->addAmountToHistoryOfWallet($fromWalletData);
            }

            return $this->sendResponse($wallets);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getCode());
        }
    }
}
