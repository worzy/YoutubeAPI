<?php

namespace App\Youtube;

use GuzzleHttp\Client as Guzzle;

class Youtube
{

    private $apiEndpoint = 'https://www.googleapis.com/youtube/v3/';

    public function __construct(Guzzle $guzzle)
    {

        $this->guzzle = $guzzle;
    }

    public function findChannelVideos($channels, $filter)
    {

        $channelVideos = [];

        foreach ($channels as $channel) {
            $channelId = $this->getChannel($channel)->id;

            $channelVideos = array_merge($channelVideos, $this->getChannelVideos($channelId, $filter));
        }

        return $channelVideos;
    }

    private function filterVideoByTitle(String $title, array $filters)
    {

        foreach ($filters as $filter) {
            if (strpos(" {$title} ", " {$filter} ") !== false) {
                return true;
            }
        }
        return false;
    }

    private function getChannel(String $username)
    {
        
        $endpoint = $this->apiEndpoint . 'channels/?key='. env('YOUTUBE_API') . '&part=snippet&forUsername=' . $username;

        $channel = json_decode($this->guzzle->request('GET', $endpoint)->getBody());

        return isset($channel->items[0]) ? $channel->items[0] : false;
    }

    private function getChannelVideos(String $channelId, array $filters = [])
    {

        $videosArr = [];

        // Endpoint for videos, max videos per page is 50
        $endpoint = $this->apiEndpoint . 'search/?type=video&maxResults=50&key='. env('YOUTUBE_API') . '&part=snippet&channelId=' . $channelId;

        $response = json_decode($this->guzzle->request('GET', $endpoint)->getBody());

        $videosArr = array_merge($videosArr, $response->items);

        $nextPageToken = isset($response->nextPageToken) ? $response->nextPageToken : "";
        $i = 0;
        while ($nextPageToken) {
            $response = json_decode($this->guzzle->request('GET', $endpoint . '&pageToken=' . $nextPageToken)->getBody());
            $nextPageToken = isset($response->nextPageToken) ? $response->nextPageToken : "";
            $videosArr = array_merge($videosArr, $response->items);
            $i++;
        }

        // If filters are empty return array
        if (!$filters) {
            return $videosArr;
        }

        $filteredVideos = array_filter($videosArr, function ($video) use ($filters) {
            return $this->filterVideoByTitle($video->snippet->title, $filters);
        });

        return $filteredVideos;
    }
}
