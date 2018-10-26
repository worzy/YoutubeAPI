<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Entities\Video;
use App\Entities\Channel;

class APITest extends TestCase
{
    // Refresh database before performing tests
    use RefreshDatabase;


    /**
     * Test fetching all videos.
     *
     * @return void
     */
    public function testFetchAllVideos()
    {
        $videos = factory(Video::class, 50)->create();

        $this->json('GET', '/api/videos')
            ->assertStatus(200)
            ->assertJson([
                "current_page" => 1
            ])
            ->assertJsonStructure([
                    'current_page',
                    'data'
            ]);

        $this->json('GET', '/api/videos?page=2')
            ->assertStatus(200)
            ->assertJson([
                "current_page" => 2
            ]);

        $this->json('GET', '/api/videos?page=hello')
            ->assertStatus(422)
            ->assertJson([
                "message" => "The given data was invalid.",
            ]);
    }

    /**
     * Test fetching a video.
     *
     * @return void
     */
    public function testFetchVideoById()
    {
        $videos = factory(Video::class, 50)->create();

        $this->json('GET', '/api/videos/' . $videos[0]->id)
            ->assertStatus(200)
            ->assertJson([
                    'title' => $videos[0]->title,
                    'date' => $videos[0]->date
                ]);
    }

    /**
     * Test deleting a video.
     *
     * @return void
     */
    public function testDeleteVideoById()
    {
        $video = factory(Video::class)->create();

        $videoArr = $video->toArray();

        $this->json('DELETE', '/api/videos/' . $video->id)
            ->assertStatus(200);

        $this->assertDatabaseMissing('videos', $videoArr);

        $this->json('GET', '/api/videos/' . $videoArr['id'])
            ->assertStatus(404);
    }

    /**
     * Test fetching all videos.
     *
     * @return void
     */
    public function testSearchVideosByQuery()
    {
        $video1 = factory(Video::class)->create([
            'title' => 'Cycling with Joe Bloggs',
        ]);

        $video2 = factory(Video::class)->create([
            'title' => 'Cycling with Ben Slater',
        ]);

        $this->json('GET', '/api/videos/search?q=Bloggs')
            ->assertStatus(200)
            ->assertJson([[
                    'title' => $video1->title,
                ]])
            ->assertJsonMissing([[
                    'title' => $video2->title,
                ]]);
    }
}
