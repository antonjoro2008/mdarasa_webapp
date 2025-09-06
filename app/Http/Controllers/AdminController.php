<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{

    public function index()
    {

        return view('admin.index');
    }

    public function loginAdmin(Request $request)
    {

        $loginUrl = "/auth/authenticate";

        $loginPayload = [
            "email" => $request->email,
            "password" => $request->password,
        ];

        $authResponse = $this->callMDarasaAPIPostWithoutToken($loginPayload, $loginUrl);

        if (!is_null($authResponse) && $authResponse->Success) {

            $loggedInDetails = $authResponse->Data;
            Session::put("profileId", $loggedInDetails->profileId);
            Session::put("admEmail", $loggedInDetails->email);
            Session::put("admFirstName", $loggedInDetails->firstName);
            Session::put("admFullName", $loggedInDetails->firstName . " " . $loggedInDetails->lastName);
            Session::put("token", $loggedInDetails->token);

            if ($loggedInDetails->profileRoleName == "ADMIN") {

                return redirect('/admin/course-units');
            } else {
                return redirect('/');
            }

        } else {
            Session::flash('error', 'Sorry! We could not log you in. Please confirm your
             credentials and try again.');
            return redirect()->back();
        }

    }

    public function getCourseUnits()
    {

        if (!$this->webAuth()) {
            Session::flash('error', 'Please login to access this page');
            return redirect("/admin");
        }

        $courseUnitsUrl = "/course-units/all";

        $courseUnits = $this->callMDarasaAPIGetWithToken($courseUnitsUrl);

        return view('admin.course-units', compact('courseUnits'));

    }

    public function getFeaturedCourseUnits()
    {

        if (!$this->webAuth()) {
            Session::flash('error', 'Please login to access this page');
            return redirect("/admin");
        }

        $courseUnitsUrl = "/featured";

        $courseUnits = $this->callMDarasaAPIGetWithToken($courseUnitsUrl);

        return view('admin.featured-course-units', compact('courseUnits'));

    }

    public function viewProfileAccount($profileId)
    {
        if (!$this->webAuth()) {
            Session::flash('error', 'Please login to access this page');
            return redirect("/");
        }

        $profileUrl = "/profile";
        $profilePayload = [
            "profileId" => $profileId,
        ];

        $profileInfo = $this->callMDarasaAPIPostWithToken($profilePayload, $profileUrl);
        $profileInfo = $profileInfo->Data;

        $walletUrl = "/profile/balance";
        $walletInfo = $this->callMDarasaAPIPostWithToken($profilePayload, $walletUrl);
        $walletInfo = $walletInfo->Data;

        $transactionsUrl = "/profile/transactions";
        $transactions = $this->callMDarasaAPIPostWithToken($profilePayload, $transactionsUrl);
        $transactions = $transactions->Data;

        return view('admin.profile-account', compact('profileInfo', 'walletInfo',
            'transactions'));
    }

    public function updateCommissionRate(Request $request)
    {
        if (!$this->webAuth()) {
            Session::flash('error', 'Please login to access this page');
            return redirect("/");
        }

        if ($request->percentRate > 100) {
            Session::flash('error', 'Percent commission rate cannot exceed 100%');
        }

        $updateCommissionRateUrl = "/lecturer/commissionrate/set";
        $ratePayload = [
            "profileId" => $request->profileId,
            "percentRate" => $request->percentRate,
        ];

        $response = $this->callMDarasaAPIPostWithToken($ratePayload, $updateCommissionRateUrl);

        if ($response->Success) {

            Session::flash('success', "Lecturer's commission rate updated successfully");

        } else {
            Session::flash('error', 'An error occured preventing the rate from being set');
        }
        return redirect()->back();
    }

    public function unfeatureCourseUnit(Request $request)
    {
        if (!$this->webAuth()) {
            Session::flash('error', 'Please login to access this page');
            return redirect("/");
        }

        $unfeatureCourseUnitUrl = "/featured/delete";
        $payload = [
            "courseUnitId" => $request->courseUnitId,
        ];

        $response = $this->callMDarasaAPIPostWithToken($payload, $unfeatureCourseUnitUrl);

        if ($response->Success) {

            Session::flash('success', "Course unit removed from featured successfully");

        } else {
            Session::flash('error', 'Failed! We encountered an error from our end.');
        }
        return redirect()->back();
    }

    public function featureCourseUnit(Request $request)
    {
        if (!$this->webAuth()) {
            Session::flash('error', 'Please login to access this page');
            return redirect("/");
        }

        $unfeatureCourseUnitUrl = "/featured/save";
        $payload = [
            "courseUnitId" => $request->courseUnitId,
            "priority" => 1,
        ];

        $response = $this->callMDarasaAPIPostWithToken($payload, $unfeatureCourseUnitUrl);

        if ($response->Success) {

            Session::flash('success', "Course unit added to featured successfully");

        } else {
            Session::flash('error', 'Failed! We encountered an error from our end.');
        }
        return redirect()->back();
    }

    public function getLecturers()
    {
        if (!$this->webAuth()) {
            Session::flash('error', 'Please login to access this page');
            return redirect("/admin");
        }

        $lecturersUrl = "/lecturers";

        $lecturers = $this->callMDarasaAPIGetWithToken($lecturersUrl);

        return view('admin.lecturers', compact('lecturers'));

    }

    public function getStudents()
    {
        if (!$this->webAuth()) {
            Session::flash('error', 'Please login to access this page');
            return redirect("/admin");
        }

        $studentsUrl = "/students";

        $students = $this->callMDarasaAPIGetWithToken($studentsUrl);

        return view('admin.students', compact('students'));

    }

    public function getStudentOrders()
    {
        if (!$this->webAuth()) {
            Session::flash('error', 'Please login to access this page');
            return redirect("/admin");
        }

        $ordersUrl = "/student/orders";
        $orders = $this->callMDarasaAPIGetWithToken($ordersUrl);

        return view('admin.orders', compact('orders'));

    }

    public function getUsers()
    {
        if (!$this->webAuth()) {
            Session::flash('error', 'Please login to access this page');
            return redirect("/admin");
        }

        $usersUrl = "/admin/users";

        $users = $this->callMDarasaAPIGetWithToken($usersUrl);

        return view('admin.users', compact('users'));

    }

    public function addAdminUser(Request $request)
    {

        if (!$this->webAuth()) {
            Session::flash('error', 'Please login to access this page');
            return redirect("/admin");

        }

        $password = $request->password;
        $confirmPassword = $request->confirmPassword;

        if ($password != $confirmPassword) {

            Session::flash('error', 'Passwords do not match. Please correct and try again');
            return redirect()->back();

        }

        $registrationUrl = "/auth/register";

        $registrationPayload = [
            "salutation" => $request->salutation,
            "firstName" => $request->firstName,
            "lastName" => $request->lastName,
            "email" => $request->email,
            "phone" => $request->phone,
            "password" => $request->password,
            "profileRoleId" => 1,
            "courseId" => 0,
        ];

        $response = $this->callMDarasaAPIPostWithoutToken($registrationPayload, $registrationUrl);

        if (!is_null($response) && $response->Success) {

            Session::flash('success', 'User added successfully');

        } else {
            Session::flash('error', 'An error occured and registration failed. Please confirm
             details and try again.');
        }

        return redirect()->back();

    }

    public function getDeposits()
    {
        if (!$this->webAuth()) {
            Session::flash('error', 'Please login to access this page');
            return redirect("/admin");
        }

        $depositsUrl = "/deposits";
        $deposits = $this->callMDarasaAPIGetWithToken($depositsUrl);

        if (!is_null($deposits)) {

            $deposits = $deposits->Data;
        }

        return view('admin.deposits', compact('deposits'));

    }

    public function getWithdrawals()
    {
        if (!$this->webAuth()) {
            Session::flash('error', 'Please login to access this page');
            return redirect("/admin");
        }

        $withdrawalsUrl = "/withdrawals";
        $withdrawals = $this->callMDarasaAPIGetWithToken($withdrawalsUrl);
        return view('admin.withdrawals', compact('withdrawals'));

    }

    public function publishCourseUnit(Request $request)
    {
        if (!$this->webAuth()) {
            Session::flash('error', 'Please login to access this page');
            return redirect("/admin");
        }

        $publishUrl = "/course/unit/publish";

        $publishPayload = [
            "courseUnitId" => $request->courseUnitId,
        ];

        $response = $this->callMDarasaAPIPostWithToken($publishPayload, $publishUrl);

        if (!is_null($response) && $response->Success) {

            Session::flash('success', 'Course unit published successfully');

        } else {
            Session::flash('error', 'An error occured and publishing failed.
             Please and try again.');
        }
        return redirect()->back();
    }

    public function unPublishCourseUnit(Request $request)
    {
        if (!$this->webAuth()) {
            Session::flash('error', 'Please login to access this page');
            return redirect("/admin");
        }

        $unPublishUrl = "/course/unit/unpublish";

        $unPublishPayload = [
            "courseUnitId" => $request->courseUnitId,
        ];

        $response = $this->callMDarasaAPIPostWithToken($unPublishPayload, $unPublishUrl);

        if (!is_null($response) && $response->Success) {

            Session::flash('success', 'Course unit unpublished successfully');

        } else {
            Session::flash('error', 'An error occured and unpublishing failed.
             Please and try again.');
        }
        return redirect()->back();
    }

    public function getCourseUnit($courseUnitId)
    {
        if (!$this->webAuth()) {
            Session::flash('error', 'Please login to access this page');
            return redirect("/");
        }

        $courseUnitUrl = "/course/unit";

        $courseUnitPayload = [
            "courseUnitId" => $courseUnitId,
        ];

        $courseUnitTopicsUrl = "/course-unit/topics/find";

        $courseUnit = $this->callMDarasaAPIPostWithToken($courseUnitPayload, $courseUnitUrl);
        $courseUnitTopics = $this->callMDarasaAPIPostWithToken($courseUnitPayload, $courseUnitTopicsUrl);

        if (!is_null($courseUnit)) {

            $courseUnit = $courseUnit->Data;
        }

        if (!is_null($courseUnitTopics)) {

            $courseUnitTopics = $courseUnitTopics->Data;
            Session::put("courseUnitTopics", $courseUnitTopics);
        }

        return view('admin.admin-course-unit', compact('courseUnit', 'courseUnitTopics'));

    }

    public function viewTopicNotes(Request $request)
    {

        if (!$this->webAuth()) {
            Session::flash('error', 'Please login to access this page');
            return redirect("/");
        }

        $subtopicsUrl = "/topic/subtopics/find";

        $subtopicsPayload = [
            "courseUnitTopicId" => $request->topicId,
        ];

        $subtopics = $this->callMDarasaAPIPostWithToken($subtopicsPayload, $subtopicsUrl);

        if (!is_null($subtopics)) {
            $subtopics = $subtopics->Data;
            Session::put("subtopics", $subtopics);
        } else {
            $subtopics = [];
        }

        $neighborTopicUrl = "/topic/neighbor";

        $neighborPayload = [
            "unitTopicId" => $request->topicId,
        ];

        $neighbors = $this->callMDarasaAPIPostWithToken($neighborPayload, $neighborTopicUrl);

        if (!is_null($neighbors)) {
            $neighbors = $neighbors->Data;
        }

        return view('admin.topic-notes-details', [
            "courseUnitId" => $request->courseUnitId,
            "topicName" => $request->topicName,
            "topicNumber" => $request->topicNumber,
            "topicNotes" => $request->topicNotes,
            "serverUrl" => $this->getApiServerUrl(),
            "courseUnitTopics" => Session::get("courseUnitTopics"),
            "nextTopic" => !is_null($neighbors) ? $neighbors->nextTopic : null,
            "previousTopic" => !is_null($neighbors) ? $neighbors->previousTopic : null,
            "subtopics" => $subtopics,
        ]);
    }

    public function viewSubtopicNotes(Request $request)
    {

        if (!$this->webAuth()) {
            Session::flash('error', 'Please login to access this page');
            return redirect("/");
        }

        $topicUrl = "/course-unit/topic";

        $topicPayload = [
            "courseUnitTopicId" => $request->topicId,
        ];

        $selectedTopic = $this->callMDarasaAPIPostWithToken($topicPayload, $topicUrl);

        if (!is_null($selectedTopic)) {

            $selectedTopic = $selectedTopic->Data;
        }

        $subtopicsPayload = [
            "courseUnitTopicId" => $request->topicId,
        ];

        $subtopicsUrl = "/topic/subtopics/find";
        $subtopics = $this->callMDarasaAPIPostWithToken($subtopicsPayload, $subtopicsUrl);

        if (!is_null($subtopics)) {
            $subtopics = $subtopics->Data;
        } else {
            $subtopics = [];
        }

        $subtopicIdPayload = [
            "courseUnitSubtopicId" => $request->subtopicId,
        ];

        $notesUrl = "/subtopic/notes";
        $notes = $this->callMDarasaAPIPostWithToken($subtopicIdPayload, $notesUrl);

        if (!is_null($notes)) {
            $notes = $notes->Data;
        } else {
            $notes = [];
        }

        return view('admin.subtopic-notes-details', [
            "courseUnitId" => $request->courseUnitId,
            "subtopicName" => $request->subtopicName,
            "subtopicNumber" => $request->subtopicNumber,
            "selectedTopic" => $selectedTopic,
            "notes" => $notes,
            "serverUrl" => $this->getApiServerUrl(),
            "courseUnitTopics" => Session::get("courseUnitTopics"),
            "subtopics" => $subtopics,
        ]);
    }

    public function launchTopicVideo(Request $request)
    {

        if (!$this->webAuth()) {
            Session::flash('error', 'Please login to access this page');
            return redirect("/");
        }

        $subtopicsUrl = "/topic/subtopics/find";

        $subtopicsPayload = [
            "courseUnitTopicId" => $request->topicId,
        ];

        $subtopics = $this->callMDarasaAPIPostWithToken($subtopicsPayload, $subtopicsUrl);

        if (!is_null($subtopics)) {
            $subtopics = $subtopics->Data;
            Session::put("subtopics", $subtopics);
        } else {
            $subtopics = [];
        }

        $neighborTopicUrl = "/topic/neighbor";

        $neighborPayload = [
            "unitTopicId" => $request->topicId,
        ];

        $neighbors = $this->callMDarasaAPIPostWithToken($neighborPayload, $neighborTopicUrl);

        if (!is_null($neighbors)) {
            $neighbors = $neighbors->Data;
        }

        return view('admin.topic-video-details', [
            "courseUnitId" => $request->courseUnitId,
            "topicName" => $request->topicName,
            "topicNumber" => $request->topicNumber,
            "topicVideo" => $request->topicVideo,
            "serverUrl" => $this->getApiServerUrl(),
            "courseUnitTopics" => Session::get("courseUnitTopics"),
            "nextTopic" => !is_null($neighbors) ? $neighbors->nextTopic : null,
            "previousTopic" => !is_null($neighbors) ? $neighbors->previousTopic : null,
            "subtopics" => $subtopics,
        ]);

    }

    public function viewSubtopicVideo(Request $request)
    {

        if (!$this->webAuth()) {
            Session::flash('error', 'Please login to access this page');
            return redirect("/");
        }

        $topicUrl = "/course-unit/topic";

        $topicPayload = [
            "courseUnitTopicId" => $request->topicId,
        ];

        $selectedTopic = $this->callMDarasaAPIPostWithToken($topicPayload, $topicUrl);

        if (!is_null($selectedTopic)) {

            $selectedTopic = $selectedTopic->Data;
        }

        $subtopicsPayload = [
            "courseUnitTopicId" => $request->topicId,
        ];

        $subtopicsUrl = "/topic/subtopics/find";
        $subtopics = $this->callMDarasaAPIPostWithToken($subtopicsPayload, $subtopicsUrl);

        if (!is_null($subtopics)) {
            $subtopics = $subtopics->Data;
        } else {
            $subtopics = [];
        }

        $subtopicIdPayload = [
            "courseUnitSubtopicId" => $request->subtopicId,
        ];

        $videosUrl = "/subtopic/videos";
        $videos = $this->callMDarasaAPIPostWithToken($subtopicIdPayload, $videosUrl);

        if (!is_null($videos)) {
            $videos = $videos->Data;
        } else {
            $videos = [];
        }

        return view('admin.subtopic-video-details', [
            "courseUnitId" => $request->courseUnitId,
            "subtopicName" => $request->subtopicName,
            "subtopicNumber" => $request->subtopicNumber,
            "selectedTopic" => $selectedTopic,
            "videos" => $videos,
            "serverUrl" => $this->getApiServerUrl(),
            "courseUnitTopics" => Session::get("courseUnitTopics"),
            "subtopics" => $subtopics,
        ]);
    }
}
