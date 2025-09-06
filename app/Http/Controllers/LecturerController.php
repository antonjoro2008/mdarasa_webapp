<?php

namespace App\Http\Controllers;

use CURLFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LecturerController extends Controller
{
    public function index()
    {
        if (!$this->webAuth()) {
            Session::flash('error', 'Please login to access this page');
            return redirect("/");
        }

        $categoriesUrl = "/categories";
        $categories = $this->callMDarasaAPIGetWithoutToken($categoriesUrl);

        $subCategoriesUrl = "/subcategories/all";
        $subCategories = $this->callMDarasaAPIGetWithoutToken($subCategoriesUrl);

        $coursesUrl = "/profile/courses";
        $profilePayload = [
            "profileId" => Session::get("profileId"),
        ];

        $courses = $this->callMDarasaAPIPostWithToken($profilePayload, $coursesUrl);
        if ($courses != null) {

            $courses = $courses->Data;
        }

        $course = null;

        return view('lecturer.courses', compact('categories', 'courses', 'course', 'subCategories'));
    }

    public function lecturerUnits($courseId = null)
    {
        if (!$this->webAuth()) {
            Session::flash('error', 'Please login to access this page');
            return redirect("/");
        }

        $courses = [];
        $coursesUrl = "/profile/courses";
        $profilePayload = [
            "profileId" => Session::get("profileId"),
        ];

        $courses = $this->callMDarasaAPIPostWithToken($profilePayload, $coursesUrl);
        if ($courses != null) {

            $courses = $courses->Data;
        }

        $courseUnitsUrl = "/course-units/lecturer/all";
        $profilePayload = [
            "profileId" => Session::get("profileId"),
        ];

        $courseUnits = $this->callMDarasaAPIPostWithToken($profilePayload, $courseUnitsUrl);

        if ($courseUnits) {

            $courseUnits = $courseUnits->Data;
        }

        $courseUnit = null;

        return view('lecturer.course_units', compact('courses', 'courseUnits', 'courseUnit', 'courseId'));
    }

    public function addCourse(Request $request)
    {
        if (!$this->webAuth()) {
            Session::flash('error', 'Please login to access this page');
            return redirect("/");
        }

        $addCourseUnitsUrl = "/lecturer/course/save";

        $addCoursePayload = [
            "categoryId" => $request->subcategory,
            "courseId" => Session::get("courseId"),
            "profileId" => Session::get("profileId"),
            "courseName" => $request->courseName,
            "courseDescription" => $request->description,
        ];

        $response = $this->callMDarasaAPIPostWithToken(
            $addCoursePayload,
            $addCourseUnitsUrl
        );

        if (!is_null($response) && $response->Success) {

            Session::flash('success', 'Your course has been added successfully');

            if ($request->submitButt == "proceed" && $response->Data->courseId) {

                return redirect("/lecturer/units/" . $response->Data->courseId);
            } elseif ($request->submitButt == "proceed" && !$response->Data->courseId) {
                return redirect("/lecturer/units");
            } else {
                return redirect()->back();
            }
        } else {

            Session::flash('error', 'We could not create the course due to an error on our
             side. Please try again later');
            return redirect()->back();
        }
    }

    public function addCourseUnit(Request $request)
    {
        if (!$this->webAuth()) {
            Session::flash('error', 'Please login to access this page');
            return redirect("/");
        }

        $addCourseUnitsUrl = "/course/unit/save";

        $purchasesExpirationDays = trim($request->purchasesExpirationDays) == "" ? 0 : intval($request->purchasesExpirationDays);

        $addCoursePayload = [
            "courseId" => $request->courseId,
            "profileId" => Session::get("profileId"),
            "courseUnitName" => $request->courseUnitName,
            "unitCode" => $request->courseUnitCode,
            "courseUnitPart" => $request->courseUnitPart,
            "courseUnitSection" => $request->courseUnitSection,
            "highlight" => $request->highlights,
            "unitDescription" => $request->description,
            "purchasesExpirationDays" => $purchasesExpirationDays,
            "price" => $request->price,
        ];

        if (isset($request->courseUnitImage)) {

            $courseUnitImage = $request->courseUnitImage;

            $cfile = new CURLFile(
                $courseUnitImage->getRealPath(),
                $courseUnitImage->getMimeType(),
                $courseUnitImage->getClientOriginalName()
            );

            $addCoursePayload["coverImageFile"] = $cfile;
        } else {

            $addCoursePayload["coverImageFile"] = null;
        }

        $response = $this->callMDarasaAPIPostFormDataWithToken(
            $addCoursePayload,
            $addCourseUnitsUrl
        );

        if (!is_null($response) && $response->Success) {

            Session::flash('success', 'Your course has been added successfully');

            if ($request->submitButt == "proceed" && $response->Data->courseUnitId) {

                return redirect("/lecturer/topics/" . $response->Data->courseUnitId);
            } elseif ($request->submitButt == "proceed" && !$response->Data->courseUnitId) {
                return redirect("/lecturer/topics");
            } else {
                return redirect()->back();
            }

        } else {

            Session::flash('error', 'We could not create the course due to an error on our
             side. Please try again later');
            return redirect()->back();
        }
    }

    public function editCourseUnit($courseUnitId)
    {

        $courseUnitUrl = "/course/unit";

        $courseUnitPayload = [
            "courseUnitId" => $courseUnitId,
        ];

        $courseUnit = $this->callMDarasaAPIPostWithoutToken($courseUnitPayload, $courseUnitUrl);

        if (!is_null($courseUnit)) {

            $courseUnit = $courseUnit->Data;
        } else {
            Session::flash('error', 'Course unit not found');
            return redirect()->back();
        }

        $courseId = $courseUnit->courseId;
        $courses = [];
        $coursesUrl = "/profile/courses";
        $profilePayload = [
            "profileId" => Session::get("profileId"),
        ];

        $courses = $this->callMDarasaAPIPostWithToken($profilePayload, $coursesUrl);
        if ($courses != null) {

            $courses = $courses->Data;
        }

        $courseUnitsUrl = "/course-units/lecturer/all";
        $courseUnits = [];

        $courseUnits = $this->callMDarasaAPIPostWithToken($profilePayload, $courseUnitsUrl);

        if ($courseUnits) {
            $courseUnits = $courseUnits->Data;
        }

        return view('lecturer.course_units', compact('courses', 'courseUnits', 'courseUnit', 'courseId'));
    }

    public function editCourse($courseId)
    {

        $categoriesUrl = "/categories";
        $categories = $this->callMDarasaAPIGetWithoutToken($categoriesUrl);

        $courseUrl = "/course/$courseId";

        $course = $this->callMDarasaAPIGetWithToken($courseUrl);

        if (!is_null($course)) {

            $course = $course->Data;
        }

        $coursesUrl = "/profile/courses";
        $profilePayload = [
            "profileId" => Session::get("profileId"),
        ];

        $courses = $this->callMDarasaAPIPostWithToken($profilePayload, $coursesUrl);
        if ($courses != null) {

            $courses = $courses->Data;
        }

        return view('lecturer.courses', compact('categories', 'courses', 'course'));
    }

    public function updateCourse(Request $request)
    {
        if (!$this->webAuth()) {
            Session::flash('error', 'Please login to access this page');
            return redirect("/");
        }

        $updateCourseUrl = "/course/update";

        $updateCoursePayload = [
            "courseId" => $request->courseId,
            "categoryId" => $request->subcategory,
            "courseName" => $request->courseName,
            "courseType" => "",
            "courseDescription" => $request->description,
            "maxStudents" => 10000,
            "status" => 1,
        ];

        $response = $this->callMDarasaAPIPostWithToken(
            $updateCoursePayload,
            $updateCourseUrl
        );

        if (!is_null($response) && $response->Success) {

            Session::flash('success', 'Your course has been updated successfully');
            return redirect("/lecturer");
        } else {

            Session::flash('error', 'We could not update the course due to an error on our
             side. Please try again later');
            return redirect()->back();
        }
    }

    public function deleteCourse(Request $request)
    {
        if (!$this->webAuth()) {
            Session::flash('error', 'Please login to access this page');
            return redirect("/");
        }

        $deleteCourseUrl = "/course/delete";

        $deleteCoursePayload = [
            "courseId" => $request->courseId,
        ];

        $response = $this->callMDarasaAPIPostWithToken(
            $deleteCoursePayload,
            $deleteCourseUrl
        );

        if (!is_null($response) && $response->Success) {

            Session::flash('success', 'Your course has been deleted successfully');
            return redirect("/lecturer");
        } else {

            Session::flash('error', 'We could not update the course due to an error on our
             side. Please try again later');
            return redirect()->back();
        }
    }

    public function updateCourseUnit(Request $request)
    {
        if (!$this->webAuth()) {
            Session::flash('error', 'Please login to access this page');
            return redirect("/");
        }

        $updateCourseUnitsUrl = "/course/unit/update";

        $purchasesExpirationDays = trim($request->purchasesExpirationDays) == "" ? 0 : intval($request->purchasesExpirationDays);

        $updateCoursePayload = [
            "courseUnitId" => $request->courseUnitId,
            "courseId" => $request->courseId,
            "profileId" => Session::get("profileId"),
            "courseUnitName" => $request->courseUnitName,
            "unitCode" => $request->courseUnitCode,
            "courseUnitPart" => $request->courseUnitPart,
            "courseUnitSection" => $request->courseUnitSection,
            "highlight" => $request->highlights,
            "unitDescription" => $request->description,
            "purchasesExpirationDays" => $purchasesExpirationDays,
            "price" => $request->price,
            "isSuspended" => $request->susp,
            "suspensionReason" => $request->susReason,
        ];

        if (isset($request->courseUnitImage)) {

            $courseUnitImage = $request->courseUnitImage;

            $cfile = new CURLFile(
                $courseUnitImage->getRealPath(),
                $courseUnitImage->getMimeType(),
                $courseUnitImage->getClientOriginalName()
            );

            $updateCoursePayload["coverImageFile"] = $cfile;
        } else {

            $updateCoursePayload["coverImageFile"] = null;
        }

        $response = $this->callMDarasaAPIPostFormDataWithToken(
            $updateCoursePayload,
            $updateCourseUnitsUrl
        );

        if (!is_null($response) && $response->Success) {

            Session::flash('success', 'Your course has been updated successfully');
            return redirect("/lecturer/units");
        } else {

            Session::flash('error', 'We could not update the course due to an error on our
             side. Please try again later');
            return redirect()->back();
        }
    }

    public function account()
    {
        if (!$this->webAuth()) {
            Session::flash('error', 'Please login to access this page');
            return redirect("/");
        }

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

        return view(
            'lecturer.accounts',
            compact(
                'profileInfo',
                'walletInfo',
                'transactions'
            )
        );
    }

    public function topics($id)
    {

        if (!$this->webAuth()) {
            Session::flash('error', 'Please login to access this page');
            return redirect("/");
        }

        $courseUnitsUrl = "/course-units/lecturer/all";
        $profilePayload = [
            "profileId" => Session::get("profileId"),
        ];

        $courseUnits = $this->callMDarasaAPIPostWithToken($profilePayload, $courseUnitsUrl);
        if (!is_null($courseUnits)) {

            $courseUnits = $courseUnits->Data;
        }

        $courseUnitPayload = [
            "courseUnitId" => $id,
        ];

        $courseUnitTopicsUrl = "/course-unit/topics/find";

        $unitTopics = $this->callMDarasaAPIPostWithToken($courseUnitPayload, $courseUnitTopicsUrl);

        if (!is_null($unitTopics)) {

            $unitTopics = $unitTopics->Data;
        }

        $selectedUnitId = $id;
        $viewType = "courses";
        return view(
            'lecturer.topics',
            compact(
                'unitTopics',
                'courseUnits',
                'selectedUnitId',
                'viewType'
            )
        );

    }

    public function editUnitTopic($id, $type)
    {

        if (!$this->webAuth()) {
            Session::flash('error', 'Please login to access this page');
            return redirect("/");
        }

        $unitTopicUrl = "/course-unit/topic";

        $unitTopicPayload = [
            "courseUnitTopicId" => $id,
        ];

        $selectedUnitId = 0;
        $unitTopic = $this->callMDarasaAPIPostWithoutToken($unitTopicPayload, $unitTopicUrl);

        if (!is_null($unitTopic)) {

            $unitTopic = $unitTopic->Data;
        }

        $courseUnitsUrl = "/course-units/lecturer/all";
        $profilePayload = [
            "profileId" => Session::get("profileId"),
        ];

        $courseUnits = $this->callMDarasaAPIPostWithToken($profilePayload, $courseUnitsUrl);
        if (!is_null($courseUnits)) {

            $courseUnits = $courseUnits->Data;
        }

        if (!is_null($unitTopic)) {

            $courseUnitPayload = [
                "courseUnitId" => $unitTopic->courseUnitId,
            ];
            $selectedUnitId = $unitTopic->courseUnitId;

        } elseif (is_null($unitTopic) && $type != "all") {

            Session::flash('error', 'Unit topic cannot be edited');
            return redirect()->back();
        }

        $courseUnitTopicsUrl = "/course-unit/topics/find";

        if ($type == "all") {

            $courseUnitTopicsUrl = "/course-unit/lecturer/topics";

            $courseUnitPayload = [
                "profileId" => Session::get("profileId"),
            ];

        }

        $unitTopics = $this->callMDarasaAPIPostWithToken($courseUnitPayload, $courseUnitTopicsUrl);

        if (!is_null($unitTopics)) {

            $unitTopics = $unitTopics->Data;
        }

        $viewType = $type;

        return view(
            'lecturer.topics',
            compact(
                'unitTopics',
                'courseUnits',
                'selectedUnitId',
                'unitTopic',
                'viewType'
            )
        );

    }

    public function allTopics()
    {

        if (!$this->webAuth()) {
            Session::flash('error', 'Please login to access this page');
            return redirect("/");
        }

        $courseUnitsUrl = "/course-units/lecturer/all";
        $profilePayload = [
            "profileId" => Session::get("profileId"),
        ];

        $courseUnits = $this->callMDarasaAPIPostWithToken($profilePayload, $courseUnitsUrl);
        if (!is_null($courseUnits)) {

            $courseUnits = $courseUnits->Data;
        }

        $courseUnitPayload = [
            "profileId" => Session::get("profileId"),
        ];

        $courseUnitTopicsUrl = "/course-unit/lecturer/topics";

        $unitTopics = $this->callMDarasaAPIPostWithToken($courseUnitPayload, $courseUnitTopicsUrl);

        if (!is_null($unitTopics)) {

            $unitTopics = $unitTopics->Data;
        }

        $selectedUnitId = 0;
        $viewType = "all";

        return view('lecturer.topics', compact('unitTopics', 'courseUnits', 'selectedUnitId', 'viewType'));
    }

    public function subtopics($id = null)
    {

        $selectedUnitId = 0;
        $selectedUnitTopicId = 0;

        if (!$this->webAuth()) {
            Session::flash('error', 'Please login to access this page');
            return redirect("/");
        }

        $courseUnitsUrl = "/course-units/lecturer/all";
        $lecturerTopicsUrl = "/course-unit/lecturer/topics";

        $profilePayload = [
            "profileId" => Session::get("profileId"),
        ];

        $courseUnits = $this->callMDarasaAPIPostWithToken($profilePayload, $courseUnitsUrl);
        if (!is_null($courseUnits)) {

            $courseUnits = $courseUnits->Data;
        }

        $unitTopics = $this->callMDarasaAPIPostWithToken($profilePayload, $lecturerTopicsUrl);
        if (!is_null($unitTopics)) {

            $unitTopics = $unitTopics->Data;

        }

        $viewType = "all";

        if (is_null($id)) {

            $courseUnitSubtopicsUrl = "/course-unit/lecturer/subtopics";
            $unitSubtopics = $this->callMDarasaAPIPostWithToken($profilePayload, $courseUnitSubtopicsUrl);

        } else {

            $topicPayload = [
                "courseUnitTopicId" => $id,
            ];

            $courseUnitTopicUrl = "/course-unit/topic";
            $courseUnitTopic = $this->callMDarasaAPIPostWithToken($topicPayload, $courseUnitTopicUrl);

            if (!is_null($courseUnitTopic->Data)) {

                $selectedUnitId = $courseUnitTopic->Data->courseUnitId;
            }

            $viewType = "topics";

            $courseUnitSubtopicsUrl = "/topic/subtopics/find";
            $unitSubtopics = $this->callMDarasaAPIPostWithToken($topicPayload, $courseUnitSubtopicsUrl);

        }

        if (!is_null($unitSubtopics)) {

            $unitSubtopics = $unitSubtopics->Data;
        }

        if (!is_null($id)) {

            $selectedUnitTopicId = $id;
        }

        $unitSubtopic = null;

        return view(
            'lecturer.subtopics',
            compact(
                'unitSubtopics',
                'courseUnits',
                'unitTopics',
                'selectedUnitId',
                'selectedUnitTopicId',
                'unitSubtopic',
                'viewType'
            )
        );
    }

    public function subtopicQuestions($courseUnitId, $subtopicId)
    {
        if (!$this->webAuth()) {
            Session::flash('error', 'Please login to access this page');
            return redirect("/");
        }

        $subtopicQuestionsUrl = "/subtopic/questions/filter";

        $subtopicQuestionsPayload = [
            "courseUnitSubtopicId" => $subtopicId,
        ];

        $subtopicQuestions = $this->callMDarasaAPIPostWithToken(
            $subtopicQuestionsPayload,
            $subtopicQuestionsUrl
        );

        if (!is_null($subtopicQuestions)) {

            $subtopicQuestions = $subtopicQuestions->Data;
        }

        return view('lecturer.subtopic-questions', [
            "subtopicQuestions" => $subtopicQuestions,
            "courseUnitId" => $courseUnitId,
            "subtopicId" => $subtopicId,
            "subtopicQuestion" => null,
        ]);
    }

    public function answerQuestion(Request $request)
    {

        if (!$this->webAuth()) {
            Session::flash('error', 'Please login to access this page');
            return redirect("/");
        }

        $answerQuestionUrl = "/question/answer/save";

        $answerQuestionPayload = [
            "questionAnswerId" => 0,
            "answer" => $request->questionAnswer,
            "subtopicQuestionId" => $request->subtopicQuestionId,
            "status" => 1,
        ];

        $response = $this->callMDarasaAPIPostWithToken(
            $answerQuestionPayload,
            $answerQuestionUrl
        );

        if (!is_null($response) && $response->Success) {

            Session::flash('success', 'Your answer has been saved successfully');
            return redirect()->back();
        } else {

            Session::flash('error', 'Answer not created due to error on our side. Please try again later');
            return redirect()->back();
        }
    }

    public function addUnitTopic(Request $request)
    {
        if (!$this->webAuth()) {
            Session::flash('error', 'Please login to access this page');
            return redirect("/");
        }

        $addUnitTopicUrl = "/course-unit/topic/save";

        $addTopicPayload = [
            "courseUnitId" => $request->courseUnitId,
            "topicName" => $request->unitTopicName,
            "description" => $request->topicDescription,
            "topicNumber" => $request->topicNumber,
            "status" => 1,
        ];

        if (isset($request->topicVideoFile)) {

            $topicVideoFile = $request->topicVideoFile;

            $cfile = new CURLFile(
                $topicVideoFile->getRealPath(),
                $topicVideoFile->getMimeType(),
                $topicVideoFile->getClientOriginalName()
            );

            $addTopicPayload["topicVideoFile"] = $cfile;
        } else {

            $addTopicPayload["topicVideoFile"] = null;
        }

        if (isset($request->topicNotesFile)) {

            $topicNotesFile = $request->topicNotesFile;

            $ctfile = new CURLFile(
                $topicNotesFile->getRealPath(),
                $topicNotesFile->getMimeType(),
                $topicNotesFile->getClientOriginalName()
            );

            $addTopicPayload["topicNotesFile"] = $ctfile;
        } else {

            $addTopicPayload["topicNotesFile"] = null;
        }

        $response = $this->callMDarasaAPIPostFormDataWithToken($addTopicPayload, $addUnitTopicUrl);

        if (!is_null($response) && $response->Success) {

            Session::flash('success', 'Your topic has been added successfully');

            if ($request->submitButt == "proceed" && $response->Data->courseUnitTopicId) {

                return redirect("/lecturer/subtopics/" . $response->Data->courseUnitTopicId);
            } elseif ($request->submitButt == "proceed" && !$response->Data->courseUnitTopicId) {
                return redirect("/lecturer/subtopics");
            } else {
                return redirect()->back();
            }
        } else {

            Session::flash('error', 'We could not create the topic due to an error on our
             side. Please try again later');
            return redirect()->back();
        }

    }

    public function updateUnitTopic(Request $request)
    {
        if (!$this->webAuth()) {
            Session::flash('error', 'Please login to access this page');
            return redirect("/");
        }

        $updateUnitTopicUrl = "/course-unit/topic/update";

        $updateTopicPayload = [
            "courseUnitTopicId" => $request->courseUnitTopicId,
            "courseUnitId" => $request->courseUnitId,
            "topicName" => $request->unitTopicName,
            "description" => $request->topicDescription,
            "topicNumber" => $request->topicNumber,
            "status" => 1,
        ];

        if (isset($request->topicVideoFile)) {

            $topicVideoFile = $request->topicVideoFile;

            $cfile = new CURLFile(
                $topicVideoFile->getRealPath(),
                $topicVideoFile->getMimeType(),
                $topicVideoFile->getClientOriginalName()
            );

            $addTopicPayload["topicVideoFile"] = $cfile;
        } else {

            $addTopicPayload["topicVideoFile"] = null;
        }

        if (isset($request->topicNotesFile)) {

            $topicNotesFile = $request->topicNotesFile;

            $ctfile = new CURLFile(
                $topicNotesFile->getRealPath(),
                $topicNotesFile->getMimeType(),
                $topicNotesFile->getClientOriginalName()
            );

            $addTopicPayload["topicNotesFile"] = $ctfile;
        } else {

            $addTopicPayload["topicNotesFile"] = null;
        }

        $response = $this->callMDarasaAPIPostFormDataWithToken($updateTopicPayload, $updateUnitTopicUrl);

        if (!is_null($response) && $response->Success) {

            Session::flash('success', 'Your topic has been updated successfully');

            if ($request->viewType == "courses") {

                return redirect("/lecturer/topics/" . $request->courseUnitId);
            } else {

                return redirect("/lecturer/topics");
            }

        } else {

            Session::flash('error', 'We could not update the topic due to an error on our
             side. Please try again later');
            return redirect()->back();
        }

    }

    public function addUnitSubtopic(Request $request)
    {
        if (!$this->webAuth()) {
            Session::flash('error', 'Please login to access this page');
            return redirect("/");
        }

        $addUnitSubtopicUrl = "/course-unit/subtopic/save";
        $addSubtopicPayload = "";

        $addSubtopicPayload = [
            "unitTopicId" => $request->courseUnitTopicId,
            "courseUnitId" => $request->courseUnitId,
            "subtopicName" => $request->subtopicName,
            "description" => $request->subtopicDescription,
            "subtopicNumber" => $request->subtopicNumber,
            "status" => 1,
        ];

        $response = $this->callMDarasaAPIPostFormDataWithToken($addSubtopicPayload, $addUnitSubtopicUrl);

        if (!is_null($response) && $response->Success) {

            Session::flash('success', 'Your subtopic has been added successfully');

            if ($request->submitButt == "proceed" && $response->Data->courseUnitSubtopicId) {

                return redirect("/unit/subtopic/resources/" . $response->Data->courseUnitSubtopicId);
            } else {
                return redirect()->back();
            }

        } else {

            Session::flash('error', 'We could not create the subtopic due to an error on our
             side. Please try again later');
            return redirect()->back();
        }

    }

    public function updateUnitSubtopic(Request $request)
    {
        if (!$this->webAuth()) {
            Session::flash('error', 'Please login to access this page');
            return redirect("/");
        }

        $updateUnitSubtopicUrl = "/course-unit/subtopic/update";

        $updateSubtopicPayload = [
            "courseUnitSubtopicId" => $request->courseUnitSubtopicId,
            "unitTopicId" => $request->courseUnitTopicId,
            "courseUnitId" => $request->courseUnitId,
            "subtopicName" => $request->subtopicName,
            "description" => $request->subtopicDescription,
            "subtopicNumber" => $request->subtopicNumber,
            "status" => 1,
        ];

        $response = $this->callMDarasaAPIPostFormDataWithToken($updateSubtopicPayload, $updateUnitSubtopicUrl);

        if (!is_null($response) && $response->Success) {

            Session::flash('success', 'Your subtopic has been updated successfully');

            if ($request->viewType == "topics") {

                return redirect("/lecturer/subtopics/" . $request->courseUnitTopicId);
            } else {

                return redirect("/lecturer/topics");
            }

        } else {

            Session::flash('error', 'We could not update the topic due to an error on our
             side. Please try again later');
            return redirect()->back();
        }

    }


    public function subtopicResources($subtopicId)
    {

        if (!$this->webAuth()) {
            Session::flash('error', 'Please login to access this page');
            return redirect("/");
        }

        $videosUrl = "/subtopic/videos";
        $notesUrl = "/subtopic/notes";

        $subtopicIDPayload = [
            "courseUnitSubtopicId" => $subtopicId,
        ];

        $videos = $this->callMDarasaAPIPostWithToken($subtopicIDPayload, $videosUrl);
        if (!is_null($videos)) {

            $videos = $videos->Data;
        }

        $notes = $this->callMDarasaAPIPostWithToken($subtopicIDPayload, $notesUrl);
        if (!is_null($notes)) {

            $notes = $notes->Data;
        }

        $courseUnitSubtopicId = $subtopicId;

        return view(
            'lecturer.subtopic-videos-notes',
            compact(
                'videos',
                'notes',
                'courseUnitSubtopicId'
            )
        );

    }

    public function addSubtopicVideo(Request $request)
    {
        if (!$this->webAuth()) {
            Session::flash('error', 'Please login to access this page');
            return redirect("/");
        }

        $addUnitSubtopicVideoUrl = "/subtopic/video/add";

        $addSubtopicVideoPayload = [
            "videoTitle" => $request->videoTitle,
            "courseUnitSubtopicId" => $request->courseUnitSubtopicId,
            "videoNumber" => $request->videoNumber,
            "status" => 1,
        ];

        if (isset($request->subtopicVideoFile)) {

            $subtopicVideoFile = $request->subtopicVideoFile;

            $cfile = new CURLFile(
                $subtopicVideoFile->getRealPath(),
                $subtopicVideoFile->getMimeType(),
                $subtopicVideoFile->getClientOriginalName()
            );

            $addSubtopicVideoPayload["subtopicVideoFile"] = $cfile;

        } else {

            Session::flash('error', 'Please attach a valid video file to proceed');
            return redirect()->back();

        }

        $response = $this->callMDarasaAPIPostFormDataWithToken(
            $addSubtopicVideoPayload,
            $addUnitSubtopicVideoUrl
        );

        if (!is_null($response) && $response->Success) {

            Session::flash('success', 'Your subtopic video has been added successfully');
        } else {

            Session::flash('error', 'We could not upload the subtopic video due to an error on our
             side. Please try again later');
        }

        return redirect()->back();
    }

    public function addSubtopicThirdPartyVideo(Request $request)
    {
        if (!$this->webAuth()) {
            Session::flash('error', 'Please login to access this page');
            return redirect("/");
        }

        $addUnitSubtopicVideoUrl = "/subtopic/video/thirdparty";

        $addSubtopicVideoPayload = [
            "videoTitle" => $request->videoTitle,
            "courseUnitSubtopicId" => $request->courseUnitSubtopicId,
            "videoNumber" => $request->videoNumber,
            "status" => 1,
            "thirdPartyVideoUrl" => $request->thirdPartyVideoUrl,
        ];

        $response = $this->callMDarasaAPIPostWithToken(
            $addSubtopicVideoPayload,
            $addUnitSubtopicVideoUrl
        );

        if (!is_null($response) && $response->Success) {

            Session::flash('success', 'Your subtopic video has been added successfully');
        } else {

            Session::flash('error', 'We could not add the subtopic video due to an error on our
             side. Please try again later');
        }

        return redirect()->back();
    }

    public function addSubtopicVideoV2(Request $request)
    {
        if (!$this->webAuth()) {
            Session::flash('error', 'Please login to access this page');
            return redirect("/");
        }

        $bucketVideoUrl = "https://bucket.mdarasa.co.ke/api/v1/files/compress-video";

        if (isset($request->subtopicVideoFile)) {

            $subtopicVideoFile = $request->subtopicVideoFile;

            $cfile = new CURLFile(
                $subtopicVideoFile->getRealPath(),
                $subtopicVideoFile->getMimeType(),
                $subtopicVideoFile->getClientOriginalName()
            );

            $addSubtopicVideoPayload = [
                "video" => $cfile,
            ];

        } else {

            Log::error("No valid file for upload is provided");
            Session::flash('error', 'Please attach a valid video file to proceed');
            return redirect()->back();

        }

        Log::info("Initiating request to bucket for file upload");

        $response = $this->uploadFileToBucket(
            $addSubtopicVideoPayload,
            $bucketVideoUrl
        );

        $jsonResponseString = json_encode($response);

        Log::info("Got response from bucket upload endpoint {$jsonResponseString}");

        if (!is_null($response) && $response->message == "Success") {

            $responsePayload = $response->payload[0];
            $videoUrl = $responsePayload->videoUrl;
            $thumbnailUrl = $responsePayload->thumbnailUrl;

            DB::table('subtopic_video')->insert([
                'video_title' => $request->videoTitle,
                'course_unit_subtopic_id' => $request->courseUnitSubtopicId,
                'subtopic_video' => $videoUrl,
                'video_thumbnail' => $thumbnailUrl,
                'video_number' => $request->videoNumber,
                'status' => 1
            ]);

            return response()->json(
                [
                    'Success' => true,
                    'message' => 'Your subtopic video has been added successfully'
                ],
                200
            );
        } else {

            return response()->json(
                [
                    'Success' => false,
                    'message' => 'We could not upload the subtopic video due to an error on our side. Please try again later'
                ],
                503
            );
        }
    }

    public function addSubtopicNotes(Request $request)
    {
        if (!$this->webAuth()) {
            Session::flash('error', 'Please login to access this page');
            return redirect("/");
        }

        $addUnitSubtopicNotesUrl = "/subtopic/notes/add";

        $addSubtopicNotesPayload = [
            "notesTitle" => $request->notesTitle,
            "courseUnitSubtopicId" => $request->courseUnitSubtopicId,
            "notesNumber" => $request->notesNumber,
            "status" => 1,
        ];

        if (isset($request->subtopicNotesFile)) {

            $subtopicNotesFile = $request->subtopicNotesFile;

            $cfile = new CURLFile(
                $subtopicNotesFile->getRealPath(),
                $subtopicNotesFile->getMimeType(),
                $subtopicNotesFile->getClientOriginalName()
            );

            $addSubtopicNotesPayload["subtopicNotesFile"] = $cfile;

        } else {

            Session::flash('error', 'Please attach a valid notes file to proceed');
            return redirect()->back();

        }

        $response = $this->callMDarasaAPIPostFormDataWithToken(
            $addSubtopicNotesPayload,
            $addUnitSubtopicNotesUrl
        );

        if (!is_null($response) && $response->Success) {

            Session::flash('success', 'Your subtopic notes have been added successfully');
        } else {

            Session::flash('error', 'We could not upload the subtopic notes due to an error on our
             side. Please try again later');
        }

        return redirect()->back();
    }

    public function removeSubtopicVideo(Request $request)
    {
        if (!$this->webAuth()) {
            Session::flash('error', 'Please login to access this page');
            return redirect("/");
        }

        $removeUnitSubtopicVideoUrl = "/subtopic/video/delete";

        $removeSubtopicVideoPayload = [
            "subtopicVideoId" => $request->subtopicVideoId,
        ];

        $response = $this->callMDarasaAPIPostWithToken(
            $removeSubtopicVideoPayload,
            $removeUnitSubtopicVideoUrl
        );

        if (!is_null($response) && $response->Success) {

            Session::flash('success', 'Your subtopic video has been removed successfully');
        } else {

            Session::flash('error', 'We could not remove the subtopic video due to an error on our
             side. Please try again later');
        }

        return redirect()->back();
    }

    public function removeSubtopicNotes(Request $request)
    {
        if (!$this->webAuth()) {
            Session::flash('error', 'Please login to access this page');
            return redirect("/");
        }

        $removeUnitSubtopicNotesUrl = "/subtopic/notes/delete";

        $removeSubtopicNotesPayload = [
            "subtopicNotesId" => $request->subtopicNotesId,
        ];

        $response = $this->callMDarasaAPIPostWithToken(
            $removeSubtopicNotesPayload,
            $removeUnitSubtopicNotesUrl
        );

        if (!is_null($response) && $response->Success) {

            Session::flash('success', 'Your subtopic notes have been removed successfully');
        } else {

            Session::flash('error', 'We could not remove the subtopic notes due to an error on our
             side. Please try again later');
        }

        return redirect()->back();
    }

    public function editUnitSubtopic($id, $type)
    {

        if (!$this->webAuth()) {
            Session::flash('error', 'Please login to access this page');
            return redirect("/");
        }

        $selectedUnitId = 0;
        $selectedUnitTopicId = 0;

        $unitSubtopicUrl = "/course-unit/subtopic";

        $unitSubtopicPayload = [
            "courseUnitSubtopicId" => $id,
        ];

        $unitSubtopic = $this->callMDarasaAPIPostWithToken($unitSubtopicPayload, $unitSubtopicUrl);
        if (!is_null($unitSubtopic)) {

            $unitSubtopic = $unitSubtopic->Data;
        }

        $courseUnitsUrl = "/course-units/lecturer/all";
        $profilePayload = [
            "profileId" => Session::get("profileId"),
        ];

        $courseUnits = $this->callMDarasaAPIPostWithToken($profilePayload, $courseUnitsUrl);
        if (!is_null($courseUnits)) {

            $courseUnits = $courseUnits->Data;
        }

        if (!is_null($unitSubtopic)) {

            $topicPayload = [
                "courseUnitTopicId" => $unitSubtopic->unitTopicId,
            ];

            $courseUnitPayload = [
                "courseUnitId" => $unitSubtopic->courseUnitId,
            ];

            $selectedUnitTopicId = $unitSubtopic->unitTopicId;
            $selectedUnitId = $unitSubtopic->courseUnitId;

        } elseif (is_null($unitSubtopic) && $type != "all") {

            Session::flash('error', 'Unit subtopic cannot be edited');
            return redirect()->back();
        }

        $courseUnitTopicsUrl = "/course-unit/topics/find";
        $courseUnitSubtopicsUrl = "/topic/subtopics/find";

        if ($type == "all") {

            $courseUnitSubtopicsUrl = "/course-unit/lecturer/subtopics";
            $courseUnitTopicsUrl = "/course-unit/lecturer/topics";

            $topicPayload = [
                "profileId" => Session::get("profileId"),
            ];

            $courseUnitPayload = $profilePayload;

        }

        $unitSubtopics = $this->callMDarasaAPIPostWithToken($topicPayload, $courseUnitSubtopicsUrl);

        if (!is_null($unitSubtopics)) {

            $unitSubtopics = $unitSubtopics->Data;
        }

        $unitTopics = $this->callMDarasaAPIPostWithToken($courseUnitPayload, $courseUnitTopicsUrl);

        if (!is_null($unitTopics)) {

            $unitTopics = $unitTopics->Data;

        }

        $viewType = $type;

        return view(
            'lecturer.subtopics',
            compact(
                'unitSubtopics',
                'unitTopics',
                'courseUnits',
                'selectedUnitTopicId',
                'selectedUnitId',
                'unitSubtopic',
                'viewType'
            )
        );

    }

    public function removeTopic(Request $request)
    {
        if (!$this->webAuth()) {
            Session::flash('error', 'Please login to access this page');
            return redirect("/");
        }

        $deleteUnitTopicUrl = "/course-unit/topic/delete";
        $deleteTopicPayload = [

            "courseUnitTopicId" => $request->courseUnitTopicId,
        ];

        $response = $this->callMDarasaAPIPostWithToken($deleteTopicPayload, $deleteUnitTopicUrl);

        if (!is_null($response) && $response->Success) {

            Session::flash('success', 'Unit topic removed successfully!');
            return redirect()->back();

        } else {

            Session::flash('error', 'We could not delete the topic due to an error on our
             side. Please try again later');
            return redirect()->back();
        }
    }

    public function removeUnit(Request $request)
    {
        if (!$this->webAuth()) {
            Session::flash('error', 'Please login to access this page');
            return redirect("/");
        }

        $deleteUnitUrl = "/course/unit/delete";
        $deleteUnitPayload = [

            "courseUnitId" => $request->courseUnitId,
        ];

        $response = $this->callMDarasaAPIPostWithToken($deleteUnitPayload, $deleteUnitUrl);

        if (!is_null($response) && $response->Success) {

            Session::flash('success', 'Course unit removed successfully!');
            return redirect()->back();

        } else {

            Session::flash('error', 'We could not delete the course unit due to an error on our
             side. Please try again later');
            return redirect()->back();
        }
    }

    public function removeSubtopic(Request $request)
    {
        if (!$this->webAuth()) {
            Session::flash('error', 'Please login to access this page');
            return redirect("/");
        }

        $deleteUnitSubtopicUrl = "/course-unit/subtopic/delete";
        $deleteSubtopicPayload = [

            "courseUnitSubtopicId" => $request->courseUnitSubtopicId,
        ];

        $response = $this->callMDarasaAPIPostWithToken($deleteSubtopicPayload, $deleteUnitSubtopicUrl);

        if (!is_null($response) && $response->Success) {

            Session::flash('success', 'Unit subtopic removed successfully!');
            return redirect()->back();

        } else {

            Session::flash('error', 'We could not delete the subtopic due to an error on our side. Please try again later');
            return redirect()->back();
        }
    }

    public function getLecturerCourseUnit($courseUnitId)
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

        return view('lecturer.lecturer-course-unit', compact('courseUnit', 'courseUnitTopics'));

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

        return view('lecturer.topic-notes-details', [
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

        return view('lecturer.subtopic-notes-details', [
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

        return view('lecturer.topic-video-details', [
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

        return view('lecturer.subtopic-video-details', [
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

    public function playLecturerVideo($fileName)
    {

        $redis = new \Predis\Client([
            'scheme' => 'tcp',
            'host' => 'localhost',
            'port' => 6379,
        ]);

        if (!empty($fileName)) {

            $cachedVideo = $redis->get('video:' . $fileName);

            if ($cachedVideo) {

                header('Content-type:video/mp4');
                echo $cachedVideo;
            } else {

                $videoFilePath = 'https://bucket.mdarasa.co.ke/file/' . $fileName;

                $handle = @fopen($videoFilePath, 'r');

                if ($handle) {

                    $videoData = file_get_contents($videoFilePath);

                    $redis->set('video:' . $fileName, $videoData);
                    $redis->expire('video:' . $fileName, 86400 * 14);
                    header('Content-type: video/mp4');

                    echo $videoData;
                } else {

                    header('HTTP/1.0 404 Not Found');
                }
            }
        } else {

            header('HTTP/1.0 400 Bad Request');
        }
    }

    public function orders()
    {
        if (!$this->webAuth()) {
            Session::flash('error', 'Please login to access this page');
            return redirect("/");
        }

        $profilePayload = [
            "profileId" => Session::get("profileId"),
        ];

        $ordersUrl = "/lecturer/orders";
        $orders = $this->callMDarasaAPIPostWithToken($profilePayload, $ordersUrl);
        $orders = !is_null($orders) ? $orders->Data : [];

        return view('lecturer.orders', compact('orders'));
    }

    public function myStudents()
    {

        if (!$this->webAuth()) {
            Session::flash('error', 'Please login to access this page');
            return redirect("/");
        }

        $myStudentsUrl = "/lecturer/students";
        $profilePayload = [
            "profileId" => Session::get("profileId"),
        ];

        $lecturerStudents = $this->callMDarasaAPIPostWithToken($profilePayload, $myStudentsUrl);
        $lecturerStudents = $lecturerStudents->Data;

        return view('lecturer.students', compact('lecturerStudents'));
    }
}