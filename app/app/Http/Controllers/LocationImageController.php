<?php namespace App\Http\Controllers;

use App\Image;
use Illuminate\Routing\Controller;
use Response;
use DB;

class LocationImageController extends Controller
{
    public function getImagesFor(string $location_id)
    {
        return Image::where('location_id', '=', $location_id)
            ->join('user', 'user.id', '=', 'image.uploader_user_id')
            ->select(
                'image.id',
                'image.created_at',
                'image.uploader_user_id',
                DB::raw('concat(user.first_name, \' \', user.last_name) as uploader_name')
            )
            ->get();
    }

    public function getImage(string $image_id)
    {
        $image = Image::find($image_id);
        if (!$image) {
            return Response::json([
                    'code'      =>  404,
                    'message'   =>  'Image not found with specified id'
                ], 404);
        }
        $response = Response::make($image->raw_data, 200);
        $response->header('Content-Type', 'image/jpeg');
        return $response;
    }
}
