<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\Basic;
use App\Models\Car;
use App\Models\CarColor;
use App\Models\CarFuel;
use App\Models\CarCreator;
use App\Models\CarTransmission;
use App\Models\CarType;
use App\Models\Consulting;
use App\Models\Faq;
use App\Models\Notice;
use App\Models\PayMethod;
use App\Models\PointHistory;
use App\Models\Qna;
use App\Models\Review;
use App\Models\Sale;
use App\Models\ServiceCheck;
use App\Models\ServiceProtect;
use App\Models\User;
use App\Models\Youtube;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class InitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("SET foreign_key_checks=0");
        Youtube::truncate();
        DB::statement("SET foreign_key_checks=1");

        $this->createYoutubes();
        // $this->createBanners();
    }

    public function createYoutubes()
    {
        $items = [
            "https://www.youtube.com/watch?v=3l3t05bsbW8",
            "https://www.youtube.com/watch?v=ceAXKowQyRk",
            "https://www.youtube.com/watch?v=LY5zjSu2mIY",
            "https://www.youtube.com/watch?v=UdzlCS-34Ts",
            "https://www.youtube.com/watch?v=85c_UVhhjrI",
            "https://www.youtube.com/watch?v=cFsGCYlbt-s",
            "https://www.youtube.com/watch?v=Th62twZftf4",
            "https://www.youtube.com/watch?v=2sosdA22MuA",
            "https://www.youtube.com/watch?v=x_HVs3bLEt0",
        ];

        foreach($items as $item){
            Youtube::factory()->create([
                "url" => $item
            ]);
        }
    }

    public function createBanners()
    {
        $items = [
            [
                "title" => "당신의 예비 자차를
                찾아드려요",
                "subtitle" => "태어는 났나 싶은 당신의 예비 자차,
                만남부터 계약까지",
                "url" => "/cars",
                "pc" => "/images/main_banner.png"
            ],
        ];

        foreach($items as $item){
            $createdItem = Banner::create(Arr::except($item, ['pc']));

            $createdItem->addMedia(public_path($item["pc"]))->preservingOriginal()->toMediaCollection("pc", "s3");
        }
    }

}
