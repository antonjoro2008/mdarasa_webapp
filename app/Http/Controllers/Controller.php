<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Session;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    const API_BASE_URL = "https://mdarasa.com:8443/api/v1";
    // const API_BASE_URL = "http://localhost:8080/api/v1";
    const SERVER_URL = "https://mdarasa.com:8443";
    // const SERVER_URL = "http://localhost:8080";

    // const UPLOADS_DIR = "C:/xampp/htdocs/mdarasa-webapp/public/uploads";
    const UPLOADS_DIR = "/var/www/html/mdarasa-webapp/public/uploads";

    protected function getApiServerUrl()
    {
        return self::SERVER_URL;
    }

    protected function getVideosUploadsDirectory()
    {
        return self::UPLOADS_DIR;
    }

    protected function callMDarasaAPIPostWithToken($data, $url)
    {
        $encodedData = json_encode($data);

        $httpRequest = curl_init(self::API_BASE_URL . $url);

        curl_setopt($httpRequest, CURLOPT_POST, true);
        curl_setopt($httpRequest, CURLOPT_POSTFIELDS, $encodedData);
        curl_setopt($httpRequest, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($httpRequest, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt(
            $httpRequest,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Authorization: Bearer' . Session::get('token')
            )
        );
        curl_setopt($httpRequest, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($httpRequest, CURLOPT_SSL_VERIFYPEER, false);
        $results = curl_exec($httpRequest);
        $decodedResults = json_decode($results);
        curl_close($httpRequest);

        return $decodedResults;

    }

    protected function callAllisterAPIPostWithParameterToken($data, $url, $token)
    {
        $encodedData = json_encode($data);

        $httpRequest = curl_init($url);

        curl_setopt($httpRequest, CURLOPT_POST, true);
        curl_setopt($httpRequest, CURLOPT_POSTFIELDS, $encodedData);
        curl_setopt($httpRequest, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($httpRequest, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt(
            $httpRequest,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $token
            )
        );
        curl_setopt($httpRequest, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($httpRequest, CURLOPT_SSL_VERIFYPEER, false);
        $results = curl_exec($httpRequest);
        $decodedResults = json_decode($results);
        curl_close($httpRequest);

        return $decodedResults;

    }

    protected function callMDarasaAPIPostWithoutToken($data, $url)
    {
        $encodedData = json_encode($data);

        $httpRequest = curl_init(self::API_BASE_URL . $url);

        curl_setopt($httpRequest, CURLOPT_POST, true);
        curl_setopt($httpRequest, CURLOPT_POSTFIELDS, $encodedData);
        curl_setopt($httpRequest, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($httpRequest, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($httpRequest, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($httpRequest, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($httpRequest, CURLOPT_SSL_VERIFYPEER, false);
        $results = curl_exec($httpRequest);
        $decodedResults = json_decode($results);
        curl_close($httpRequest);

        return $decodedResults;
    }

    protected function callAPIPost($data, $url)
    {

        $httpRequest = curl_init(self::API_BASE_URL . $url);

        curl_setopt($httpRequest, CURLOPT_POST, true);
        curl_setopt($httpRequest, CURLOPT_POSTFIELDS, $data);
        curl_setopt($httpRequest, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($httpRequest, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($httpRequest, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($httpRequest, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($httpRequest, CURLOPT_SSL_VERIFYPEER, false);
        $results = curl_exec($httpRequest);
        $decodedResults = json_decode($results);
        curl_close($httpRequest);

        return $decodedResults;
    }

    public function callCBCEndpoint($data, $url)
    {

        $httpRequest = curl_init($url);

        curl_setopt($httpRequest, CURLOPT_POST, true);
        curl_setopt($httpRequest, CURLOPT_POSTFIELDS, $data);
        curl_setopt($httpRequest, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($httpRequest, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($httpRequest, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($httpRequest, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($httpRequest, CURLOPT_SSL_VERIFYPEER, false);
        $results = curl_exec($httpRequest);
        $decodedResults = json_decode($results);
        curl_close($httpRequest);

        return $decodedResults;
    }

    protected function callAllisterAPIPost($data, $url)
    {

        $encodedData = json_encode($data);
        $httpRequest = curl_init($url);

        curl_setopt($httpRequest, CURLOPT_POST, true);
        curl_setopt($httpRequest, CURLOPT_POSTFIELDS, $encodedData);
        curl_setopt($httpRequest, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($httpRequest, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($httpRequest, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($httpRequest, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($httpRequest, CURLOPT_SSL_VERIFYPEER, false);
        $results = curl_exec($httpRequest);
        $decodedResults = json_decode($results);
        curl_close($httpRequest);

        return $decodedResults;
    }

    protected function callRevenueAPIPostWithoutToken($data, $url)
    {
        $encodedData = json_encode($data);

        $httpRequest = curl_init($url);

        curl_setopt($httpRequest, CURLOPT_POST, true);
        curl_setopt($httpRequest, CURLOPT_POSTFIELDS, $encodedData);
        curl_setopt($httpRequest, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($httpRequest, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($httpRequest, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($httpRequest, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($httpRequest, CURLOPT_SSL_VERIFYPEER, false);
        $results = curl_exec($httpRequest);
        $decodedResults = json_decode($results);
        curl_close($httpRequest);

        return $decodedResults;
    }

    protected function callMDarasaAPIGetWithToken($url)
    {
        $httpRequest = curl_init(self::API_BASE_URL . $url);

        curl_setopt($httpRequest, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($httpRequest, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($httpRequest, CURLOPT_HTTPHEADER, array('Authorization: Bearer' . Session::get('token')));
        curl_setopt($httpRequest, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($httpRequest, CURLOPT_SSL_VERIFYPEER, false);
        $results = curl_exec($httpRequest);
        $decodedResults = json_decode($results);
        curl_close($httpRequest);

        return $decodedResults;

    }

    protected function callMDarasaAPIGetWithoutToken($url)
    {
        $httpRequest = curl_init(self::API_BASE_URL . $url);
        curl_setopt($httpRequest, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($httpRequest, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($httpRequest, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($httpRequest, CURLOPT_SSL_VERIFYPEER, false);
        $results = curl_exec($httpRequest);
        $decodedResults = json_decode($results);
        curl_close($httpRequest);

        return $decodedResults;

    }

    protected function callMDarasaAPIPostFormDataWithToken($data, $url)
    {

        $httpRequest = curl_init(self::API_BASE_URL . $url);

        curl_setopt($httpRequest, CURLOPT_POST, true);
        curl_setopt($httpRequest, CURLOPT_POSTFIELDS, $data);
        curl_setopt($httpRequest, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($httpRequest, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt(
            $httpRequest,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: multipart/form-data',
                'Authorization: Bearer' . Session::get('token')
            )
        );
        curl_setopt($httpRequest, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($httpRequest, CURLOPT_SSL_VERIFYPEER, false);
        $results = curl_exec($httpRequest);
        $decodedResults = json_decode($results);
        curl_close($httpRequest);

        return $decodedResults;

    }

    protected function uploadFileToBucket($data, $url)
    {

        $httpRequest = curl_init($url);

        curl_setopt($httpRequest, CURLOPT_POST, true);
        curl_setopt($httpRequest, CURLOPT_POSTFIELDS, $data);
        curl_setopt($httpRequest, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($httpRequest, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($httpRequest, CURLOPT_HTTPHEADER, array('Content-Type: multipart/form-data'));
        curl_setopt($httpRequest, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($httpRequest, CURLOPT_SSL_VERIFYPEER, false);
        $results = curl_exec($httpRequest);
        $decodedResults = json_decode($results);
        curl_close($httpRequest);

        return $decodedResults;

    }

    protected function webAuth()
    {
        return !is_null(Session::get("token")) &&
            strlen(Session::get("token")) != 0 &&
            !is_null(Session::get("profileId"));
    }
}