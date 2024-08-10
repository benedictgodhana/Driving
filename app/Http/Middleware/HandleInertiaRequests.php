<?php
namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => function () use ($request) {
                    if ($user = $request->user()) {
                        $profile = $user->profile;
                        return array_merge($user->only('id', 'full_name', 'email',  'username'), [
                            'roles' => $user->getRoleNames(),
                            'permissions' => $user->getAllPermissions()->pluck('name'),
                            'profile' => $profile ? $profile->only('address', 'phone_number', 'profile_picture', 'role', 'department', 'course', 'year_of_study', 'faculty', 'bio','gender','religion') : null,
                        ]);
                    }
                    return null;
                },
            ],
        ]);
    }
}
