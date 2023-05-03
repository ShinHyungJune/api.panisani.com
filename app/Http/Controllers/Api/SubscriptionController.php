<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SubscriptionResource;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;

class SubscriptionController extends ApiController
{
    public function index(Request $request)
    {
        $request->validate([
            "user_id" => "nullable|integer",
            "target_user_id" => "nullable|integer",
        ]);

        $request["order_by"] = $request->order_by ?? "created_at";

        $subscriptions = Subscription::orderBy($request->order_by, "desc");

        if($request->user_id)
            $subscriptions = $subscriptions->where("user_id", $request->user_id);

        if($request->target_user_id)
            $subscriptions = $subscriptions->where("target_user_id", $request->target_user_id);

        $subscriptions = $subscriptions->paginate(20);

        return SubscriptionResource::collection($subscriptions);
    }

    public function store(Request $request)
    {
        $request->validate([
            "target_user_id" => "required|integer"
        ]);

        $targetUser = User::find($request->target_user_id);

        if(!$targetUser)
            return $this->respondForbidden("존재하지 않거나 탈퇴한 회원입니다.");

        if($targetUser->id == auth()->id())
            return $this->respondForbidden("자기 자신은 구독할 수 없습니다.");

        $prevSubscription = auth()->user()->giveSubscriptions()->where("target_user_id", $targetUser->id)->first();

        $prevSubscription ? $prevSubscription->delete() : auth()->user()->giveSubscriptions()->create([
            "target_user_id" => $targetUser->id
        ]);

        return $this->respondSuccessfully();
    }
}
