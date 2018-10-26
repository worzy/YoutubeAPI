<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Youtube\Youtube;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Client;
use App\Entities\Channel;

class YoutubeApiTest extends TestCase
{
    /**
     * Test Youtube Api Store.
     *
     * @return void
     */
    public function testYoutubeApiStore()
    {

        // Set up responses
        $channelResp = [
            "pageInfo" => [
                "totalResults" => 1,
                "resultsPerPage" => 5
            ],
            "items" => [
                [
                    "id" => "UCuTaETsuCOkJ0H_GAztWt0Q",
                    "snippet" => [
                        "title" => "Global Cycling Network"
                    ],
                ]
            ]
        ];

        $videoResp = [
            "pageInfo" => [
                "totalResults" => 2,
                "resultsPerPage" => 50
            ],
            "items" => [
                [
                    "id" => "UCuTaETsuCOkJ0H_GAztWt0Q",
                    "snippet" => [
                        "title" => "My Video 1",
                        "publishedAt" => "2013-12-10T12:00:40.000Z"
                    ],
                ],[
                    "id" => "UCuTaETsuCOkJ0H_GAztWt0A",
                    "snippet" => [
                        "title" => "Great Video 2",
                        "publishedAt" => "2013-12-10T12:00:40.000Z"
                    ]
                ]
            ]
        ];

        // Configure mocked responses from API
        $mock = new MockHandler([
            new Response(200, [], json_encode($channelResp)),
            new Response(200, [], json_encode($videoResp)),
        ]);
        $guzzleClient = new Client(['handler' => $mock]);
        
        // Call gather videos function
        $youtube = new Youtube($guzzleClient);
        $channels = factory(Channel::class, 1)->make();
        $videos = $youtube->findChannelVideos($channels, []);

        // Check result
        $this->assertEquals(json_encode($videoResp['items']), json_encode($videos));
    }
}
