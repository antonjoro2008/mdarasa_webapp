<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class UssdController extends Controller
{
    private ?string $response = null;

    const STK_ENDPOINT = "https://mdarasa.com/revenue/deposit/stk";

    public function ussdCallback(Request $request)
    {
        $sessionId = $request->sessionId;
        $serviceCode = $request->serviceCode;
        $phoneNumber = $request->phoneNumber;
        $text = $request->text;

        Log::info("USSD Callback: Session ID: " . $sessionId . "
             Service Code: " . $serviceCode . " Phone Number: "
            . $phoneNumber . " Text: " . $text);

        $components = explode("*", $text);

        if (
            !empty($components)
            && $components[count($components) - 1] == "00"
        ) {

            $response = $this->ussdMainMenu();
            return $this->sendUssdResponse($response);
        }

        if ($text == "") {
            $response = $this->ussdMainMenu();

        } elseif (strlen($text) == 1) {

            switch ($text) {
                case "1":
                    $response = $this->getParkingCategories();
                    break;
                case "2":
                    $response = $this->getMarkets();
                    break;
                case "3":
                    $response = $this->showComingSoon();
                    break;
                case "4":
                    $response = $this->showComingSoon();
                    break;
                case "5":
                    $response = $this->showComingSoon();
                    break;
                case "6":
                    $response = $this->showComingSoon();
                    break;
                case "7":
                    $response = $this->showMyProfile();
                    break;
                default:
                    $response = "END Invalid option. Please try again.";
                    break;

            }

        } elseif (strlen($text) == 3) {

            $selectioComponents = explode("*", $text);

            if ($selectioComponents[0] == "1") {

                $response = $this->getZones($selectioComponents[0]);

            } elseif ($selectioComponents[0] == "2") {

                $response = $this->showComingSoon();

            } elseif ($selectioComponents[2] == "0") {

                $response = $this->ussdMainMenu();

            } elseif ($selectioComponents[2] == "00") {

                $response = $this->ussdMainMenu();

            } else {

                $response = $this->showComingSoon();
            }

        } elseif (strlen($text) == 5) {

            $selectionComponents = explode("*", $text);

            if ($selectionComponents[0] == "1") {

                $response = $this->showVehicleRegistration($phoneNumber);

            } elseif ($selectionComponents[0] == "2") {

                $response = $this->showComingSoon();

            } elseif ($selectionComponents[2] == "0") {

                if ($selectionComponents[0] == "1") {

                    $response = $this->getParkingCategories();

                } elseif ($selectionComponents[0] == "2") {

                    $response = $this->getMarkets();
                }

            } elseif ($selectionComponents[2] == "00") {

                $response = $this->ussdMainMenu();

            } else {

                $response = $this->showComingSoon();
            }

        } elseif (strlen($text) == 7) {

            $selectionComponents = explode("*", $text);
            $lastComponent = $components[count($components) - 1];

            if ($lastComponent == "1") {

                $response = $this->enterVehicleRegistrationNumber();

            } else {

                //query the transactions table for the account number

                $phoneNumber = $this->formatMobileNumber($phoneNumber);

                $previousVehicles = DB::table('revenue_collection.transactions')
                    ->join(
                        'revenue_collection.services',
                        'revenue_collection.transactions.service_id',
                        '=',
                        'revenue_collection.services.id'
                    )
                    ->join(
                        'revenue_collection.users',
                        'revenue_collection.transactions.user_id',
                        '=',
                        'revenue_collection.users.id'
                    )
                    ->where('users.phone_number', $phoneNumber)
                    ->select('account')
                    ->distinct('account')
                    ->orderBy('account')
                    ->get();

                $counter = 2;
                $account = "DEFAULT";

                foreach ($previousVehicles as $vehicle) {

                    if ($counter == $lastComponent) {

                        $account = strtoupper(str_replace(' ', '', $vehicle->account));
                        break;
                    }

                    $counter++;
                }

                $amount = 300;
                $serviceCost = DB::table('revenue_collection.service_costs')
                    ->where('service_id', $selectionComponents[0])
                    ->where('service_category_id', $selectionComponents[1])
                    ->first();

                if ($serviceCost) {
                    $amount = $serviceCost->cost;
                }

                Log::info("Exploded Array: " . json_encode($selectionComponents));

                $phoneNumber = $this->formatMobileNumber($phoneNumber);
                $serviceCategoryId = $selectionComponents[1];

                $payload = [
                    "msisdn" => $phoneNumber,
                    "account" => $account . "-" . $serviceCategoryId . "-" . $selectionComponents[2],
                    "depositAmt" => intval($amount)
                ];

                Log::info("Payload for STK: " . json_encode($payload));

                Log::info("STK Endpoint: " . self::STK_ENDPOINT);

                $apiResponse = $this->callRevenueAPIPostWithoutToken($payload, self::STK_ENDPOINT);

                Log::info("API Response: " . json_encode($apiResponse));

                if (!is_null($apiResponse)) {

                    if ($apiResponse->Success) {

                        $response = "END Thank you. Please pay via the MPESA prompt sent to your phone.";

                    } else {

                        $response = "END Sorry! We could not process your request. Please try again.";
                    }

                } else {

                    $response = "END Sorry! We could not process your request. Please try again.";
                }
            }

        } elseif (strlen($text) > 8) {

            $selectionComponents = explode("*", $text);
            $lastComponent = $selectionComponents[count($selectionComponents) - 1];

            if ($selectionComponents[0] == "1" && $selectionComponents[1] == "1") {

                $amount = 300;
                $serviceCost = DB::table('revenue_collection.service_costs')
                    ->where('service_id', $selectionComponents[0])
                    ->where('service_category_id', $selectionComponents[1])
                    ->first();

                if ($serviceCost) {
                    $amount = $serviceCost->cost;
                }

                Log::info("Exploded Array: " . json_encode($selectionComponents));

                $phoneNumber = $this->formatMobileNumber($phoneNumber);
                $serviceCategoryId = $selectionComponents[1];

                $payload = [
                    "msisdn" => $phoneNumber,
                    "account" => $selectionComponents[count($selectionComponents) - 1] . "-" . $serviceCategoryId . "-" . $selectionComponents[2],
                    "depositAmt" => intval($amount)
                ];

                Log::info("Payload for STK: " . json_encode($payload));

                Log::info("STK Endpoint: " . self::STK_ENDPOINT);

                $apiResponse = $this->callRevenueAPIPostWithoutToken($payload, self::STK_ENDPOINT);

                Log::info("API Response: " . json_encode($apiResponse));

                if (!is_null($apiResponse)) {

                    if ($apiResponse->Success) {

                        $response = "END Thank you. Please pay via the MPESA prompt sent to your phone.";

                    } else {

                        $response = "END Sorry! We could not process your request. Please try again.";
                    }

                } else {

                    $response = "END Sorry! We could not process your request. Please try again.";
                }

            } elseif (
                $selectionComponents[0] == "1" &&
                $selectionComponents[1] == "2" &&
                strlen($lastComponent) > 2
            ) {

                $response = $this->getSeasonalParkingOptions();

            } elseif (
                $selectionComponents[0] == "1" &&
                $selectionComponents[1] == "2" &&
                strlen($lastComponent) <= 2
            ) {

                //fetch service cost for seasonal parking

                $serviceCost = DB::table('revenue_collection.service_costs')
                    ->where('service_id', $selectionComponents[0])
                    ->where('service_category_id', $lastComponent)
                    ->first();

                if ($serviceCost) {
                    $amount = $serviceCost->cost;
                }

                Log::info("Exploded Array: " . json_encode($selectionComponents));

                $phoneNumber = $this->formatMobileNumber($phoneNumber);
                $serviceCategoryId = $lastComponent;

                $payload = [
                    "msisdn" => $phoneNumber,
                    "account" => $selectionComponents[count($selectionComponents) - 2] . "-" . $serviceCategoryId . "-" . $selectionComponents[2],
                    "depositAmt" => intval($amount)
                ];

                Log::info("Payload for STK: " . json_encode($payload));

                Log::info("STK Endpoint: " . self::STK_ENDPOINT);

                $apiResponse = $this->callRevenueAPIPostWithoutToken($payload, self::STK_ENDPOINT);

                Log::info("API Response: " . json_encode($apiResponse));

                if (!is_null($apiResponse)) {

                    if ($apiResponse->Success) {

                        $response = "END Thank you. Please pay via the MPESA prompt sent to your phone.";

                    } else {

                        $response = "END Sorry! We could not process your request. Please try again.";
                    }

                } else {

                    $response = "END Sorry! We could not process your request. Please try again.";
                }

            } elseif ($selectionComponents[0] == "2") {

                $response = $this->showComingSoon();

            } elseif ($selectionComponents[2] == "0") {

                $response = $this->getZones($selectionComponents[0]);

            } elseif ($selectionComponents[2] == "00") {

                $response = $this->ussdMainMenu();

            } else {

                $response = $this->showComingSoon();
            }

        }

        return $this->sendUssdResponse($response);
    }

    private function ussdMainMenu()
    {
        $ussdString = "CON Welcome to Nairobi County. Choose an option: \n";
        $ussdString .= "1. Parking Services \n";
        $ussdString .= "2. Markets Cess \n";
        $ussdString .= "3. Licensing Services \n";
        $ussdString .= "4. Land Services \n";
        $ussdString .= "5. Property Services \n";
        $ussdString .= "6. My Bills \n";
        $ussdString .= "7. My Profile \n";

        return $ussdString;
    }

    public function getServices()
    {

        $services = DB::table('revenue_collection.services')->get();
        $response = "CON Select service: \n";

        foreach ($services as $service) {
            $response .= $service->id . ". " . $service->name . "\n";
        }

        return $response;
    }

    private function getServiceCategories($serviceId)
    {
        $categories = DB::table('revenue_collection.service_categories')
            ->where('service_id', $serviceId)
            ->get();
        $response = "CON Select Service Category: \n";

        foreach ($categories as $category) {
            $response .= $category->id . ". " . $category->name . "\n";
        }

        return $response;
    }

    private function getParkingCategories()
    {

        $response = "CON Select Option: \n";

        $response .= "1. Daily Parking \n";
        $response .= "2. Seasonal Parking \n";

        $response .= "0. BACK \n";
        $response .= "00. HOME \n";

        return $response;

    }

    public function getMarkets()
    {

        $markets = DB::table('revenue_collection.markets')->get();
        $response = "CON Select Market: \n";

        foreach ($markets as $market) {
            $response .= $market->id . ". " . $market->name . "\n";
        }

        return $response;
    }

    private function getZones($serviceId)
    {
        $zones = DB::table('revenue_collection.zones')
            ->where('service_id', $serviceId)
            ->get();
        $response = "CON Select Parking Zone: \n";

        foreach ($zones as $zone) {
            $response .= $zone->id . ". " . strtoupper($zone->name) . "\n";
        }

        $response .= "0. BACK \n";
        $response .= "00. HOME \n";
        return $response;
    }

    private function getVehicleCategories($serviceCategoryId)
    {
        $vehicleCategories = DB::table('revenue_collection.vehicle_categories')
            ->where('service_category_id', $serviceCategoryId)
            ->get();
        $response = "CON Select Vehicle Category: \n";

        foreach ($vehicleCategories as $vehicleCategory) {
            $response .= $vehicleCategory->id . ". " . $vehicleCategory->name . "\n";
        }

        return $response;
    }

    private function showMyProfile()
    {
        return "CON My Profile: \n" .
            "1. View Profile \n" .
            "2. Reset PIN \n" .
            "0. BACK \n" .
            "00. HOME \n";
    }

    private function showComingSoon()
    {
        return "CON Coming soon. Thank you. \n" .
            "0. BACK \n" .
            "00. HOME \n";
    }

    private function showVehicleRegistration($phoneNumber)
    {
        $vehicleDetails = "CON Select Licence Plate: \n" .
            "1. Enter Vehicle Licence Plate \n";

        $phoneNumber = $this->formatMobileNumber($phoneNumber);

        $previousVehicles = DB::table('revenue_collection.transactions')
            ->join(
                'revenue_collection.services',
                'revenue_collection.transactions.service_id',
                '=',
                'revenue_collection.services.id'
            )
            ->join(
                'revenue_collection.users',
                'revenue_collection.transactions.user_id',
                '=',
                'revenue_collection.users.id'
            )
            ->where('users.phone_number', $phoneNumber)
            ->select('account')
            ->distinct('account')
            ->orderBy('account')
            ->get();

        if (count($previousVehicles) > 0) {

            $count = 2;
            foreach ($previousVehicles as $vehicle) {

                $account = strtoupper(str_replace(' ', '', $vehicle->account));

                $vehicleDetails .= $count . ". " . $account . "\n";
                $count++;
            }
        }

        $vehicleDetails .= "0. BACK \n";
        $vehicleDetails .= "00. HOME \n";

        return $vehicleDetails;
    }

    private function enterVehicleRegistrationNumber()
    {
        return "CON Enter Vehicle License Plate";
    }

    private function getSeasonalParkingOptions()
    {

        $seasonalParkingOptions = DB::table('revenue_collection.service_categories')
            ->join(
                'revenue_collection.services',
                'revenue_collection.service_categories.service_id',
                '=',
                'revenue_collection.services.id'
            )->where('revenue_collection.services.name', 'LIKE', '%Parking%')
            ->where('revenue_collection.service_categories.name', 'LIKE', '%Seasonal%')
            ->select('revenue_collection.service_categories.id', 'revenue_collection.service_categories.name')
            ->get();

        $response = "CON Select Seasonal Parking Option: \n";

        foreach ($seasonalParkingOptions as $option) {

            $optionName = str_replace('Seasonal - ', 'Next ', $option->name);
            $response .= $option->id . ". " . $optionName . "\n";
        }

        $response .= "0. BACK \n";
        $response .= "00. HOME \n";

        return $response;
    }

    private function sendUssdResponse($ussdString)
    {
        return response($ussdString, 200)
            ->header('Content-Type', 'text/plain');
    }

    private function formatMobileNumber($number)
    {
        $regex = '/^(?:\+?(?:[1-9]{3})|0)?([1,7]([0-9]{8}))$/';
        if (preg_match_all($regex, $number, $capture)) {
            $msisdn = '254' . $capture[1][0];
        } else {
            $msisdn = false;
        }

        return $msisdn;
    }

}

?>