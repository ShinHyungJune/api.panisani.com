<?php

namespace Database\Factories;

use App\Models\Youtube;
use Illuminate\Database\Eloquent\Factories\Factory;

class YoutubeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $titles = [
            "7월 블록체인 컨퍼런스",
            "플라네타리움 연구실",
            "NFT 시장전망",
            "위메이드 코리아",
            "LOL 모바일 토큰 출시",
            "웹3 게임 출시",
            "웹3 기반 서든어택 질리카",
            "걸으면 가상화폐 주는 앱 '스테픈' 인기",
            "6월 블록체인 컨퍼런스",
            "메타골드 파밍에 좋은 픽스플로전",
            "랜드마크 5번 구매 알고 계셨나요?",
            "주간 APEX, 현재 1티어는?",
        ];

        $descriptions = [
            "NFT시장이 과연 어떻게 변화할것인가?",
            "구글 APEC 웹 3.0 게임 혁신 공유",
            "토큰기반 웹게임 출시될 예정",
            "올 하반기 예상 성과"
        ];

        return [
            "title" => $titles[Youtube::count() % count($titles)],
            "url" => $this->faker->url,
            "thumbnail" => $this->faker->url,
            "key" => $this->faker->randomKey,
            "order" => Youtube::count(),
        ];
    }
}
