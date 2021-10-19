<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Auth;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Lang;

class LoginController extends Controller
{
    use ThrottlesLogins;

    /**
     * Максимальное число попыток входа
     * @var int
     */
    protected $maxAttempts = 5;

    /**
     * Время блокировки в минутах, при превышении числа неудачных попыток
     * @var int
     */
    protected $decayMinutes = 3;
    
    /**
     * Показ формы входа
     * 
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    /**
     * Обработка отправки формы авторизации
     * 
     * @param \Illuminate\Http\Request $request
     *
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        // Проверка запроса
        $request->validate([
            'email'   => 'required|email',
            'password' => 'required|min:5'
        ]);
        
        // Проверка максимального числа неудачных попыток авторизации
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            $this->sendLockoutResponse($request);
        }
        
        
        // Attempt to log the user in
        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
            $this->clearLoginAttempts($request);
            return redirect()->intended(route('admin.dashboard'));
        }

        // +1 к числу неудачных попыток
        $this->incrementLoginAttempts($request);
        
        // if unsuccessful, then redirect back to the login with the form data
        return redirect()
            ->back()
            ->withInput($request->only('email', 'remember'))
            ->withErrors([
                'password' => Lang::get('auth.failed'),
            ]);
    }

    /**
     * Выход
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();

        return redirect()->route('admin.login');
    }


    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'email';
    }

    /**
     * @inheritdoc
     */
    protected function sendLockoutResponse(Request $request)
    {
        $seconds = $this->limiter()->availableIn(
            $this->throttleKey($request)
        );

        throw ValidationException::withMessages([
            $this->username() => [
                Lang::get('auth.throttle_human', [
                    'time' => Carbon::now()->addSeconds($seconds)->diffForHumans()
                ])
            ],
        ])->status(423);
    }
}
