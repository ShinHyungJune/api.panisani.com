<?php

namespace App\Http\Controllers\Api;

use App\Enums\KakaoTemplate;
use App\Mail\VerifyNumberCreated;
use App\Models\Kakao;
use App\Models\User;
use App\Models\VerifyNumber;
use App\Models\SMS;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class VerifyNumberController extends ApiController
{
    public function store(Request $request)
    {
        $emailValidation = $request->isRegister ? "required|max:255|email|unique:users" : "required|max:255|email";

        $request->validate([
            "email" => $emailValidation,
        ]);

        $number = random_int(100000,999999);

        $verifyNumber = VerifyNumber::updateOrCreate([
            "contact" => $request->email
        ],[
            "contact" => $request->email,
            "number" => $number,
            "verified" => false
        ]);

        /*
        $kakao = new Kakao();

        $sms = new SMS();

        try{
            // $kakao->send($request->contact, ["number" => $number], KakaoTemplate::VERIFY_NUMBER);
            $sms->send("+82".$request->contact, "인증번호 [".$number."]");
        }catch(\Exception $exception){
            return $this->respondForbidden("잘못된 전화번호 형식입니다.");
        }
        */
        Mail::to($request->email)->send(new VerifyNumberCreated($verifyNumber));

        // return $this->respondSuccessfully(null, __("response.verifyNumber")["send mail"]);
        return $this->respondSuccessfully("인증번호가 발송되었습니다.");

    }


    public function update(Request $request)
    {
        $request->validate([
            "email" => "required|max:255",
            "number" => "required|max:255",
        ]);

        $verifyNumber = VerifyNumber::where('contact', $request->email)
            ->where('number', $request->number)->first();

        if(!$verifyNumber)
            return $this->respondForbidden("인증번호가 일치하지 않습니다.");

        $verifyNumber->update([
            "verified" => true
        ]);

        /*if(auth()->user()) {
            auth()->user()->update(["contact" => $verifyNumber->contact]);

            $verifyNumber->delete();
        }*/

        // return $this->respondSuccessfully($verifyNumber, __("response.verifyNumber")["verified"]);
        return $this->respondSuccessfully();
    }
}
