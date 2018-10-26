<?php

namespace App\Http\Controllers;

use App\Entities\Channel;
use App\Entities\Video;
use App\Youtube\Youtube;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Validator;

class VideoController extends ApiController
{

    /**
     * Fetch and store data from API based on filter file
     */
    public function storeFilter(Channel $channel, Video $video, Youtube $youtube)
    {
        // Get Channel names as array
        $allChannelsArr = $channel->all()->pluck('channel_name')->toArray();
        
        $filterArr = explode(PHP_EOL, Storage::get('search_filter'));

        // Get videos with filter applied
        $youtubeVideos = $youtube->findChannelVideos($allChannelsArr, $filterArr);

        foreach ($youtubeVideos as $video) {

            // Validate that data is correct and unique
            $validator = Validator::make([
                'title' => $video->snippet->title,
                'date' => Carbon::parse($video->snippet->publishedAt)
            ], [
                'title' => 'required|unique:videos|max:100',
                'date' => 'required|date',
            ]);

            if (!$validator->fails()) {
                Video::create([
                    'title' => $video->snippet->title,
                    'date' => Carbon::parse($video->snippet->publishedAt),
                ]);
            }
        }

        return $this->respondSuccess();
    }

    /**
     * Return a listing of the resource
     */
    public function index(Request $request)
    {
        // Validate index query
        $validatedData = $request->validate([
            'page' => 'integer',
        ]);

        // Paginate the response
        return Video::paginate();
    }

    /**
     * Return the specified resource.
     */
    public function show(Video $video)
    {
        return $this->respond($video);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Video $video)
    {
        $video->delete();
        return $this->respondSuccess();
    }

    /**
     * Search using keywords for the specified resource.
     */
    public function search(Request $request, Video $video)
    {
        // Validate search query
        $validatedData = $request->validate([
            'q' => 'required|max:100',
        ]);

        $videos = $video->where('title', 'like', '%' . $request->get('q') . '%')->get();

        return $videos;
    }
}
