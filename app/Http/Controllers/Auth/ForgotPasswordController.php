<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class ForgotPasswordController extends Controller
{
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => 'Vui lòng nhập địa chỉ email.',
            'email.email' => 'Địa chỉ email không hợp lệ.',
        ]);

        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::ResetLinkSent) {
            return back()->with('status', 'Chúng tôi đã gửi liên kết đặt lại mật khẩu vào email của bạn. Vui lòng kiểm tra hộp thư.');
        }

        return back()->withErrors([
            'email' => match ($status) {
                Password::INVALID_USER => 'Không tìm thấy tài khoản với địa chỉ email này.',
                Password::RESET_THROTTLED => 'Vui lòng chờ một lúc trước khi yêu cầu lại.',
                default => 'Không thể gửi email lúc này. Vui lòng thử lại sau.',
            },
        ]);
    }
}
