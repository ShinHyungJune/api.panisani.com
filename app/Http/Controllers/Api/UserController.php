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
            "ids" => "required|string|max:500",
            "password" => "required|string|max:500",
        ]);

        if(auth()->attempt($request->only("ids", "password"))) {
            session()->regenerate();

            $token = auth()->user()->createToken("auth");

            return $this->respondSuccessfully([
                "token" => $token->plainTextToken,
                "user" => UserResource::make(auth()->user())
            ]);
        }

        return throw ValidationException::withMessages([
            "ids" => [
                __("socialLogin.invalid")
            ]
        ]);
    }

    public function store(Request $request)
    {
        // 소셜가입
        if($request->social_id)
            return $this->storeSocial($request);

        $request->validate([
            "ids" => "required|string|max:500|unique:users",
            "name" => "required|string|max:500",
            "password" => "required|string|max:500|min:8|confirmed",

            "sex" => "nullable|string|max:500",
            "birth" => "nullable|string|max:500",
            "contact" => "required|string|max:500|unique:users",
            "email" => "nullable|email|max:500",
            "ids_recommend" => "nullable|string|max:500",

            "address" => "nullable|string|max:500",
            "address_detail" => "nullable|string|max:500",
            "address_zipcode" => "nullable|string|max:500",
        ]);

        $recommendedUser = null;

        $verifyNumber = VerifyNumber::where('contact', $request->contact)
            ->where('verified', true)->first();

        if(!$verifyNumber)
            return throw ValidationException::withMessages([
                "contact" => [
                    "인증된 전화번호만 사용할 수 있습니다."
                ]
            ]);

        if($request->ids_recommend) {
            $recommendedUser = User::where("ids", $request->ids_recommend)->first();

            if(!$recommendedUser)
                return throw ValidationException::withMessages([
                    "ids_recommend" => [
                        "존재하지 않는 추천인입니다."
                    ]
                ]);
        }

        $user = User::create(array_merge($request->all(), [
            "password" => Hash::make($request->password)
        ]));

        if($recommendedUser){
            $recommendedUser->changePoint(PointHistoryType::RECOMMEND);
            $user->changePoint(PointHistoryType::RECOMMEND);
        }

        $user->changePoint(PointHistoryType::REGISTER);

        $verifyNumber->delete();

        return $this->respondSuccessfully(UserResource::make($user), "성공적으로 가입되었습니다.");
    }

    public function storeSocial(Request $request)
    {
        $request->validate([
            "name" => "required|string|max:500",
            "sex" => "nullable|string|max:500",
            "birth" => "nullable|string|max:500",
            "contact" => "required|string|max:500|unique:users",
            "email" => "nullable|email|max:500",
            "ids_recommend" => "nullable|string|max:500",

            "address" => "nullable|string|max:500",
            "address_detail" => "nullable|string|max:500",
            "address_zipcode" => "nullable|string|max:500",

            "social_id" => "required|string|max:50000",
            "social_platform" => "required|string|max:50000",
        ]);

        $recommendedUser = null;

        $verifyNumber = VerifyNumber::where('contact', $request->contact)
            ->where('verified', true)->first();

        if(!$verifyNumber)
            return throw ValidationException::withMessages([
                "contact" => [
                    "인증된 전화번호만 사용할 수 있습니다."
                ]
            ]);

        if($request->ids_recommend) {
            $recommendedUser = User::where("ids", $request->ids_recommend)->first();

            if(!$recommendedUser)
                return throw ValidationException::withMessages([
                    "ids_recommend" => [
                        "존재하지 않는 추천인입니다."
                    ]
                ]);
        }

        $user = User::create($request->all());

        if($recommendedUser){
            $recommendedUser->changePoint(PointHistoryType::RECOMMEND);
            $user->changePoint(PointHistoryType::RECOMMEND);
        }

        $user->changePoint(PointHistoryType::REGISTER);

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
            if($socialUser->contact){
                VerifyNumber::create([
                    "contact" => $socialUser->contact,
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
            "sex" => "nullable|string|max:500",
            "email" => "nullable|email|max:500",

            "address" => "nullable|string|max:500",
            "address_detail" => "nullable|string|max:500",
            "address_zipcode" => "nullable|string|max:500",
        ]);

        auth()->user()->update($request->except(["password", "ids", "contact"]));

        return $this->respondSuccessfully();
    }

    public function destroy(Request $request)
    {
        $request->validate([
            "password" => "required|string|max:50000"
        ]);

        if(!Hash::check($request->password, auth()->user()->password)){
            return throw ValidationException::withMessages([
                "password" => [
                    "틀린 비밀번호입니다."
                ]
            ]);
        };

        auth()->user()->delete();

        return $this->respondSuccessfully();
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return $this->respondSuccessfully();
    }
}
