<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AccountsController extends Controller
{

    public function index()
    {
        if (!$this->webAuth()) {
            Session::flash('error', 'Please login to access this page');
            return redirect("/");
        }

        $categoriesUrl = "/categories";
        $categories = $this->callMDarasaAPIGetWithoutToken($categoriesUrl);

        $profileUrl = "/profile";
        $profilePayload = [
            "profileId" => Session::get("profileId"),
        ];

        $profileInfo = $this->callMDarasaAPIPostWithToken($profilePayload, $profileUrl);
        $profileInfo = $profileInfo->Data;

        $walletUrl = "/profile/balance";
        $walletInfo = $this->callMDarasaAPIPostWithToken($profilePayload, $walletUrl);
        $walletInfo = $walletInfo->Data;

        $transactionsUrl = "/profile/transactions";
        $transactions = $this->callMDarasaAPIPostWithToken($profilePayload, $transactionsUrl);
        $transactions = $transactions->Data;

        return view('student.accounts', compact('categories', 'profileInfo', 'walletInfo',
            'transactions'));
    }

    public function deposit(Request $request)
    {

        if (!$this->webAuth()) {
            Session::flash('error', 'Please login to deposit funds into your account');
            return redirect("/cart");
        }

        $stkPushUrl = "/deposit/stk";

        $stkPayload = [
            "orderId" => Session::get("msisdn"),
            "msisdn" => Session::get("msisdn"),
            "amount" => $request->depositAmount,
            "profileId" => Session::get("profileId"),
        ];

        $response = $this->callMDarasaAPIPostWithToken($stkPayload, $stkPushUrl);

        if (!is_null($response)) {

            if ($response->Success) {

                Session::flash('success', 'Please authorize the deposit request to your MPESA phone.');
                return redirect()->back();

            } else {

                Session::flash('error', `Sorry, we encountered an error and deposit failed. Please try later`);
                return redirect()->back();
            }
        }

        return redirect()->back();

    }

    public function withdraw(Request $request)
    {

        if (!$this->webAuth()) {
            Session::flash('error', 'Please login to withdraw funds from your account');
            return redirect("/cart");
        }

        $withdrawalUrl = "/mpesa/withdraw";

        $withdrawalPayload = [
            "msisdn" => Session::get("msisdn"),
            "amount" => $request->withdrawAmount,
            "profileId" => Session::get("profileId"),
        ];

        $response = $this->callMDarasaAPIPostWithToken($withdrawalPayload, $withdrawalUrl);

        if (!is_null($response)) {

            if ($response->Success && $response->Code == 0) {

                Session::flash('success', 'Withdrawal request accepted successfully. Please wait for approval.');

                return redirect()->back();

            } elseif ($response->Code == 2) {

                Session::flash('error', 'Insufficient balance in your account.');
                return redirect()->back();

            } elseif ($response->Code == -1) {

                Session::flash('error', 'Invalid amount for withdrawal. Please use a valid number');
                return redirect()->back();

            } elseif ($response->Code == -6) {

                Session::flash('error', 'Invalid phone number. Please use a valid phone number');
                return redirect()->back();

            } elseif ($response->Code == 3) {

                Session::flash('error', 'Maximum withdrawal daily limit exceeded. Please withdraw more tomorrow');
                return redirect()->back();

            } elseif ($response->Code == 4) {

                Session::flash('error', 'Maximum withdrawal transaction limit exceeded. Please withdraw less');
                return redirect()->back();

            } else {

                Session::flash('error', `Sorry, we encountered an error and deposit failed. Please try later`);
                return redirect()->back();
            }
        }

        return redirect()->back();

    }
}
