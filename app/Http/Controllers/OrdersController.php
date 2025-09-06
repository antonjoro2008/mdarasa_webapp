<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;

class OrdersController extends Controller
{

    public function index()
    {
        if (!$this->webAuth()) {
            Session::flash('error', 'Please login to access this page');
            return redirect("/");
        }

        $categoriesUrl = "/categories";
        $categories = $this->callMDarasaAPIGetWithoutToken($categoriesUrl);

        $profilePayload = [
            "profileId" => Session::get("profileId"),
        ];

        $ordersUrl = "/student/orders";
        $orders = $this->callMDarasaAPIPostWithToken($profilePayload, $ordersUrl);
        $orders = !is_null($orders) ? $orders->Data : [];

        return view('student.orders', compact('categories', 'orders'));
    }
}
