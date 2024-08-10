<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Feedback;
use App\Models\FeedbackCategory;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Notification;
use App\Models\Application;
use App\Models\Course;
use App\Models\Instructor;
use App\Models\Lesson;
use App\Models\PaymentMode;
use App\Models\Station;
use App\Models\Student;
use App\Models\StudentPayment;
use App\Models\Vehicle;

class AdminController extends Controller
{


    public function index()
    {
        // Fetch counts
        $totalUsers = User::count();
        $totalCourses = Course::count();
        $totalStations = Station::count();

        // Fetch count of students
        $totalStudents = Student::count();

        // Return data using Inertia
        return Inertia::render('Admin/AdminIndex', [
            'totalUsers' => $totalUsers,
            'totalCourses' => $totalCourses,
            'totalStations' => $totalStations,
            'totalStudents' => $totalStudents,
        ]);
    }
    public function users(): Response
    {
        $user = auth()->user(); // Assuming you're using Laravel's authentication
        $errors = []; // Example empty errors array

        return Inertia::render('Users', [
            'auth' => [
                'user' => $user,
            ],
            'errors' => $errors,
        ]);
    }

    public function manageRoles(): Response
    {
        return Inertia::render('ManageRoles');
    }



    public function courses()
    {
        $courses = Course::with(['user'])->get(); // Fetch all applications with related data

        return Inertia::render('Admin/Course', [
            'courses' => $courses,
        ]);
    }


    public function lessons()
    {
        // Fetch all lessons with related student, instructor, and vehicle
        $lessons = Lesson::with(['student', 'instructor', 'vehicle'])->get();

        // Fetch students with their courses
        $students = Student::with('course')->get();

        $instructors = Instructor::all();

        $vehicles = Vehicle::all();



        // Calculate total attended and total lessons per student
        $studentLessonsCount = $lessons->groupBy('student_id')->mapWithKeys(function ($lessons, $studentId) {
            return [
                $studentId => [
                    'total_attended' => $lessons->where('attended', true)->count(),
                    'total_lessons' => $lessons->count(),
                ]
            ];
        });

        // Calculate remaining lessons for each student
        $studentsRemainingLessons = $students->map(function ($student) use ($studentLessonsCount) {
            $totalLessonsRequired = $student->course->number_lessons ?? 0;
            $attendedLessons = $studentLessonsCount->get($student->id, ['total_attended' => 0])['total_attended'];
            $remainingLessons = $totalLessonsRequired - $attendedLessons;

            return [
                'student' => $student,
                'remaining_lessons' => $remainingLessons,
            ];
        });

        return Inertia::render('Admin/Lessons', [
            'lessons' => $lessons,
            'students'=>$students,
            'vehicles'=>$vehicles,
            'instructors'=>$instructors,
            'studentsRemainingLessons' => $studentsRemainingLessons,
        ]);
    }

    public function vehicles()
    {
        $vehicle = Vehicle::with(['user'])->get(); // Fetch all applications with related data

        return Inertia::render('Admin/Vehicle', [
            'vehicles' => $vehicle,
        ]);
    }


    public function instructors()
    {
        $instructor = Instructor::with(['user'])->get(); // Fetch all applications with related data

        return Inertia::render('Admin/Instructor', [
            'instructors' => $instructor,
        ]);
    }



    public function stations()
    {
        $stations = Station::all(); // Fetch all applications with related data

        return Inertia::render('Admin/Stations', [
            'stations' => $stations,
        ]);
    }



    public function students()
    {
        // Fetch all students with their related course data
        $students = Student::with('course','user')->get();

        $courses=Course::all();

        return Inertia::render('Admin/Students', [
            'students' => $students,
            'courses'=>$courses,
        ]);
    }


    public function payments()
{
    $payments = StudentPayment::with('student.course', 'paymentMode', 'user')->get();

    $paymentModes = PaymentMode::all();

    $students = Student::with('course')->get();

    $studentsWithBalance = $students->map(function ($student) use ($payments) {
        $totalPayments = $payments->where('student_id', $student->id)->sum('payment_amount');

        if ($student->course && isset($student->course->amount)) {
            $balance = $student->course->amount - $totalPayments;
        } else {
            $balance = null;
        }

        $student->balance = $balance;

        return $student;
    });


    return Inertia::render('Admin/Payments', [
        'payments' => $payments,
        'paymentModes' => $paymentModes,
        'students' => $studentsWithBalance,
    ]);
}



    public function dashboard(): Response
    {
        return Inertia::render('Dashboard');
    }

    public function adminProfile(): Response
    {
        return Inertia::render('Admin/Profile');
    }



    public function adminCourses(): Response
    {
        return Inertia::render('Admin/Courses');
    }

    public function adminNotification(): Response
    {
        $notifications = Notification::with(['feedback.category', 'feedback.subcategory'])->get();
        $feedbacks = Application::with('user', 'applicationType', 'location', 'institution','activity')->get(); // Ensure you have a Feedback model

        return Inertia::render('Admin/Notifications', [
            'notifications' => $notifications,
            'feedbacks' => $feedbacks,
        ]);
    }



    public function adminSetting(): Response
    {
        return Inertia::render('Admin/Settings');
    }


    public function categoryFeedback($id)
    {
        // Fetch feedbacks for a specific category
        $category = FeedbackCategory::findOrFail($id);
        $feedbacks = $category->feedbacks;

        return Inertia::render('Feedback/CategoryFeedback', [
            'category' => $category,
            'feedbacks' => $feedbacks,
        ]);
    }

}
