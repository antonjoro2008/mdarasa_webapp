<?php

namespace App\Http\Controllers;

use CURLFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Predis\Client;

class HomeController extends Controller
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

        return view(
            'welcome',
            compact(
                'categories',
                'featuredCourses',
                'newArrivals',
                'popularUnits'
            )
        );
    }

    public function teach()
    {

        $coursesUrl = "/courses";
        $courses = $this->callMDarasaAPIGetWithoutToken($coursesUrl);

        $categoriesUrl = "/categories";
        $categories = $this->callMDarasaAPIGetWithoutToken($categoriesUrl);

        return view('instructor', compact('courses', 'categories'));

    }

    public function categoryUnits($categoryId)
    {
        $categoryPayload = [
            "categoryId" => $categoryId,
        ];

        $categoryUnitsUrl = "/category/units/find";
        $courseUnits = $this->callMDarasaAPIPostWithoutToken($categoryPayload, $categoryUnitsUrl);
        $courseUnits = $courseUnits->Data;

        $categoryLecturersUrl = "/category/lecturers";
        $lecturers = $this->callMDarasaAPIPostWithoutToken($categoryPayload, $categoryLecturersUrl);
        $lecturers = $lecturers->Data;

        return view('student.course-units', compact('courseUnits', 'lecturers'));
    }

    public function courseUnits($categoryId)
    {
        $categoryPayload = [
            "categoryId" => $categoryId,
        ];

        $categoryUnitsUrl = "/category/units/find";
        $courseUnits = $this->callMDarasaAPIPostWithoutToken($categoryPayload, $categoryUnitsUrl);
        $courseUnits = $courseUnits->Data;

        $categoryLecturersUrl = "/subcategory/lecturers";
        $lecturers = $this->callMDarasaAPIPostWithoutToken($categoryPayload, $categoryLecturersUrl);
        $lecturers = $lecturers->Data;

        return view('student.course-units', compact('courseUnits', 'lecturers'));
    }

    public function lecturerUnits($categoryId, $lecturerId)
    {
        $lecturerPayload = [
            "profileId" => $lecturerId,
        ];

        $lecturerUnitsUrl = "/course/units/lecturer";
        $lecturerUnits = $this->callMDarasaAPIPostWithoutToken($lecturerPayload, $lecturerUnitsUrl);
        $lecturerUnits = $lecturerUnits->Data;

        $categoryPayload = [
            "categoryId" => $categoryId,
        ];

        $categoryLecturersUrl = "/category/lecturers";
        $lecturers = $this->callMDarasaAPIPostWithoutToken($categoryPayload, $categoryLecturersUrl);
        $lecturers = $lecturers->Data;

        return view('student.lecturer-units', compact('lecturerUnits', 'lecturers'));

    }

    public function getCourseUnit($courseUnitId)
    {

        $courseUnitUrl = "/course/unit";

        $courseUnitPayload = [
            "courseUnitId" => $courseUnitId,
        ];

        $courseUnit = $this->callMDarasaAPIPostWithoutToken($courseUnitPayload, $courseUnitUrl);

        if (!is_null($courseUnit)) {

            $courseUnit = $courseUnit->Data;

            if (is_null($courseUnit)) {
                Session::flash('error', 'The course you searched for doesn\'t exist');
                return redirect("/");
            }
        } else {

            Session::flash('error', 'The course you searched for doesn\'t exist');
            return redirect("/");
        }

        $popularUrl = "/courses/popular";
        $popularUnits = $this->callMDarasaAPIGetWithoutToken($popularUrl);

        return view('unit-details', compact('courseUnit', 'popularUnits'));

    }

    public function myUnits()
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

        $myUnitsUrl = "/course/units/student";
        $myUnits = $this->callMDarasaAPIPostWithToken($profilePayload, $myUnitsUrl);
        $myUnits = !is_null($myUnits) ? $myUnits->Data : [];

        return view('student.myunits', compact('categories', 'myUnits'));

    }

    public function getStudentCourseUnit($courseUnitId)
    {
        if (!$this->webAuth()) {
            Session::flash('error', 'Please login to access this page');
            return redirect("/");
        }

        $categoriesUrl = "/categories";
        $categories = $this->callMDarasaAPIGetWithoutToken($categoriesUrl);

        $studentCourseUnitUrl = "/student/course/unit";

        $studentCourseUnitPayload = [
            "profileId" => Session::get("profileId"),
            "courseUnitId" => $courseUnitId,
        ];

        $studentCourseUnitTopicsUrl = "/student/unit/topics";

        $courseUnit = $this->callMDarasaAPIPostWithToken($studentCourseUnitPayload, $studentCourseUnitUrl);
        $courseUnitTopics = $this->callMDarasaAPIPostWithToken($studentCourseUnitPayload, $studentCourseUnitTopicsUrl);

        if (!is_null($courseUnit)) {

            $courseUnit = $courseUnit->Data;
        } else {

            Session::flash('error', 'You are not authorized to access this page');
            return redirect()->back();
        }

        if (is_null($courseUnit)) {

            Session::flash('error', 'You are not authorized to access this page');
            return redirect()->back();

        }

        if (!is_null($courseUnitTopics)) {

            $courseUnitTopics = $courseUnitTopics->Data;
            Session::put("courseUnitTopics", $courseUnitTopics);
        }

        return view('student.student-course-unit', compact('categories', 'courseUnit', 'courseUnitTopics'));

    }

    public function viewTopicNotes(Request $request)
    {

        if (!$this->webAuth()) {
            Session::flash('error', 'Please login to access this page');
            return redirect("/");
        }

        $categoriesUrl = "/categories";
        $categories = $this->callMDarasaAPIGetWithoutToken($categoriesUrl);

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

        return view('student.topic-notes-details', [
            "categories" => $categories,
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

        $categoriesUrl = "/categories";
        $categories = $this->callMDarasaAPIGetWithoutToken($categoriesUrl);

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

        return view('student.subtopic-notes-details', [
            "categories" => $categories,
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

        $categoriesUrl = "/categories";
        $categories = $this->callMDarasaAPIGetWithoutToken($categoriesUrl);

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

        return view('student.topic-video-details', [
            "categories" => $categories,
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

        $categoriesUrl = "/categories";
        $categories = $this->callMDarasaAPIGetWithoutToken($categoriesUrl);

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

        return view('student.subtopic-video-details', [
            "categories" => $categories,
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

    public function playVideo($fileName)
    {

        $redis = new \Predis\Client([
            'scheme' => 'tcp',
            'host' => 'localhost',
            'port' => 6379,
        ]);

        $uploadsDir = env('UPLOADS_DIR');

        if (!$uploadsDir) {

            $uploadsDir = $this->getVideosUploadsDirectory();
        }

        if (!empty($fileName)) {

            // $cachedVideo = $redis->get('video:' . $fileName);

            // if ($cachedVideo) {

            //     Log::info("File is in Redis cache. Proceeding");

            //     Log::info("Looking for file in ", [$uploadsDir]);

            //     $myfile = fopen($uploadsDir . '/' . $fileName, "w");

            //     fwrite($myfile, $cachedVideo);
            //     fclose($myfile);

            //     // $this->sendVideoFileWithHTTPRanges($cachedVideo, "Redis");

            //     $this->sendVideoFileWithHTTPRanges($uploadsDir . '/' . $fileName, "File");

            // } else {

            Log::info("File is NOT in Redis cache. Proceeding to fetch from bucket");

            $videoFilePath = 'https://bucket.mdarasa.co.ke/file/' . $fileName;

            $handle = @fopen($videoFilePath, 'r');

            if ($handle) {

                $videoData = file_get_contents($videoFilePath);

                $myfile = fopen($uploadsDir . '/' . $fileName, "w");

                fwrite($myfile, $videoData);
                fclose($myfile);

                // $redis->set('video:' . $fileName, $videoData);
                // $redis->expire('video:' . $fileName, 86400 * 14);

                // $this->sendVideoFileWithHTTPRanges($videoData, "Redis");

                $this->sendVideoFileWithHTTPRanges($uploadsDir . '/' . $fileName, "File");
            } else {

                header('HTTP/1.0 404 Not Found');
            }
            // }

            if (file_exists($uploadsDir . '/' . $fileName)) {
                unlink($uploadsDir . '/' . $fileName);
            }
        } else {

            header('HTTP/1.0 400 Bad Request');
        }
    }

    private function sendVideoFileWithHTTPRanges($videoFile, $source)
    {
        $fileSize = 0;
        Log::info("Source is ", [$source]);
        if ($source == "Redis") {

            Log::info("Processing for redis source");

            $fileSize = strlen($videoFile);
        } else {

            Log::info("Processing for file path source");

            $fileSize = filesize($videoFile);
        }
        $range = isset($_SERVER['HTTP_RANGE']) ? $_SERVER['HTTP_RANGE'] : '';

        if ($range) {

            list($units, $range) = explode('=', $range, 2);
            list($start, $end) = explode('-', $range, 2);
            $start = intval($start);
            $end = $end ? intval($end) : $fileSize - 1;
            $length = $end - $start + 1;

            header('HTTP/1.1 206 Partial Content');
            header('Content-Type: video/mp4');
            header('Content-Length: ' . $length);
            header('Content-Range: bytes ' . $start . '-' . $end . '/' . $fileSize);
            header('Accept-Ranges: bytes');
            http_response_code(206);

            // Read and output the specified portion of the video

            if ($source != "Redis") {
                $file = fopen($videoFile, 'rb');
                fseek($file, $start);
                while (!feof($file) && ($p = ftell($file)) <= $end) {
                    if ($p + 1024 > $end) {
                        $length = $end - $p + 1;
                    }
                    set_time_limit(0);
                    echo fread($file, $length);
                    flush();
                }
                fclose($file);
            } else {
                $this->sendChunksForRedisFile($videoFile);
            }

            http_response_code(200);

        } else {

            header('HTTP/2 206 Partial Content');
            header('Content-Type: video/mp4');
            http_response_code(206);

            $chunkSize = 1024 * 1024;

            if ($source != "Redis") {
                $file = fopen($videoFile, 'rb');

                while (!feof($file)) {
                    set_time_limit(0);
                    echo fread($file, $chunkSize);
                    ob_flush();
                    flush();
                }

                fclose($file);
            } else {
                $this->sendChunksForRedisFile($videoFile);
            }
            http_response_code(200);
        }
    }

    private function sendChunksForRedisFile($videoFile)
    {

        $chunkSize = 1024 * 1024; // Set the chunk size (1 MB in this case)
        $totalSize = strlen($videoFile);

        for ($start = 0; $start < $totalSize; $start += $chunkSize) {

            $end = min($start + $chunkSize - 1, $totalSize - 1);
            $contentSize = $end - $start + 1;

            // Send the current chunk
            echo substr($videoFile, $start, $contentSize);

            ob_flush();
            flush();
        }
    }

    public function subtopicQuestions($courseUnitId, $subtopicId)
    {
        if (!$this->webAuth()) {
            Session::flash('error', 'Please login to access this page');
            return redirect("/");
        }

        $categoriesUrl = "/categories";
        $categories = $this->callMDarasaAPIGetWithoutToken($categoriesUrl);

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

        return view('student.subtopic-questions', [
            "categories" => $categories,
            "subtopicQuestions" => $subtopicQuestions,
            "courseUnitId" => $courseUnitId,
            "subtopicId" => $subtopicId,
            "subtopicQuestion" => null,
        ]);
    }

    public function addQuestion(Request $request)
    {

        if (!$this->webAuth()) {
            Session::flash('error', 'Please login to access this page');
            return redirect("/");
        }

        $addQuestionUrl = "/subtopic/question/save";

        $addQuestionPayload = [
            "question" => $request->question,
            "courseUnitSubtopicId" => $request->courseUnitSubtopicId,
            "profileId" => Session::get("profileId"),
            "status" => 1,
        ];

        $response = $this->callMDarasaAPIPostWithToken($addQuestionPayload, $addQuestionUrl);

        if (!is_null($response) && $response->Success) {

            Session::flash('success', 'Your question has been submitted to the instructor!');
            return redirect()->back();

        } else {

            Session::flash('error', 'We could not add the question due to an error on our
             side. Please try again later');
            return redirect()->back();
        }

    }

    public function editQuestion($courseUnitId, $questionId, $subtopicId)
    {

        if (!$this->webAuth()) {
            Session::flash('error', 'Please login to access this page');
            return redirect("/");
        }

        $categoriesUrl = "/categories";
        $categories = $this->callMDarasaAPIGetWithoutToken($categoriesUrl);

        $getQuestionUrl = "/subtopic/question";

        $findQuestionPayload = [
            "subtopicQuestionId" => $questionId,
        ];

        $response = $this->callMDarasaAPIPostWithToken($findQuestionPayload, $getQuestionUrl);

        if (!is_null($response)) {

            $subtopicQuestion = $response->Data;

            $subtopicQuestionsUrl = "/subtopic/questions/filter";

            $subtopicQuestionsPayload = [
                "courseUnitSubtopicId" => $subtopicQuestion->courseUnitSubtopicId,
            ];

            $subtopicQuestions = $this->callMDarasaAPIPostWithToken(
                $subtopicQuestionsPayload,
                $subtopicQuestionsUrl
            );

            if (!is_null($subtopicQuestions)) {

                $subtopicQuestions = $subtopicQuestions->Data;
            }

            return view(
                'student.subtopic-questions',
                compact(
                    'categories',
                    'subtopicQuestion',
                    'courseUnitId',
                    'subtopicQuestions',
                    'subtopicId'
                )
            );

        } else {

            Session::flash('error', 'No question was found. Cannot update question');
            return redirect()->back();
        }
    }

    public function updateQuestion(Request $request)
    {

        if (!$this->webAuth()) {
            Session::flash('error', 'Please login to access this page');
            return redirect("/");
        }

        $updateQuestionUrl = "/subtopic/question/update";

        $updateQuestionPayload = [
            "subtopicQuestionId" => $request->subtopicQuestionId,
            "question" => $request->question,
            "courseUnitSubtopicId" => $request->courseUnitSubtopicId,
            "profileId" => Session::get("profileId"),
            "status" => 1,
        ];

        $response = $this->callMDarasaAPIPostWithToken($updateQuestionPayload, $updateQuestionUrl);

        if (!is_null($response) && $response->Success) {

            Session::flash('success', 'Your question has been updated successfully!');
            return redirect("/unit/subtopic/questions/" . $request->courseUnitId . "/" . $request->courseUnitSubtopicId);

        } else {

            Session::flash('error', 'We could not update the question due to an error on our
             side. Please try again later');
            return redirect()->back();
        }
    }

    public function deleteQuestion(Request $request)
    {

        if (!$this->webAuth()) {
            Session::flash('error', 'Please login to access this page');
            return redirect("/");
        }

        $deleteQuestionUrl = "/subtopic/question/delete";

        $deleteQuestionPayload = [
            "subtopicQuestionId" => $request->subtopicQuestionId,
        ];

        $response = $this->callMDarasaAPIPostWithToken($deleteQuestionPayload, $deleteQuestionUrl);

        if (!is_null($response) && $response->Success) {

            Session::flash('success', 'Question deleted successfully!');
            return redirect()->back();

        } else {

            Session::flash('error', 'We could not delete the question due to an error on our
             side. Please try again later');
            return redirect()->back();
        }

    }

    public function completeOrder(Request $request)
    {

        if (!$this->webAuth()) {
            Session::flash('error', 'Please login to access this page');
            return redirect("/");
        }

        $balance = 0.0;
        $profileId = Session::get("profileId");
        $totalOrderValue = $request->totalOrderValue;

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
                "studentOrderId" => $request->studentOrderId,
            ];

            $response = $this->callMDarasaAPIPostWithToken($payFromWalletPayload, $paymentUrl);

            if (!is_null($response)) {

                if ($response->Success) {

                    Session::flash('success', 'Congratulations!! Your order is completed successfully');
                } else {

                    Session::flash('error', 'Sorry, we encountered an error and therefore
                     couldn\'t complete your transaction.');
                }
            }

        } else {

            $stkPushUrl = "/deposit/stk";

            $stkPayload = [
                "orderId" => $request->studentOrderId,
                "msisdn" => Session::get("msisdn"),
                "amount" => $totalOrderValue,
                "profileId" => $profileId,
            ];

            $response = $this->callMDarasaAPIPostWithToken($stkPayload, $stkPushUrl);

            if (!is_null($response)) {

                if ($response->Success) {

                    Session::flash('success', 'We have sent payment request to your MPESA phone.
                     Please authorize it to complete your order');
                } else {

                    Session::flash('error', 'Sorry, we encountered an error and therefore
                     couldn\'t complete your transaction.');
                }
            }

            return redirect()->back();
        }
    }

    public function search(Request $request)
    {

        $categoriesUrl = "/categories";
        $categories = $this->callMDarasaAPIGetWithoutToken($categoriesUrl);

        $searchUrl = "/course/units/search";

        $searchPayload = [
            "pattern" => $request->keyword,
        ];

        $response = $this->callMDarasaAPIPostWithoutToken($searchPayload, $searchUrl);
        if (!is_null($response)) {

            if ($response->Success) {

                $searchedCourseUnits = $response->Data;

                return view('search', compact('categories', 'searchedCourseUnits'));
            } else {

                Session::flash('error', 'An error occured and no search items were retrieved');
            }
        }
    }

    public function uploadProfilePhoto(Request $request)
    {

        if (!$this->webAuth()) {

            Session::flash('error', 'Please login to access this page');
            return redirect("/");
        }

        $profileId = Session::get("profileId");

        $profilePhotoPayload = [
            "profileId" => $profileId,
        ];

        if (isset($request->profilePhoto)) {

            $profilePhotoFile = $request->profilePhoto;

            $cfile = new CURLFile(
                $profilePhotoFile->getRealPath(),
                $profilePhotoFile->getMimeType(),
                $profilePhotoFile->getClientOriginalName()
            );

            $profilePhotoPayload["profilePhoto"] = $cfile;
        } else {

            Session::flash('error', 'Please upload a valid photo to proceed');
            return redirect()->back();
        }

        $profilePhotoUrl = "/profile/photo/save";
        $response = $this->callMDarasaAPIPostFormDataWithToken($profilePhotoPayload, $profilePhotoUrl);

        if (!is_null($response)) {

            if ($response->Success && $response->Data->profilePhoto) {

                Session::flash('success', 'Your profile photo has been set successfully!');
            } else {

                Session::flash('error', 'Sorry, we encountered an error and therefore couldn\'t upload your photo.');

            }
        }
        return redirect()->back();
    }

    public function contactUs()
    {
        $categoriesUrl = "/categories";
        $categories = $this->callMDarasaAPIGetWithoutToken($categoriesUrl);

        return view('contact-us', compact('categories'));
    }

    public function terms()
    {
        $categoriesUrl = "/categories";
        $categories = $this->callMDarasaAPIGetWithoutToken($categoriesUrl);

        return view('terms', compact('categories'));
    }

    public function privacy()
    {

        $categoriesUrl = "/categories";
        $categories = $this->callMDarasaAPIGetWithoutToken($categoriesUrl);

        return view('privacy-policy', compact('categories'));
    }

    public function forgotPassword()
    {
        $categoriesUrl = "/categories";
        $categories = $this->callMDarasaAPIGetWithoutToken($categoriesUrl);

        return view('forgot-password', compact('categories'));
    }

    public function resetPassword(Request $request)
    {

        $generateTokenUrl = "/auth/token";

        $tokenPayload = [
            "email" => $request->email,
        ];

        $response = $this->callMDarasaAPIPostWithoutToken($tokenPayload, $generateTokenUrl);

        if (!is_null($response)) {

            if ($response->Success) {

                Session::flash('success', 'We have sent a reset password token to your email address!');

                return redirect('/password/new');
            } else {

                Session::flash('error', 'Sorry, we encountered an error and therefore
                     couldn\'t send a reset password token.');

                return redirect()->back();

            }
        }

    }

    public function newPasswordView()
    {

        $categoriesUrl = "/categories";
        $categories = $this->callMDarasaAPIGetWithoutToken($categoriesUrl);

        return view('new-password', compact('categories'));
    }

    public function newPassword(Request $request)
    {

        if ($request->newPassword != $request->confPassword) {

            Session::flash('error', 'Your passwords do not match.Please retry!');
            return redirect()->back();
        }

        $setNewPasswordUrl = "/auth/setnewpassword";

        $newPasswordPayload = [
            "email" => $request->email,
            "token" => $request->token,
            "newPassword" => $request->newPassword,
        ];

        $response = $this->callMDarasaAPIPostWithoutToken($newPasswordPayload, $setNewPasswordUrl);

        if (!is_null($response)) {

            if ($response->Success) {

                Session::flash('success', 'Your password has been reset successfully');

                return redirect('/');
            } else {

                Session::flash('error', 'Sorry, we encountered an error and therefore
                     couldn\'t reset your password.');

                return redirect()->back();

            }
        }

    }
}