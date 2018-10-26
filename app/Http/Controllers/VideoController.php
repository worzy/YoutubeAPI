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
