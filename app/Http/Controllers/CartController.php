<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function index()
    {
        $categoriesUrl = "/categories";
        $categories = $this->callMDarasaAPIGetWithoutToken($categoriesUrl);

        $featuredUrl = "/featured";
        $featuredCourses = $this->callMDarasaAPIGetWithoutToken($featuredUrl);
        $newArrivalsUrl = "/new-arrivals";
        $newArrivals = $this->callMDarasaAPIGetWithoutToken($newArrivalsUrl);
        $popularUrl = "/courses/popular";
        $popularUnits = $this->callMDarasaAPIGetWithoutToken($popularUrl);

        return view('student.cart', compact('categories', 'featuredCourses',
            'newArrivals', 'popularUnits'));
    }

    public function checkout(Request $request)
    {
        if (!$this->webAuth()) {
            Session::flash('error', 'Please login/signup to checkout');
            return redirect("/cart");
        }

        $itemsInCart = $request->itemsInCart;
        if (substr($itemsInCart, -1) == ",") {

            $itemsInCart = substr($itemsInCart, 0, strlen($itemsInCart) - 1);
        }

        $items = explode(",", $itemsInCart);
        $orderDetails = array();
        $totalOrderValue = 0;
        foreach ($items as $orderItem) {

            $orderItemArr = explode(":", $orderItem);

            $totalOrderValue += $orderItemArr[1];
            array_push($orderDetails,
                [
                    "courseUnitId" => $orderItemArr[0],
                    "quantity" => 1,
                    "linePrice" => $orderItemArr[1],
                ]
            );
        }

        $ordersPayload = [
            "profileId" => Session::get("profileId"),
            "voucherCode" => "",
            "totalOrderValue" => $totalOrderValue,
            "notes" => "Student order online",
            "studentOrderDetails" => $orderDetails,
        ];

        $placeOrderUrl = "/student/order/save";
        $orderResponse = $this->callMDarasaAPIPostWithToken($ordersPayload, $placeOrderUrl);

        if (is_null($orderResponse) || !$orderResponse->Success) {

            Session::flash('error', `An error occurred and we could not create your order.
             Please try again later`);
            return redirect("/cart");
        }

        $studentOrderId = $orderResponse->Data->studentOrderId;

        $balance = 0.0;
        $profileId = Session::get("profileId");

        $balancePayload = [
            "profileId" => $profileId,
        ];

        $profileBalanceUrl = "/profile/balance";
        $profileBalance = $this->callMDarasaAPIPostWithToken($balancePayload, $profileBalanceUrl);

        if (!is_null($profileBalance)) {

            $profileBalance = $profileBalance->Data;
            $balance = $profileBalance->balance;
        }

        if ($balance >= $totalOrderValue) {

            $paymentUrl = "/student/order/complete";

            $payFromWalletPayload = [
                "studentOrderId" => $studentOrderId,
            ];

            $response = $this->callMDarasaAPIPostWithToken($payFromWalletPayload, $paymentUrl);

            if (!is_null($response)) {

                if ($response->Success) {

                    Session::flash('success', 'Congratulations!! Your order is completed successfully');
                    return redirect("/?checkout=success");
                } else {

                    Session::flash('error', `Sorry, we encountered an error and therefore couldn\'t
                     complete your transaction. You can however complete your order from the orders
                      section.`);

                    return redirect("/?checkout=success");
                }
            }

        } else {

            $stkPushUrl = "/deposit/stk";

            $stkPayload = [
                "orderId" => $studentOrderId,
                "msisdn" => Session::get("msisdn"),
                "amount" => $totalOrderValue,
                "profileId" => $profileId,
            ];

            $response = $this->callMDarasaAPIPostWithToken($stkPayload, $stkPushUrl);

            if (!is_null($response)) {

                if ($response->Success) {

                    Session::flash('success', 'Please authorize the payment request to your MPESA phone.');

                    return redirect("/?checkout=success");

                } else {

                    Session::flash('error', `Sorry, we encountered an error and therefore
                     couldn\'t complete your transaction. However your order can be completed
                      from the orders section`);

                    return redirect("/?checkout=success");
                }
            }

            return redirect()->back();
        }

    }
}
