<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset security question verification.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'security_answer' => ['required', 'string'],
        ]);

        // Check if the security answer matches
        if (strtolower($request->input('security_answer')) === 'kochenk') {
            // Mark as verified in session
            $request->session()->put('security_verified', true);
            return redirect()->route('password.reset', ['token' => 'verified']);
        }

        return back()->withInput($request->only('security_answer'))
                    ->withErrors(['security_answer' => 'The security answer is incorrect. Please try again.']);
    }
}
