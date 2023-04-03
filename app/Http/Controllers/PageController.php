<?php

namespace App\Http\Controllers;

use App\Http\Resources\BannerResource;
use App\Http\Resources\NoticeResource;
use App\Http\Resources\UserResource;
use App\Models\Banner;
use App\Models\Notice;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class PageController extends Controller
{
    public function index(Request $request)
    {
        $banners = Banner::latest()->paginate(30);

        return Inertia::render("Index",[
            "banners" => BannerResource::collection($banners),
        ]);
    }

    public function intro()
    {
        return Inertia::render("Contents/Intro");
    }

    public function about()
    {
        return Inertia::render("Contents/About");
    }

    public function privacyPolicy()
    {
        return Inertia::render("Contents/PrivacyPolicy");
    }
}
