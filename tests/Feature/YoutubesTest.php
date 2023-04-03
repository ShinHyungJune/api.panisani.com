<?php

namespace Tests\Feature;

use App\Models\Youtube;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class YoutubesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 누구나_유튜브목록을_순서순으로_조회할_수_있다()
    {
        $youtubes = Youtube::factory()->count(7)->create();

        $items = $this->get('/api/youtubes')->decodeResponseJson()["data"];

        $this->assertCount(count($youtubes), $items);
    }
}
