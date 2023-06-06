<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Purse;
use App\Models\PurseHistory;
use App\Services\ExchangeService;

class PurseController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetchPursesOfUser(Request $request)
    {
        try {
            $purseModel = new Purse;

            $purses = $purseModel->fetchPurses($request->user()->id);

            return sendResponse($purses);
        } catch (\Exception $e) {
            return sendError($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetchPurseHistory(Request $request)
    {
        try {
            $purseHistoryModel = new PurseHistory;

            $purses = $purseHistoryModel->fetchPurseHistory($request->user()->id, $request->query());

            return sendResponse($purses);
        } catch (\Exception $e) {
            return sendError($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createPurse(Request $request)
    {
        $currency = $request->all();

        $validator = Validator::make($currency, [
            "currency" => "required|unique:purses"
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();

            return sendError($errors["currency"][0], 422);
        }

        try {
            $purseModel = new Purse;

            $wallet = $purseModel->createPurse([
                "userId" => $request->user()->id,
                ...$currency
            ], $request->user()->id);

            $wallet["totalAmount"] = "0.0000";

            return sendResponse($wallet);
        } catch (\Exception $e) {
            return sendError($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addAmountToPurse(Request $request)
    {
        $amountData = $request->all();

        $validator = Validator::make($amountData, [
            "walletId" => "required",
            "transaction" => "required",
            "amount" => "required",
        ]);

        if ($validator->fails()) {
            return sendError('Validation Error', 422, $validator->errors());
        }

        try {
            $purseModel = new Purse;

            $wallet = $purseModel->updatePurse($amountData, $request->user()->id);
            return sendResponse($wallet);
        } catch (\Exception $e) {
            return sendError($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function exchangePurseToPurse(Request $request)
    {
        try {
            $currencyInfo = $request->all();
            $userId = $request->user()->id;
            $exchangeService = new ExchangeService;

            $exchangeInfo = $exchangeService->fetchPairConversion($currencyInfo["amount"], $currencyInfo["fromCurrency"], $currencyInfo["toCurrency"]);

            $purseModel = new Purse;
            $purseHistoryModel = new PurseHistory;

            $fromWalletData = [
                "userId" => $userId,
                "amount" => $currencyInfo["amount"],
                "currency" => $currencyInfo["fromCurrency"],
                "walletId" => $currencyInfo["fromWalletId"],
                "transaction" => 2,
                "transfer" => 1
            ];
            $toWalletData = [
                "userId" => $userId,
                "amount" => $exchangeInfo["conversion_result"],
                "walletId" => $currencyInfo["toWalletId"],
                "currency" => $currencyInfo["toCurrency"],
                "transaction" => 1,
                "transfer" => 1
            ];

            $fromWallet = $purseModel->updatePurse($fromWalletData, $request->user()->id);
            $toWallet = $purseModel->updatePurse($toWalletData, $request->user()->id);

            return sendResponse(["fromWallet" => $fromWallet, "toWallet" => $toWallet]);
        } catch (\Exception $e) {
            return sendError($e->getMessage(), $e->getCode());
        }
    }
}
