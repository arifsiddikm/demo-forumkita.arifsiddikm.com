<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class ForgotPasswordController extends Controller
{
    public function showForm()
    {
        return view('auth.forgot-password');
    }

    public function sendLink(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            ['token' => Hash::make($token), 'created_at' => now()]
        );

        $resetUrl = route('password.reset', ['token' => $token]) . '?email=' . urlencode($request->email);

        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = 'smtp.hostinger.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'noreply@arifsiddikm.com';
            $mail->Password   = 'SatuDua345!!';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;
            $mail->setFrom('noreply@arifsiddikm.com', 'ForumKita');
            $mail->addAddress($request->email);
            $mail->isHTML(true);
            $mail->Subject = 'Reset Password - ForumKita';
            $mail->Body    = $this->emailTemplate($resetUrl);
            $mail->send();
        } catch (Exception $e) {
            return back()->with('error', 'Gagal mengirim email. Coba lagi nanti.');
        }

        return back()->with('success', 'Link reset password telah dikirim ke email Anda.');
    }

    public function showReset(Request $request, $token)
    {
        return view('auth.reset-password', ['token' => $token, 'email' => $request->email]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token'                 => 'required',
            'email'                 => 'required|email',
            'password'              => 'required|min:8|confirmed',
        ]);

        $record = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        if (!$record || !Hash::check($request->token, $record->token)) {
            return back()->withErrors(['email' => 'Token reset password tidak valid atau kadaluarsa.']);
        }

        if (now()->diffInMinutes($record->created_at) > 60) {
            return back()->withErrors(['email' => 'Token sudah kadaluarsa. Minta link baru.']);
        }

        User::where('email', $request->email)->update([
            'password' => Hash::make($request->password),
        ]);

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('success', 'Password berhasil direset! Silakan login.');
    }

    private function emailTemplate(string $url): string
    {
        return <<<HTML
        <div style="font-family:sans-serif;max-width:600px;margin:0 auto;background:#fff;border-radius:12px;overflow:hidden;border:1px solid #e5e7eb;">
            <div style="background:linear-gradient(135deg,#2563EB,#1d4ed8);padding:32px;text-align:center;">
                <h1 style="color:#fff;margin:0;font-size:24px;">🔐 ForumKita</h1>
            </div>
            <div style="padding:32px;">
                <h2 style="color:#1f2937;margin-top:0;">Reset Password Anda</h2>
                <p style="color:#6b7280;">Klik tombol di bawah untuk mereset password akun ForumKita Anda. Link ini berlaku selama 60 menit.</p>
                <div style="text-align:center;margin:32px 0;">
                    <a href="{$url}" style="background:#2563EB;color:#fff;padding:14px 32px;border-radius:8px;text-decoration:none;font-weight:600;display:inline-block;">Reset Password</a>
                </div>
                <p style="color:#9ca3af;font-size:13px;">Jika Anda tidak meminta reset password, abaikan email ini.</p>
                <hr style="border:none;border-top:1px solid #e5e7eb;margin:24px 0;">
                <p style="color:#9ca3af;font-size:12px;text-align:center;">© 2025 ForumKita. Semua hak dilindungi.</p>
            </div>
        </div>
        HTML;
    }
}
