<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{

    public function registerStudent(Request $request)
    {
        $registrationUrl = "/auth/register";

        $password = $request->password;
        $confirmPassword = $request->confirmPassword;

        if ($password != $confirmPassword) {

            Session::flash('error', 'Passwords do not match. Please correct and try again');
            return redirect()->back();

        }

        $registrationPayload = [
            "profileType" => $request->profileType,
            "salutation" => $request->salutation,
            "firstName" => $request->firstName,
            "lastName" => $request->lastName,
            "email" => $request->email,
            "phone" => $request->msisdn,
            "password" => $request->password,
            "profileRoleId" => 2,
            "referredBy" => $request->referredBy,
            "courseId" => 0,
            "courseName" => "",
            "profileSummary" => "",
        ];

        $response = $this->callMDarasaAPIPostWithoutToken($registrationPayload, $registrationUrl);

        if (!is_null($response) && $response->Success) {

            $loggedInDetails = $response->Data;
            Session::put("profileId", $loggedInDetails->profileId);
            Session::put("email", $loggedInDetails->email);
            Session::put("firstName", $loggedInDetails->firstName);
            Session::put("msisdn", $loggedInDetails->phone);
            Session::put("fullName", $loggedInDetails->firstName . " " . $loggedInDetails->lastName);
            Session::put("token", $loggedInDetails->token);
            Session::put("courseId", $loggedInDetails->courseId);
            Session::flash('success', 'Congratulations! Welcome to MDARASA.');

        } elseif (!is_null($response) && $response->Code == 2) {

            Session::flash('error', 'The mobile number specified is already registed. Please use a different one.');
        } else {
            Session::flash('error', 'Sorry! We could not register your account. Please confirm your details and try again.');
        }

        return redirect()->back();
    }

    public function registerLecturer(Request $request)
    {
        $registrationUrl = "/auth/register";

        $password = $request->password;
        $confirmPassword = $request->confirmPassword;

        if ($password != $confirmPassword) {

            Session::flash('error', 'Passwords do not match. Please correct and try again');
            return redirect()->back();

        }

        $registrationPayload = [
            "profileType" => null,
            "salutation" => $request->salutation,
            "firstName" => $request->firstName,
            "lastName" => $request->lastName,
            "email" => $request->email,
            "phone" => $request->msisdn,
            "password" => $request->password,
            "profileRoleId" => 3,
            "categoryId" => null,
            "courseName" => null,
            "profileSummary" => $request->profileSummary,
        ];

        $response = $this->callMDarasaAPIPostWithoutToken($registrationPayload, $registrationUrl);

        if (!is_null($response) && $response->Success) {

            $loggedInDetails = $response->Data;
            Session::put("profileId", $loggedInDetails->profileId);
            Session::put("email", $loggedInDetails->email);
            Session::put("msisdn", $loggedInDetails->phone);
            Session::put("firstName", $loggedInDetails->firstName);
            Session::put("fullName", $loggedInDetails->firstName . " " . $loggedInDetails->lastName);
            Session::put("token", $loggedInDetails->token);
            Session::put("courseId", $loggedInDetails->courseId);
            Session::flash('success', 'Congratulations! Welcome to MDARASA.');

            return redirect("/lecturer");

        } elseif (!is_null($response) && $response->Code == 2) {

            Session::flash('error', 'The mobile number specified is already registed. Please use a different one.');
        } else {
            Session::flash('error', 'Sorry! We could not register your account. Please confirm your details and try again.');
            return redirect()->back();
        }
    }

    public function registerInstitution(Request $request)
    {
        $registrationUrl = "/auth/register";

        $password = $request->password;
        $confirmPassword = $request->confirmPassword;

        if ($password != $confirmPassword) {

            Session::flash('error', 'Passwords do not match. Please correct and try again');
            return redirect()->back();

        }

        $registrationPayload = [
            "profileType" => null,
            "salutation" => "Inst",
            "firstName" => $request->institutionName,
            "lastName" => "",
            "email" => $request->email,
            "phone" => $request->msisdn,
            "password" => $request->password,
            "profileRoleId" => 3,
            "categoryId" => null,
            "courseName" => null,
            "profileSummary" => $request->profileSummary,
        ];

        $response = $this->callMDarasaAPIPostWithoutToken($registrationPayload, $registrationUrl);

        if (!is_null($response) && $response->Success) {

            $loggedInDetails = $response->Data;
            Session::put("profileId", $loggedInDetails->profileId);
            Session::put("email", $loggedInDetails->email);
            Session::put("msisdn", $loggedInDetails->phone);
            Session::put("firstName", $loggedInDetails->firstName);
            Session::put("fullName", $loggedInDetails->firstName);
            Session::put("token", $loggedInDetails->token);
            Session::put("courseId", $loggedInDetails->courseId);
            Session::flash('success', 'Congratulations! Welcome to MDARASA.');

            return redirect("/lecturer");

        } elseif (!is_null($response) && $response->Code == 2) {

            Session::flash('error', 'The mobile number specified is already registed. Please use a different one.');
        } else {
            Session::flash('error', 'Sorry! We could not register your account. Please confirm your
             details and try again.');
            return redirect()->back();
        }
    }

    public function login(Request $request)
    {

        $loginUrl = "/auth/authenticate";

        $loginPayload = [
            "email" => $request->email,
            "password" => $request->password,
        ];

        $signInAsLecturer = $request->signInAsLecturer;

        $authResponse = $this->callMDarasaAPIPostWithoutToken($loginPayload, $loginUrl);

        if (!is_null($authResponse) && $authResponse->Success) {

            $loggedInDetails = $authResponse->Data;
            Session::put("profileId", $loggedInDetails->profileId);
            Session::put("email", $loggedInDetails->email);
            Session::put("firstName", $loggedInDetails->firstName);
            Session::put("msisdn", $loggedInDetails->phone);
            Session::put("fullName", $loggedInDetails->firstName . " " . $loggedInDetails->lastName);
            Session::put("token", $loggedInDetails->token);
            Session::put("courseId", $loggedInDetails->courseId);
            Session::put("role", $loggedInDetails->profileRoleName);
            Session::put("profilePhoto", $loggedInDetails->profilePhoto);
            Session::put("referralCode", $loggedInDetails->referralCode);
            Session::put("referredBy", $loggedInDetails->referredBy);

            if ($signInAsLecturer == "on" && $loggedInDetails->profileRoleName == "LECTURER") {

                return redirect('/lecturer');
            }

            if ($loggedInDetails->profileRoleName == "LECTURER") {

                return redirect('/lecturer');
            }

        } else {
            Session::flash('error', 'Sorry! We could not log you in. Please confirm your credentials and try again.');
            return redirect()->back();
        }

        return redirect()->back();
    }

    public function forgotPassword(Request $request)
    {

        $sendTokenUrl = "/token";

        $tokenPayload = [
            "email" => $request->email,
        ];

        $tokenResponse = $this->callMDarasaAPIPostWithoutToken($tokenPayload, $sendTokenUrl);

        if (!is_null($tokenResponse) && $tokenResponse->Success) {

            Session::flash('success', 'We have sent an email on how to reset your password. Please check.');
            return redirect()->back();

        } else {
            Session::flash('error', 'We encountered a problem sending you a reset token. Please contact us');
            return redirect()->back();
        }
    }

    public function logout()
    {
        Auth::logout();
        Session::forget("profileId");
        Session::forget("email");
        Session::forget("firstName");
        Session::forget("fullName");
        Session::forget("token");
        Session::forget("courseId");
        Session::forget("admProfileId");
        Session::forget("admEmail");
        Session::forget("admFirstName");
        Session::forget("admFullName");
        return redirect('/');
    }
}