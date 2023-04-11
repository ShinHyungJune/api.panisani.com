<?php

namespace App\Http\Controllers\Api;

use App\Enums\PointHistoryType;
use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\VerifyNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;

class UserController extends ApiController
{
    public function login(Request $request)
    {
        // 소셜로그인 시도 시
        if($request->token && auth()->user()){
            return $this->respondSuccessfully([
                "token" => $request->token,
                "user" => UserResource::make(auth()->user())
            ]);
        }

        $data = $request->validate([
            "email" => "required|email|max:500",
            "password" => "required|string|max:500",
        ]);

        if(auth()->attempt($request->only("email", "password"))) {
            session()->regenerate();

            $token = auth()->user()->createToken("auth");

            return $this->respondSuccessfully([
                "token" => $token->plainTextToken,
                "user" => UserResource::make(auth()->user())
            ]);
        }

        return throw ValidationException::withMessages([
            "email" => [
                __("socialLogin.invalid")
            ]
        ]);
    }

    public function show(User $user)
    {
        return $this->respondSuccessfully(UserResource::make($user));
    }

    public function store(Request $request)
    {
        // 소셜가입
        if($request->social_id)
            return $this->storeSocial($request);

        $request->validate([
            "email" => "required|email|max:500|unique:users",
            "nickname" => "required|unique:users|string|max:500",
            "password" => "required|string|max:500|min:8|confirmed",
            "birth" => "required|string|max:500",
            "img" => "required|file|max:20480", // 20MB
        ]);

        $verifyNumber = VerifyNumber::where('contact', $request->email)
            ->where('verified', true)->first();

        if(!$verifyNumber)
            return throw ValidationException::withMessages([
                "email" => [
                    "인증된 이메일만 사용할 수 있습니다."
                ]
            ]);

        $user = User::create(array_merge($request->all(), [
            "password" => Hash::make($request->password)
        ]));

        if($request->img)
            $user->addMedia($request->img)->toMediaCollection("img", "s3");

        $verifyNumber->delete();

        return $this->respondSuccessfully(UserResource::make($user), "성공적으로 가입되었습니다.");
    }

    public function storeSocial(Request $request)
    {
        $request->validate([
            "img" => "nullable|file|max:20480", // 20MB
            "email" => "required|email|max:500|unique:users",
            "nickname" => "required|unique:users|string|max:500",
            "password" => "required|string|max:500|min:8|confirmed",
            "birth" => "required|string|max:500",

            "social_id" => "required|string|max:50000",
            "social_platform" => "required|string|max:50000",
        ]);

        $verifyNumber = VerifyNumber::where('contact', $request->email)
            ->where('verified', true)->first();

        if(!$verifyNumber)
            return throw ValidationException::withMessages([
                "email" => [
                    "인증된 이메일만 사용할 수 있습니다."
                ]
            ]);

        $user = User::create($request->all());

        if($request->img)
            $user->addMedia($request->img)->toMediaCollection("img", "s3");

        $verifyNumber->delete();

        return $this->respondSuccessfully(UserResource::make($user), "성공적으로 가입되었습니다.");
    }

    public function openSocialLoginPop($social)
    {
        return Socialite::driver($social)->stateless()->redirect();
    }

    public function socialLogin(Request $request, $social)
    {
        $socialUser = Socialite::driver($social)->stateless()->user();

        // 일단 네이버
        $user = User::where("social_id", $socialUser->id)->where("social_platform", $social)->first();

        if(!$user) {
            // 소셜로그인에서 연락처 받을 시 해당 연락처 인증완료처리
            if($socialUser->email){
                VerifyNumber::create([
                    "contact" => $socialUser->email,
                    "number"=> "1234",
                    "verified" => true
                ]);
            }

            $socialUser->attributes["social_platform"] = $social;
            $socialUser->attributes["social_id"] = $socialUser->id;

            $attributes = json_encode($socialUser->attributes);

            return redirect(config("app.client_url") . "/users/create?socialUser={$attributes}");
        }

        $token = $user->createToken("auth")->plainTextToken;

        Auth::login($user);

        return redirect(config("app.client_url")."/socialLogin?token=".$token);
    }

    public function update(Request $request)
    {
        $request->validate([
            "img" => "nullable|file|max:20480", // 20MB
            // "email" => "required|email|max:500|unique:users,email".auth()->id(),
            "nickname" => "nullable|string|max:500|unique:users,nickname,".auth()->id(),
            "password" => "nullable|string|max:500|min:8",
            "password_new" => "nullable|string|max:500|min:8|confirmed",
            "birth" => "nullable|string|max:500",
            "description" => "nullable|string|max:500",
        ]);

        auth()->user()->update($request->except(["password"]));

        if($request->img)
            auth()->user()->addMedia($request->img)->toMediaCollection("img", "s3");

        if($request->password){
            if(!Hash::check($request->password, auth()->user()->password)){
                return throw ValidationException::withMessages([
                    "password" => [
                        "틀린 비밀번호입니다."
                    ]
                ]);
            };

            $request->validate([
                "password_new" => "string|confirmed|min:8"
            ]);

            auth()->user()->update(["password" => Hash::make($request->password_new)]);
        }

        return $this->respondSuccessfully(UserResource::make(auth()->user()));
    }

    public function destroy(Request $request)
    {
        $request->validate([
            "password" => "required|string|max:50000",
            "reason_leave_out" => "nullable|string|max:50000",
        ]);

        if(!Hash::check($request->password, auth()->user()->password)){
            return throw ValidationException::withMessages([
                "password" => [
                    "틀린 비밀번호입니다."
                ]
            ]);
        };

        auth()->user()->update([
            "reason_leave_out" => $request->reason_leave_out
        ]);

        auth()->user()->delete();

        return $this->respondSuccessfully();
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return $this->respondSuccessfully();
    }
}
