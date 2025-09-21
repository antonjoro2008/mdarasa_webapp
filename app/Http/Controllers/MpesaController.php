<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;

class MpesaController extends Controller
{
    const MDARASA_OTHER_ENDPOINT = "https://apis.mdarasa.com/api/v1/payments/transactions-callback";
    const MDARASA_OTHER_ACCESS_TOKEN_ENDPOINT = "https://apis.mdarasa.com/oauth/v1/generate/access-token";
    const MDARASA_OTHER_CONSUMER_KEY = "ck_4wYaE2imMD8";
    const MDARASA_OTHER_CONSUMER_SECRET = "b4e40b5c65fe7a85ad4bdc5abd33d348a43553f0631bed44c42423fe7ef37f94";
    const CBC_ENDPOINT = "https://admin.skillszone.africa/api/payments/mpesa";
    public function mpesaC2BConfirm()
    {

        $postData = file_get_contents('php://input');

        $file = fopen("/var/www/html/mdarasa-webapp/storage/logs/mpesa.log", "a");

        if (fwrite($file, $postData) === false) {
            fwrite($file, "Error: no data written");
        }

        fwrite($file, "\r\n");
        fclose($file);

        if ($postData != null) {

            $decoded = json_decode($postData);

            if (isset($decoded->Body)) {
                return;
            }

            $transactionType = $decoded->TransactionType;
            $transactionId = $decoded->TransID;
            $transactionTime = $decoded->TransTime;
            $transactionAmount = $decoded->TransAmount;
            $businessCode = $decoded->BusinessShortCode;
            $billRefNo = $decoded->BillRefNumber;
            $orgAccountBalance = $decoded->OrgAccountBalance;
            $thirdPartyTransId = $decoded->ThirdPartyTransID;
            $msisdn = $decoded->MSISDN;
            $firstName = $decoded->FirstName;

            if ($transactionAmount < 1) {
                return;
            }

            $prefix = substr($billRefNo, 0, 3);
            Log::info("The prefix here is: $prefix");

            if ($prefix == "MDR") {

                $phone = $this->formatMobileNumber($msisdn);

                $payload = [

                    "transaction_id" => $transactionId,
                    "account" => $billRefNo,
                    "amount" => $transactionAmount,
                    "phone" => $phone,
                    "payload" => $postData
                ];

                Log::info("Forwarding transaction to the other server endpoint >> " . json_encode($payload));
                $allisterToken = $this->allisterAccessToken();

                Log::info("Got token from Allister $allisterToken");

                $returned = $this->callAllisterAPIPostWithParameterToken($payload, self::MDARASA_OTHER_ENDPOINT, $allisterToken);

                Log::info("Read Response from Allister " . print_r($returned, true));

                if ($returned && $returned->ResultCode == 0) {

                    return;

                } else {

                    Log::info("Failed to forward the transaction to the other server endpoint");

                    DB::table('mpesa_transaction')->insert([
                        'transaction_type' => $transactionType,
                        'transaction_id' => $transactionId,
                        'transaction_time' => $transactionTime,
                        'transaction_amount' => $transactionAmount,
                        'business_code' => $businessCode,
                        'bill_ref_no' => $billRefNo,
                        'org_account_balance' => $orgAccountBalance,
                        'third_party_trans_id' => $thirdPartyTransId,
                        'msisdn' => $msisdn,
                        'first_name' => $firstName,
                        'middle_name' => "",
                        'last_name' => "",
                    ]);

                    return;

                }
            } elseif ($prefix == "CBC") {

                Log::info("This is a CBC payment request");
                $phone = explode("-", $billRefNo)[1];

                $payload = [
                    "status" => "successful",
                    "transaction_id" => $transactionId,
                    "reference" => $billRefNo,
                    "amount" => $transactionAmount,
                    "phone" => $phone,
                    "transaction_date" => $transactionTime
                ];

                Log::info("Forwarding transaction to the CBC endpoint >> " . json_encode($payload));

                $returned = $this->callCBCEndpoint($payload, self::CBC_ENDPOINT);

                Log::info("Read Response from CBC " . print_r($returned, true));

                if ($returned?->success) {

                    return;

                } else {

                    Log::info("Failed to forward the transaction to the CBC endpoint");

                    DB::table('mpesa_transaction')->insert([
                        'transaction_type' => $transactionType,
                        'transaction_id' => $transactionId,
                        'transaction_time' => $transactionTime,
                        'transaction_amount' => $transactionAmount,
                        'business_code' => $businessCode,
                        'bill_ref_no' => $billRefNo,
                        'org_account_balance' => $orgAccountBalance,
                        'third_party_trans_id' => $thirdPartyTransId,
                        'msisdn' => $msisdn,
                        'first_name' => $firstName,
                        'middle_name' => "",
                        'last_name' => "",
                    ]);

                    return;

                }
            }

            DB::table('mpesa_transaction')->insert([
                'transaction_type' => $transactionType,
                'transaction_id' => $transactionId,
                'transaction_time' => $transactionTime,
                'transaction_amount' => $transactionAmount,
                'business_code' => $businessCode,
                'bill_ref_no' => $billRefNo,
                'org_account_balance' => $orgAccountBalance,
                'third_party_trans_id' => $thirdPartyTransId,
                'msisdn' => $msisdn,
                'first_name' => $firstName,
                'middle_name' => "",
                'last_name' => "",
            ]);

        }

        $checkOrderSql = "SELECT o.student_order_id, o.profile_id,
         o.total_order_value,o.voucher_amount,o.voucher_code,
          o.order_reference,o.transaction_cost,o.notes,o.payment_gateway,
          o.payment_status,o.payment_reference,p.first_name,p.last_name,
          p.email,p.phone FROM `student_order` o INNER JOIN profile p
           USING(profile_id) WHERE student_order_id = ?";

        $orderResults = DB::select($checkOrderSql, [$billRefNo]);

        $profileId = 0;
        $studentOrderId = 0;

        if (!empty($orderResults)) {

            Log::info("Transaction has an associated student order. Continuing..");

            $profileId = $orderResults[0]->profile_id;
            $studentOrderId = $orderResults[0]->student_order_id;

        } else {

            Log::info("Transaction has no associated student order. Continuing to credit account..");

            $profileSql = "SELECT p.profile_id,p.password,p.first_name,p.last_name,
                p.email,p.phone,p.status,p.profile_role_id,p.salutation,
                p.course_id,p.profile_photo, pr.profile_role_name,
                p.commission_rate FROM `profile` p INNER JOIN profile_role pr
                 USING(profile_role_id) WHERE p.phone = ?";

            $profileResults = DB::select($profileSql, [$this->formatMobileNumber($billRefNo)]);

            if (!empty($profileResults)) {

                Log::info("Found a user associated with the MSISDN. Will credit their account");

                $profileId = $profileResults[0]->profile_id;

            } else {

                Log::info("No user with the MSISDN found in our database");

            }
        }

        $runningBalanceSql = "SELECT t.transaction_id,t.profile_id,t.running_balance FROM
         `transaction` t WHERE t.profile_id = ? ORDER BY 1 DESC LIMIT 1";

        $balanceResults = DB::select($runningBalanceSql, [$profileId]);
        $runningBalance = 0;

        if (!empty($balanceResults)) {

            $runningBalance = $balanceResults[0]->running_balance;
        }

        $depositTranId = DB::table('transaction')->insertGetId([
            'profile_id' => $profileId,
            'student_order_id' => $studentOrderId,
            'is_credit' => 1,
            'amount' => $transactionAmount,
            'reference' => $profileId . "-" . $studentOrderId . "-" . time(),
            'running_balance' => $runningBalance + $transactionAmount,
            'payment_gateway' => "MPESA",
            'partner_transaction_id' => $thirdPartyTransId,
            'transaction_desc' => "CUSTOMER DEPOSIT",
            'third_party_conversation_id' => null,
            'transaction_type' => "DEPOSIT",
            'status' => "COMPLETE",
        ]);

        Log::info("Successfully created the deposit transaction");

        if ($profileId > 0) {

            DB::statement("UPDATE `profile_balance` SET `balance` = balance + $transactionAmount,
                `transaction_id` = '" . $depositTranId . "' WHERE profile_id='" . $profileId . "'");

            Log::info("Successfully credited the user account");
        } else {

            Log::info("Since no user is associated with the MSISDN, we are creating an account first");

            $profileId = DB::table('profile')->insertGetId([
                'first_name' => $firstName,
                'last_name' => null,
                'email' => $billRefNo,
                'phone' => $this->formatMobileNumber($billRefNo),
                'password' => Hash::make($billRefNo),
                'course_id' => null,
                'category_id' => null,
                'salutation' => null,
                'profile_role_id' => 2,
                'profile_summary' => null,
                'commission_rate' => null,
                'profile_type' => null,
            ]);

            Log::info("Created the account successfully");

            DB::table('profile_balance')->insertGetId([
                'profile_id' => $profileId,
                'balance' => $transactionAmount,
                'transaction_id' => $depositTranId,
            ]);

            Log::info("Created a wallet for the user and credited the account");
            return;
        }

        if (!empty($orderResults) && $orderResults[0]->total_order_value <= $transactionAmount) {

            Log::info("We have an associated student order. Proceeding to create payment transaction");

            $totalOrderValue = $orderResults[0]->total_order_value;
            $paymentTranId = DB::table('transaction')->insertGetId([
                'profile_id' => $profileId,
                'student_order_id' => $studentOrderId,
                'is_credit' => 0,
                'amount' => $totalOrderValue,
                'reference' => $profileId . "-" . $studentOrderId . "-" . time(),
                'running_balance' => ($runningBalance + $transactionAmount) - $totalOrderValue,
                'payment_gateway' => "WALLET",
                'partner_transaction_id' => null,
                'transaction_desc' => $studentOrderId . " ORDER PAYMENT",
                'third_party_conversation_id' => null,
                'transaction_type' => "DEBIT",
                'status' => "COMPLETE",
            ]);

            Log::info("Created payment transaction successfully");

            DB::statement("UPDATE `profile_balance` SET `balance` = balance - $totalOrderValue,
                `transaction_id` = '" . $paymentTranId . "' WHERE profile_id='" . $profileId . "'");

            Log::info("Debited the user account successfully to fully settle the order");

            $orderDetailsSql = "SELECT o.student_order_detail_id, o.student_order_id,
                o.course_unit_id,o.line_price, o.quantity, cu.profile_id FROM
                student_order_detail o INNER JOIN course_unit cu USING(course_unit_id)
                 WHERE o.student_order_id ='$studentOrderId'";

            $orderDetailsRs = DB::select($orderDetailsSql);

            foreach ($orderDetailsRs as $detail) {

                $linePrice = $detail->line_price;
                Log::info("Course unit has a line price of $linePrice");
                $lecturerProfileId = $detail->profile_id;

                $lecturerProfileSql = "SELECT p.profile_id,p.first_name,p.last_name,p.course_id,
                    p.category_id,p.email,p.phone,p.status,p.profile_role_id,
                    p.salutation,p.profile_photo, pr.profile_role_name,
                    p.commission_rate FROM `profile` p INNER JOIN profile_role pr
                     USING(profile_role_id) WHERE p.profile_id = ?";

                $lecturerProfileRs = DB::select($lecturerProfileSql, [$lecturerProfileId]);
                $sharePercent = $lecturerProfileRs[0]->commission_rate;

                Log::info("Read lecturer share percent of $sharePercent");
                $shareableRevenue = $linePrice * ($sharePercent / 100);

                $runningBalance = $this->getRunningBalance($lecturerProfileId);

                Log::info("Calculated the lecturer shareable revenue of $shareableRevenue");

                $lecturerTranId = DB::table('transaction')->insertGetId([
                    'profile_id' => $lecturerProfileId,
                    'student_order_id' => $studentOrderId,
                    'is_credit' => 1,
                    'amount' => $shareableRevenue,
                    'reference' => $lecturerProfileId . "-" . $studentOrderId . "-" . time(),
                    'running_balance' => $runningBalance + $shareableRevenue,
                    'payment_gateway' => "WALLET",
                    'partner_transaction_id' => null,
                    'transaction_desc' => $studentOrderId . " LECTURER PAYMENT",
                    'third_party_conversation_id' => null,
                    'transaction_type' => "CREDIT",
                    'status' => "COMPLETE",
                ]);

                Log::info("Created lecturer payment transaction successfully");

                DB::statement("UPDATE `profile_balance` SET `balance` = balance + $shareableRevenue,
                `transaction_id` = '" . $lecturerTranId . "' WHERE profile_id='" . $lecturerProfileId . "'");

            }

            Log::info("Successfully credited all lecturers' accounts for the purchased course units");

            DB::statement("UPDATE `student_order` so
                INNER JOIN `student_order_detail` sd ON so.student_order_id = sd.student_order_id
                INNER JOIN `course_unit` cu ON sd.course_unit_id = cu.course_unit_id
                SET so.payment_status = 'PAID',
                    so.payment_gateway = 'MPESA',
                    so.expires_on = CASE
                        WHEN cu.purchases_expiration_days IS NOT NULL 
                        THEN NOW() + INTERVAL cu.purchases_expiration_days DAY
                        ELSE NULL
                    END
                WHERE so.student_order_id = '$studentOrderId'");

            Log::info("Completed the student order successfully");
        }

        $profileSql = "SELECT * FROM `profile` p WHERE p.profile_id = ? LIMIT 1";
        $profileResults = DB::select($profileSql, [$profileId]);

        $referredBy = $profileResults[0]->referred_by;

        if (!is_null($referredBy)) {

            $referredBySql = "SELECT * FROM `profile` p WHERE p.referral_code = ? LIMIT 1";
            $referredByResults = DB::select($referredBySql, [$referredBy]);
            $referredById = $referredByResults[0]->profile_id;

            $referredByBalance = $this->getRunningBalance($referredById);

            $referrerSharePercent = env('REFERRAL_SHARE_PERCENT', 2.5);
            $referralAmount = $transactionAmount * ($referrerSharePercent / 100);

            $referralTranId = DB::table('transaction')->insertGetId([
                'profile_id' => $referredById,
                'student_order_id' => $studentOrderId,
                'is_credit' => 1,
                'amount' => $referralAmount,
                'reference' => $referredBy . "-" . $studentOrderId . "-" . time(),
                'running_balance' => $referredByBalance + $referralAmount,
                'payment_gateway' => "WALLET",
                'partner_transaction_id' => null,
                'transaction_desc' => $studentOrderId . " REFERRAL EARNING",
                'third_party_conversation_id' => null,
                'transaction_type' => "CREDIT",
                'status' => "COMPLETE",
            ]);

            Log::info("Created referral payment transaction successfully");

            DB::statement("UPDATE `profile_balance` SET `balance` = balance + $referralAmount,
                `transaction_id` = '" . $referralTranId . "' WHERE profile_id='" . $referredById . "'");

            Log::info("Successfully credited the referrer account");
        }

        Log::info("Mpesa Transaction logged successfully");

        echo '{"ResultCode": 0, "ResultDesc": "Accepted"}';
    }

    public function mpesaC2BValidate()
    {
        echo '{"ResultCode": 0, "ResultDesc": "Accepted"}';
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

    private function getRunningBalance($profileId)
    {

        $sql = "SELECT t.transaction_id,t.profile_id,t.running_balance FROM `transaction` t
         WHERE t.profile_id = ? ORDER BY 1 DESC LIMIT 1";

        $transRs = DB::select($sql, [$profileId]);

        if (!empty($transRs)) {

            return $transRs[0]->running_balance;
        }

        return 0;
    }

    public function revenueC2BConfirm()
    {

        $postData = file_get_contents('php://input');

        $file = fopen("/var/www/html/mdarasa-webapp/storage/logs/revenue.log", "a");

        if (fwrite($file, $postData) === false) {
            fwrite($file, "Error: no data written");
        }

        fwrite($file, "\r\n");
        fclose($file);

        if ($postData != null) {

            $decoded = json_decode($postData);

            if (isset($decoded->Body)) {
                return;
            }

            $transactionType = $decoded->TransactionType;
            $transactionId = $decoded->TransID;
            $transactionTime = $decoded->TransTime;
            $transactionAmount = $decoded->TransAmount;
            $businessCode = $decoded->BusinessShortCode;
            $billRefNo = $decoded->BillRefNumber;
            $orgAccountBalance = $decoded->OrgAccountBalance;
            $thirdPartyTransId = $decoded->ThirdPartyTransID;
            $msisdn = $decoded->MSISDN;
            $firstName = $decoded->FirstName;
            $lastName = $decoded->LastName;
            $surname = $decoded->MiddleName;

            $billRefComponents = explode("-", $billRefNo);

            $account = $billRefComponents[0];
            $serviceCategoryId = $billRefComponents[1];
            $zoneId = $billRefComponents[2];

            //replace all spaces in account with empty string and convert to uppercase

            $account = strtoupper(str_replace(" ", "", $account));

            if ($transactionAmount < 1) {
                return;
            }

            DB::table('revenue_collection.mpesa_transaction')->insert([
                'transaction_type' => $transactionType,
                'transaction_id' => $transactionId,
                'transaction_time' => $transactionTime,
                'transaction_amount' => $transactionAmount,
                'business_code' => $businessCode,
                'bill_ref_no' => $billRefNo,
                'org_account_balance' => $orgAccountBalance,
                'third_party_trans_id' => $thirdPartyTransId,
                'msisdn' => $msisdn,
                'first_name' => $firstName,
                'middle_name' => $surname,
                'last_name' => $lastName,
            ]);

        }

        $profileId = 0;

        Log::info("Continuing to credit account..");

        $profileSql = "SELECT p.id,p.password,p.first_name,p.last_name,
            p.email,p.phone_number,p.status,p.role,p.salutation
            FROM revenue_collection.users p WHERE p.phone_number = ?";

        $profileResults = DB::select($profileSql, [$this->formatMobileNumber($msisdn)]);

        if (!empty($profileResults)) {

            Log::info("Found a user associated with the MSISDN. Will credit their account");

            $profileId = $profileResults[0]->id;

        } else {

            Log::info("No user with the MSISDN found in our database");

            Log::info("Since no user is associated with the MSISDN, we are creating an account first");

            $profileId = DB::table('revenue_collection.users')->insertGetId([
                'name' => trim($firstName . " " . $lastName . " " . $surname),
                'first_name' => $firstName,
                'last_name' => $lastName,
                'surname' => $surname,
                'email' => '',
                'phone_number' => $this->formatMobileNumber($msisdn),
                'password' => Hash::make($msisdn),
                'salutation' => null,
                'role' => 'CUSTOMER'
            ]);

            Log::info("Created the account successfully");

            DB::table('revenue_collection.user_wallets')->insertGetId([
                'user_id' => $profileId,
                'balance' => $transactionAmount,
                'transaction_id' => -1,
            ]);

            Log::info("Created a wallet for the user and credited the account");

        }

        $runningBalanceSql = "SELECT t.id,t.user_id,t.running_balance
         FROM revenue_collection.transactions t
          WHERE t.user_id = ? ORDER BY 1 DESC LIMIT 1";

        $balanceResults = DB::select($runningBalanceSql, [$profileId]);
        $runningBalance = 0;

        if (!empty($balanceResults)) {

            $runningBalance = $balanceResults[0]->running_balance;
        }

        if ($profileId == 0) {

            //try again to create the user

            $profileId = DB::table('revenue_collection.users')->insertGetId([
                'name' => trim($firstName . " " . $lastName . " " . $surname),
                'first_name' => $firstName,
                'last_name' => $lastName,
                'surname' => $surname,
                'email' => '',
                'phone_number' => $this->formatMobileNumber($msisdn),
                'password' => Hash::make($msisdn),
                'salutation' => null,
                'role' => 'CUSTOMER'
            ]);
        }

        $depositTranId = DB::table('revenue_collection.transactions')->insertGetId([
            'user_id' => $profileId,
            'service_id' => 1,
            'is_credit' => 1,
            'account' => $account,
            'service_category_id' => $serviceCategoryId,
            'zone_id' => $zoneId,
            'amount' => $transactionAmount,
            'reference' => $profileId . "-" . time(),
            'running_balance' => $runningBalance + $transactionAmount,
            'payment_gateway' => "MPESA",
            'partner_transaction_id' => $thirdPartyTransId,
            'transaction_desc' => "CUSTOMER DEPOSIT",
            'third_party_conversation_id' => null,
            'transaction_type' => "DEPOSIT",
            'status' => "COMPLETE",
        ]);

        Log::info("Successfully created the deposit transaction");

        if ($profileId > 0) {

            DB::statement("UPDATE revenue_collection.user_wallets
                 SET `balance` = balance + $transactionAmount,
                `transaction_id` = '" . $depositTranId . "'
                 WHERE user_id='" . $profileId . "'");

            Log::info("Successfully credited the user account");
        }

        Log::info("Proceeding to create payment transaction");

        $paymentTranId = DB::table('revenue_collection.transactions')->insertGetId([
            'user_id' => $profileId,
            'service_id' => 1,
            'is_credit' => 0,
            'account' => $account,
            'service_category_id' => $serviceCategoryId,
            'zone_id' => $zoneId,
            'amount' => $transactionAmount,
            'reference' => $profileId . "-" . time(),
            'running_balance' => ($runningBalance + $transactionAmount) - $transactionAmount,
            'payment_gateway' => "WALLET",
            'partner_transaction_id' => null,
            'transaction_desc' => "SERVICE PAYMENT",
            'third_party_conversation_id' => null,
            'transaction_type' => "DEBIT",
            'status' => "COMPLETE",
        ]);

        Log::info("Created payment transaction successfully");

        if ($profileId > 0) {

            DB::statement("UPDATE revenue_collection.user_wallets SET
                `balance` = balance - $transactionAmount,
                `transaction_id` = '" . $paymentTranId . "'
                WHERE user_id='" . $profileId . "'");
        }

        Log::info("Debited the user account successfully to fully settle the cost");

        Log::info("Mpesa Transaction logged successfully");

        echo '{"ResultCode": 0, "ResultDesc": "Accepted"}';
    }

    public function revenueC2BValidate()
    {
        echo '{"ResultCode": 0, "ResultDesc": "Accepted"}';
    }

    public function lipaNaMPESAOnlineSTK(Request $request)
    {

        $account = $request->account;
        $amount = $request->depositAmt;
        $msisdn = $request->msisdn;

        $shortCode = "731029";
        $callBack = "https://mdarasa.com/revenue/c2b/paymentengine";

        $accessTokenResponse = $this->accessToken();
        $access_token = "";
        if (!is_null($accessTokenResponse)) {
            $access_token = $accessTokenResponse['access_token'];
        }

        $url = 'https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest';

        $timestamp = date('YmdHis');
        $password = $this->mpesaGenerateSTKPassword($timestamp);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt(
            $curl,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type:application/json',
                "Authorization: Bearer $access_token"
            )
        );

        $curl_post_data = array(

            'BusinessShortCode' => "731029",
            'Password' => $password,
            'Timestamp' => $timestamp,
            'TransactionType' => 'CustomerPayBillOnline',
            'Amount' => $amount,
            'PartyA' => $this->formatMobileNumber($msisdn),
            'PartyB' => $shortCode,
            'PhoneNumber' => $this->formatMobileNumber($msisdn),
            'CallBackURL' => $callBack,
            'AccountReference' => $account,
            'TransactionDesc' => 'Customer Payment Online',
        );

        $data_string = json_encode($curl_post_data);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

        $curl_response = curl_exec($curl);

        Log::info("Read Response from MPESA " . print_r($curl_response, true));

        $file = fopen("/var/www/html/mdarasa-webapp/storage/logs/revenue.log", "a");

        if (fwrite($file, print_r($curl_response, true)) === false) {
            fwrite($file, "Error: no data written");
        }

        fwrite($file, "\r\n");
        fclose($file);

        $response = array(
            'Success' => true,
            'message' => 'Payment request sent successfully',
        );

        return json_encode($response);
    }

    public function iluC2BConfirm()
    {

        $postData = file_get_contents('php://input');

        $file = fopen("/var/www/html/mdarasa-webapp/storage/logs/revenue.log", "a");

        if (fwrite($file, $postData) === false) {
            fwrite($file, "Error: no data written");
        }

        fwrite($file, "\r\n");
        fclose($file);

        if ($postData != null) {

            Log::info("Found some payload data from Safaricom MPESA. Preparing to send");

            $url = 'http://ilu.smartsoko.com/api/process_ipn';
            $this->callRevenueAPIPostWithoutToken($postData, $url);

        }

        Log::info("Mpesa Transaction sent to Titus successfully");

        echo '{"ResultCode": 0, "ResultDesc": "Accepted"}';
    }

    public function iluC2BValidate()
    {
        echo '{"ResultCode": 0, "ResultDesc": "Accepted"}';
    }

    public function iluLipaNaMPESAOnlineSTK(Request $request)
    {

        // $account = $request->account;
        // $amount = $request->depositAmt;
        // $msisdn = $request->msisdn;

        // $shortCode = "519400";
        // $callBack = "https://mdarasa.com/ilu/c2b/paymentengine";

        // $accessTokenResponse = $this->accessToken();
        // $access_token = "";
        // if (!is_null($accessTokenResponse)) {
        //     $access_token = $accessTokenResponse['access_token'];
        // }

        // $url = 'https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest';

        // $timestamp = date('YmdHis');
        // $password = $this->mpesaGenerateSTKPassword($timestamp);

        // $curl = curl_init();
        // curl_setopt($curl, CURLOPT_URL, $url);
        // curl_setopt(
        //     $curl,
        //     CURLOPT_HTTPHEADER,
        //     array(
        //         'Content-Type:application/json',
        //         'Authorization: Bearer ' . $access_token
        //     )
        // );

        // $curl_post_data = array(

        //     'BusinessShortCode' => "731029",
        //     'Password' => $password,
        //     'Timestamp' => $timestamp,
        //     'TransactionType' => 'CustomerPayBillOnline',
        //     'Amount' => $amount,
        //     'PartyA' => $this->formatMobileNumber($msisdn),
        //     'PartyB' => $shortCode,
        //     'PhoneNumber' => $this->formatMobileNumber($msisdn),
        //     'CallBackURL' => $callBack,
        //     'AccountReference' => $account,
        //     'TransactionDesc' => 'Customer Payment Online',
        // );

        // $data_string = json_encode($curl_post_data);

        // curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($curl, CURLOPT_POST, true);
        // curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

        // $curl_response = curl_exec($curl);

        // Log::info("Read Response from MPESA " . print_r($curl_response, true));

        // $file = fopen("/var/www/html/mdarasa-webapp/storage/logs/revenue.log", "a");

        // if (fwrite($file, print_r($curl_response, true)) === false) {
        //     fwrite($file, "Error: no data written");
        // }

        // fwrite($file, "\r\n");
        // fclose($file);

        // $response = array(
        //     'Success' => true,
        //     'message' => 'Payment request sent successfully',
        // );

        // return json_encode($response);

        return true;
    }

    private function accessToken()
    {

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "https://api.safaricom.co.ke/oauth/v2/generate?grant_type=client_credentials");
        $credentials = base64_encode('SVH66qBAtH6bKGibhZAWIBjxttt6MGTw:QCoK5cpdAPzsf7Bi');

        curl_setopt(
            $curl,
            CURLOPT_HTTPHEADER,
            array('Authorization: Basic ' . $credentials)
        );
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $curl_response = curl_exec($curl);

        Log::info("Read Response from MPESA - Access Token " . print_r($curl_response, true));
        return json_decode($curl_response, true);
    }

    private function allisterAccessToken()
    {

        $payload = [
            "grant_type" => "access_token",
            "consumer_key" => self::MDARASA_OTHER_CONSUMER_KEY,
            "consumer_secret" => self::MDARASA_OTHER_CONSUMER_SECRET
        ];

        Log::info("Sending request to Allister for Access Token " . print_r($payload, true));

        $response = $this->callAllisterAPIPost($payload, self::MDARASA_OTHER_ACCESS_TOKEN_ENDPOINT);

        Log::info("Read Response from Allister - Access Token " . print_r($response, true));

        if ($response && isset($response->access_token)) {
            return $response->access_token;
        } else {
            return null;
        }
    }

    private function mpesaGenerateSTKPassword($timestamp)
    {

        $shortCode = "731029";
        return base64_encode("{$shortCode}bc9a331469b31711b216c1238810b8e0489c8f27280f74310f080aa19530c2d8$timestamp");
    }

}