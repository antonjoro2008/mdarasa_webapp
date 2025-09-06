<?php

use App\Http\Controllers\AccountsController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LecturerController;
use App\Http\Controllers\MpesaController;
use App\Http\Controllers\UssdController;
use App\Http\Controllers\OrdersController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */
Route::get('/', [HomeController::class, 'index'])->name('welcome');
Route::get('/teach', [HomeController::class, 'teach'])->name('teach');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'registerStudent'])->name('registerStudent');
Route::post('/register/lecturer', [AuthController::class, 'registerLecturer'])->name('registerLecturer');
Route::post('/register/institution', [AuthController::class, 'registerInstitution'])->name('registerInstitution');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/account', [AccountsController::class, 'index'])->name('accounts');
Route::get('/orders', [OrdersController::class, 'index'])->name('orders');
Route::get('/course-units/{categoryId}', [HomeController::class, 'courseUnits'])->name('courseUnits');
Route::get('/category/course-units/{categoryId}', [HomeController::class, 'categoryUnits'])->name('categoryUnits');
Route::get('/course/unit/{courseUnitId}', [HomeController::class, 'getCourseUnit'])->name('getCourseUnit');
Route::get('/course/student/unit/{courseUnitId}', [HomeController::class, 'getStudentCourseUnit'])
    ->name('getStudentCourseUnit');
Route::get('/student/topic/notes', [HomeController::class, 'viewTopicNotes'])->name('viewTopicNotes');
Route::get('/student/subtopic/notes', [HomeController::class, 'viewSubtopicNotes'])->name('viewSubtopicNotes');
Route::get('/student/topic/content', [HomeController::class, 'launchTopicVideo'])->name('launchTopicVideo');
Route::get('/student/subtopic/content', [HomeController::class, 'viewSubtopicVideo'])->name('viewSubtopicVideo');
Route::get('/student/play-video/{fileName}', [HomeController::class, 'playVideo'])->name('playVideo');
Route::get('/lecturer/play-video/{fileName}', [LecturerController::class, 'playLecturerVideo'])->name('playLecturerVideo');
Route::get('/terms', [HomeController::class, 'terms'])->name('terms');
Route::get('/privacy', [HomeController::class, 'privacy'])->name('privacy');
Route::post('/student/order/complete', [HomeController::class, 'completeOrder'])->name('completeOrder');
Route::get('/search', [HomeController::class, 'search'])->name('search');
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::post('/checkout', [CartController::class, 'checkout'])->name('checkout');
Route::post('/deposit', [AccountsController::class, 'deposit'])->name('deposit');
Route::post('/withdraw', [AccountsController::class, 'withdraw'])->name('withdraw');
Route::get('/my-units', [HomeController::class, 'myUnits'])->name('myUnits');
Route::get('/forgot-password', [HomeController::class, 'forgotPassword'])->name('forgotPassword');
Route::post('/password/reset', [HomeController::class, 'resetPassword'])->name('resetPassword');
Route::get('/password/new', [HomeController::class, 'newPasswordView'])->name('newPasswordView');
Route::post('/password/new', [HomeController::class, 'newPassword'])->name('newPassword');
Route::get('/unit/subtopic/questions/{courseUnitId}/{subtopicId}', [HomeController::class, 'subtopicQuestions'])
    ->name('subtopicQuestions');
Route::post('/subtopic/add-question', [HomeController::class, 'addQuestion'])->name('addQuestion');
Route::get(
    '/subtopic/question/edit/{courseUnitId}/{questionId}/{subtopicId}',
    [HomeController::class, 'editQuestion']
)->name('editQuestion');
Route::post('/subtopic/update-question', [HomeController::class, 'updateQuestion'])->name('updateQuestion');
Route::post('/subtopic/question/delete', [HomeController::class, 'deleteQuestion'])->name('deleteQuestion');
Route::get('/category/lecturer/{categoryId}/{lecturerId}', [HomeController::class, 'lecturerUnits'])
    ->name('lecturerUnits');
Route::get('/contact-us', [HomeController::class, 'contactUs'])->name('contactUs');
Route::get('/lecturer', [LecturerController::class, 'index'])->name('lecturer');
Route::post('/lecturer/remove-course', [LecturerController::class, 'deleteCourse'])->name('deleteCourse');
Route::post('/lecturer/add-course', [LecturerController::class, 'addCourse'])->name('addCourse');
Route::get('/course/edit/{courseId}', [LecturerController::class, 'editCourse'])->name('editCourse');
Route::post('/lecturer/update-course', [LecturerController::class, 'updateCourse'])->name('updateCourse');
Route::get('/lecturer/units/{courseId?}', [LecturerController::class, 'lecturerUnits'])->name('lecturerUnits');
Route::get('/course/unit/edit/{courseUnitId}', [LecturerController::class, 'editCourseUnit'])->name('editCourseUnit');
Route::post('/lecturer/add-unit', [LecturerController::class, 'addCourseUnit'])->name('addCourseUnit');
Route::post('/lecturer/update-unit', [LecturerController::class, 'updateCourseUnit'])->name('updateCourseUnit');
Route::get('/lecturer/account', [LecturerController::class, 'account'])->name('lecturerAccount');
Route::get('/lecturer/topics', [LecturerController::class, 'allTopics'])->name('allTopics');
Route::get('/lecturer/topics/{id?}', [LecturerController::class, 'topics'])->name('topics');
Route::get('/unit/topic/edit/{topicId}/{type}', [LecturerController::class, 'editUnitTopic'])->name('editUnitTopic');
Route::post('/lecturer/add-topic', [LecturerController::class, 'addUnitTopic'])->name('addUnitTopic');
Route::post('/lecturer/update-topic', [LecturerController::class, 'updateUnitSubtopic'])->name('updateUnitSubtopic');
Route::post('/lecturer/remove-topic', [LecturerController::class, 'removeTopic'])->name('removeTopic');
Route::get('/lecturer/subtopics/{id?}', [LecturerController::class, 'subtopics'])->name('subtopics');
Route::post('/lecturer/add-subtopic', [LecturerController::class, 'addUnitSubtopic'])->name('addUnitSubtopic');
Route::post('/lecturer/update-subtopic', [LecturerController::class, 'updateUnitSubtopic'])->name('updateUnitSubtopic');
Route::post('/subtopic/video/add', [LecturerController::class, 'addSubtopicVideoV2'])->name('addSubtopicVideoV2');
Route::post('/subtopic/video/thirdparty', [LecturerController::class, 'addSubtopicThirdPartyVideo'])->name('addSubtopicThirdPartyVideo');
Route::post('/subtopic/notes/add', [LecturerController::class, 'addSubtopicNotes'])->name('addSubtopicNotes');
Route::post('/subtopic/video/remove', [LecturerController::class, 'removeSubtopicVideo'])->name('removeSubtopicVideo');
Route::post('/subtopic/notes/remove', [LecturerController::class, 'removeSubtopicNotes'])->name('removeSubtopicNotes');
Route::get('/unit/subtopic/resources/{subtopicId}', [LecturerController::class, 'subtopicResources'])
    ->name('subtopicResources');
Route::get('/unit/subtopic/edit/{subtopicId}/{type}', [LecturerController::class, 'editUnitSubtopic'])
    ->name('editUnitSubtopic');
Route::post('/lecturer/remove-subtopic', [LecturerController::class, 'removeSubtopic'])
    ->name('removeSubtopic');
Route::get('/lecturer/orders', [LecturerController::class, 'orders'])->name('lecturerOrders');
Route::get('/lecturer/my-students', [LecturerController::class, 'myStudents'])->name('myStudents');
Route::post('/lecturer/remove-unit', [LecturerController::class, 'removeUnit'])->name('removeUnit');
Route::get('/lecturer/course/unit/{courseUnitId}', [LecturerController::class, 'getLecturerCourseUnit'])
    ->name('getLecturerCourseUnit');
Route::get('/lecturer/topic/notes', [LecturerController::class, 'viewTopicNotes'])->name('viewTopicNotes');
Route::get('/lecturer/subtopic/notes', [LecturerController::class, 'viewSubtopicNotes'])->name('viewSubtopicNotes');
Route::get('/lecturer/topic/content', [LecturerController::class, 'launchTopicVideo'])->name('launchTopicVideo');
Route::get('/lecturer/subtopic/content', [LecturerController::class, 'viewSubtopicVideo'])->name('viewSubtopicVideo');
Route::get('/lecturer/subtopic/questions/{courseUnitId}/{subtopicId}', [LecturerController::class, 'subtopicQuestions'])
    ->name('lecturerSubtopicQuestions');
Route::post('/lecturer/question/answer', [LecturerController::class, 'answerQuestion'])->name('answerQuestion');
Route::get('/admin', [AdminController::class, 'index'])->name('adminHome');
Route::post('/admin/login', [AdminController::class, 'loginAdmin'])->name('loginAdmin');
Route::get('/admin/profile/{profileId}', [AdminController::class, 'viewProfileAccount'])->name('viewProfileAccount');
Route::get('/admin/course-units', [AdminController::class, 'getCourseUnits'])->name('getCourseUnits');
Route::get('/course/units/featured', [AdminController::class, 'getFeaturedCourseUnits'])
    ->name('getFeaturedCourseUnits');
Route::get('/admin/lecturers', [AdminController::class, 'getLecturers'])->name('getLecturers');
Route::get('/admin/students', [AdminController::class, 'getStudents'])->name('getStudents');
Route::get('/admin/orders', [AdminController::class, 'getStudentOrders'])->name('getStudentOrders');
Route::get('/admin/users', [AdminController::class, 'getUsers'])->name('getUsers');
Route::post('/admin/add-user', [AdminController::class, 'addAdminUser'])->name('addAdminUser');
Route::get('/admin/deposits', [AdminController::class, 'getDeposits'])->name('getDeposits');
Route::get('/admin/withdrawals', [AdminController::class, 'getWithdrawals'])->name('getWithdrawals');
Route::post('/admin/unit/unpublish', [AdminController::class, 'unpublishCourseUnit'])->name('unpublishCourseUnit');
Route::post('/admin/unit/publish', [AdminController::class, 'publishCourseUnit'])->name('publishCourseUnit');
Route::post('/admin/commission/rate', [AdminController::class, 'updateCommissionRate'])->name('updateCommissionRate');
Route::post('/admin/unit/unfeature', [AdminController::class, 'unfeatureCourseUnit'])->name('unfeatureCourseUnit');
Route::post('/admin/unit/feature', [AdminController::class, 'featureCourseUnit'])->name('featureCourseUnit');
Route::get('/admin/course/unit/{courseUnitId}', [AdminController::class, 'getCourseUnit'])
    ->name('getLecturerCourseUnit');
Route::get('/admin/topic/notes', [AdminController::class, 'viewTopicNotes'])->name('viewTopicNotes');
Route::get('/admin/subtopic/notes', [AdminController::class, 'viewSubtopicNotes'])->name('viewSubtopicNotes');
Route::get('/admin/topic/content', [AdminController::class, 'launchTopicVideo'])->name('launchTopicVideo');
Route::get('/admin/subtopic/content', [AdminController::class, 'viewSubtopicVideo'])->name('viewSubtopicVideo');

Route::post('/profile/photo/upload', [HomeController::class, 'uploadProfilePhoto'])->name('uploadProfilePhoto');

//MPESA Backup Route
Route::post('/students/c2b/mdarasapaymentengine', [MpesaController::class, 'mpesaC2BConfirm'])->name('mpesaC2BConfirm');
Route::post('/students/c2b/validatepayment', [MpesaController::class, 'mpesaC2BValidate'])->name('mpesaC2BValidate');

Route::post('/revenue/c2b/paymentengine', [MpesaController::class, 'revenueC2BConfirm'])->name('revenueC2BConfirm');
Route::post('/revenue/c2b/validatepayment', [MpesaController::class, 'revenueC2BValidate'])->name('revenueC2BValidate');
Route::post('/revenue/deposit/stk', [MpesaController::class, 'lipaNaMPESAOnlineSTK'])->name('lipaNaMPESAOnlineSTK');

//USSD
Route::post('/revenue/ussd', [UssdController::class, 'ussdCallback'])->name('ussdCallback');

Route::post('/ilu/c2b/paymentengine', [MpesaController::class, 'iluC2BConfirm'])->name('revenueC2BConfirm');
Route::post('/ilu/c2b/validatepayment', [MpesaController::class, 'iluC2BValidate'])->name('revenueC2BValidate');
Route::post('/ilu/deposit/stk', [MpesaController::class, 'iluLipaNaMPESAOnlineSTK'])->name('iluLipaNaMPESAOnlineSTK');