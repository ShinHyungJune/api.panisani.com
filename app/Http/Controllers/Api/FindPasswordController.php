<?php

namespace App\Http\Controllers\Api;

use App\Enums\KakaoTemplate;
use App\Http\Controllers\Controller;
use App\Mail\PasswordResetCreated;
use App\Mail\VerifyNumberCreated;
use App\Models\Kakao;
use App\Models\PasswordReset;
use App\Models\User;
use App\Models\VerifyNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class FindPasswordController extends ApiController
{
    public function index()
    {

    }

    public function update(Request $request)
    {
        $request->validate([
            "password_new" => "required|string|max:500|confirmed",
            "token" => "required|string|max:5000"
        ]);

        $passwordReset = PasswordReset::where("token", $request->token)->first();

        if(!$passwordReset)
            return throw ValidationException::withMessages([
                "token" => [
                    "유효하지 않은 토큰입니다."
                ]
            ]);

        $user = User::where("email", $passwordReset->ids)->first();

        if(!$user)
            return throw ValidationException::withMessages([
                "token" => [
                    "유효하지 않은 계정정보입니다."
                ]
            ]);

        $user->update(["password" => Hash::make($request->password_new)]);

        return $this->respondSuccessfully();
    }

    public function store(Request $request)
    {
        $request->validate([
            "email" => "required|string|max:500",
        ]);

        $verifyNumber = VerifyNumber::where('contact', $request->email)
            ->where('verified', true)->first();

        if(!$verifyNumber)
            return throw ValidationException::withMessages([
                "email" => [
                    "인증된 정보로만 진행할 수 있습니다."
                ]
            ]);

        if(!User::where("email", $request->email)->exists())
            return throw ValidationException::withMessages([
                "email" => [
                    "유효하지 않은 계정정보입니다."
                ]
            ]);

        $token = random_int(100000000,999999999);

        $passwordReset = PasswordReset::where("ids", $request->email)->first();

        $passwordReset ? $passwordReset->update([
            "ids" => $request->email,
            "token" => $token
        ]) : $passwordReset = PasswordReset::create([
            "ids" => $request->email,
            "token" => $token
        ]);

        Mail::to($request->email)->send(new PasswordResetCreated($passwordReset));

        $verifyNumber->delete();

        return $this->respondSuccessfully([

        ]);
    }
}
