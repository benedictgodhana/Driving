    <?php

    use App\Http\Controllers\AdminController;
    use App\Http\Controllers\Auth\RegisteredUserController;
    use App\Http\Controllers\ProfileController;
    use App\Http\Controllers\ContributionController;
    use App\Http\Controllers\FeedbackController;
    use Illuminate\Foundation\Application;
    use Illuminate\Support\Facades\Route;
    use Inertia\Inertia;
    use App\Http\Controllers\UserController;
    use App\Http\Controllers\RoleController;
    use App\Http\Controllers\memberController;
    use App\Http\Controllers\ApplicationController;
    use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\StationController;

    /*
    |--------------------------------------------------------------------------
    | Web Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register web routes for your application.
    |
    */

    Route::get('/', function () {
        return Inertia::render('Welcome', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
        ]);
    });

    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::get('/feedback/print', [FeedbackController::class, 'print'])->name('feedback.print');
        Route::get('/feedback/export', [FeedbackController::class, 'export'])->name('feedback.export');
    });

    Route::middleware(['auth', 'role:student'])->group(function () {



        Route::get('/new_application', [memberController::class, 'new_application'])->name('contribution');
        Route::get('/notifications', [memberController::class, 'notifications'])->name('notifications');
        Route::get('/settings', [memberController::class, 'settings'])->name('settings');

        Route::get('/institutions', [memberController::class, 'getFilteredInstitutions']);
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('/applications', [ApplicationController::class, 'submit'])->name('applications.store');


    });

    Route::middleware(['auth', 'role:admin'])->group(function () {

        Route::post('/applications/approve', [ApplicationController::class, 'approve']);

        Route::post('/applications/update', [ApplicationController::class, 'update']);

        Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');

        Route::get('/all-feedback', [FeedbackController::class, 'AllFeedback'])->name('feedback.index');

        Route::get('/profile', [AdminController::class, 'adminProfile'])->name('profile');

        Route::get('/my_contribution', [AdminController::class, 'adminContribution'])->name('admincontribution');

        Route::get('/my_notifications', [AdminController::class, 'adminNotification'])->name('adminnotification');

        Route::get('/my_settings', [AdminController::class, 'adminSetting'])->name('adminsettings');


        // routes/web.php
        Route::post('/stations/create', [StationController::class, 'create'])->name('stations.create');

        // In web.php
        Route::get('/feedback/category/{categoryId}', [FeedbackController::class, 'show'])->name('category.feedback');


        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/courses', [AdminController::class, 'courses'])->name('courses.index');
        Route::get('/stations', [AdminController::class, 'stations'])->name('stations.index');
        Route::get('/students', [AdminController::class, 'students'])->name('students.index');
        Route::get('/payments', [AdminController::class, 'payments'])->name('payments.index');
        Route::get('/vehicles', [AdminController::class, 'vehicles'])->name('vehicles.index');
        Route::get('/instructors', [AdminController::class, 'instructors'])->name('instructors.index');
        Route::get('/lessons', [AdminController::class, 'lessons'])->name('lessons.index');
        Route::post('/instructors', [InstructorController::class, 'store'])->name('instructors.store');
        Route::put('/instructors/{id}', [InstructorController::class, 'update'])->name('instructors.update');
        Route::delete('/instructors/{id}', [InstructorController::class, 'destroy']);









        Route::get('/manage-roles', [RoleController::class, 'index'])->name('roles.index');


        Route::get('/graph', [AdminController::class, 'graph']);

    });

    // Routes for additional pages
    Route::get('/about', function () {
        return Inertia::render('About');
    })->name('about');

    Route::get('/contact', function () {
        return Inertia::render('Contact');
    })->name('contact');

    Route::get('/gallery', function () {
        return Inertia::render('Gallery');
    })->name('gallery');

    Route::get('/updates', function () {
        return Inertia::render('Updates');
    })->name('updates');

    // Registration routes
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store'])->name('register.store');

    Route::get('/give_feedback', [FeedbackController::class, 'GivefeedbackPage'])->name('feedback');


    require __DIR__ . '/auth.php';
