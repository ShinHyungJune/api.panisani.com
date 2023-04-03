<?php

namespace App\Http\Controllers\Api;

use App\Enums\KakaoTemplate;
use App\Http\Controllers\Controller;
use App\Models\Kakao;
use App\Models\PasswordReset;
use App\Models\User;
use App\Models\VerifyNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class FindPasswordController extends ApiController
{
    public function index()
    {

    }

    public function update(Request $request)
    {
        $request->validate([
            "password" => "required|string|max:500",
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

        $user = User::where("ids", $passwordReset->ids)->first();

        if(!$user)
            return throw ValidationException::withMessages([
                "token" => [
                    "유효하지 않은 계정정보입니다."
                ]
            ]);

        if(!Hash::check($request->password, $user->password))
            return throw ValidationException::withMessages([
                "token" => [
                    "기존 비밀번호를 다시 확인해주세요."
                ]
            ]);

        $user->update(["password" => Hash::make($request->password_new)]);

        return $this->respondSuccessfully();
    }

    public function store(Request $request)
    {
        $request->validate([
            "ids" => "required|string|max:500",
            "contact" => "required|string|max:500"
        ]);

        $verifyNumber = VerifyNumber::where('contact', $request->contact)
            ->where('verified', true)->first();

        if(!$verifyNumber)
            return throw ValidationException::withMessages([
                "contact" => [
                    "인증된 전화번호로만 진행할 수 있습니다."
                ]
            ]);

        if(!User::where("contact", $request->contact)->where("ids", $request->ids)->exists())
            return throw ValidationException::withMessages([
                "ids" => [
                    "유효하지 않은 정보입니다."
                ]
            ]);

        $token = random_int(100000000,999999999);

        $passwordReset = PasswordReset::where("ids", $request->ids)->first();

        $passwordReset ? $passwordReset->update([
            "ids" => $request->ids,
            "token" => $token
        ]) : $passwordReset = PasswordReset::create([
            "ids" => $request->ids,
            "token" => $token
        ]);

        $verifyNumber->delete();

        return $this->respondSuccessfully([
            "token" => $passwordReset->token
        ]);
    }
}
