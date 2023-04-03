<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PayMethodResource;
use App\Models\PayMethod;
use Illuminate\Http\Request;

class PayMethodController extends ApiController
{
    public function index()
    {
        $payMethods = PayMethod::where("used", true)->paginate(30);

        return $this->respondSuccessfully(PayMethodResource::collection($payMethods));
    }
}
