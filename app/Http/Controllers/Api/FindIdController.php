<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VerifyNumber;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class FindIdController extends ApiController
{
    public function store(Request $request)
    {
        $request->validate([
            "email" => "required|string|max:500"
        ]);

        $verifyNumber = VerifyNumber::where('contact', $request->email)
            ->where('verified', true)->first();

        if(!$verifyNumber)
            return throw ValidationException::withMessages([
                "email" => [
                    "인증된 정보로만 사용할 수 있습니다."
                ]
            ]);

        $verifyNumber->delete();

        $user = User::where("email", $request->email)->first();

        if(!$user)
            return throw ValidationException::withMessages([
                "email" => [
                    "해당 정보로 가입된 계정이 없습니다."
                ]
            ]);

        if(!$user->ids && $user->social_platform)
            return throw ValidationException::withMessages([
                "email" => [
                    $user->getFormatSocial()." 소셜 간편가입으로 가입된 계정입니다. 소셜로그인으로 로그인해주세요."
                ]
            ]);


        return $this->respondSuccessfully([
            "email" => $user->email
        ]);
    }
}
