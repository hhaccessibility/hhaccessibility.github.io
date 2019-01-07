<?php namespace App\Http\Controllers;

use App\Image;
use App\Role;
use App\BaseUser;
use Illuminate\Routing\Controller;
use Illuminate\Auth\AuthenticationException;
use Response;
use DB;

class LocationImageController extends Controller
{
    public function getImagesFor(string $location_id)
    {
        $result = Image::where('location_id', '=', $location_id)
            ->join('user', 'user.id', '=', 'image.uploader_user_id')
            ->select(
                'image.id',
                'image.created_at',
                'image.uploader_user_id',
                DB::raw('concat(user.first_name, \' \', user.last_name) as uploader_name')
            )
            ->get();
        $all_can_be_deleted = false;
        $user_id = null;
        if (BaseUser::isSignedIn()) {
            $user = BaseUser::getDbUser();
            $user_id = $user->id;
            if ($user->hasRole(Role::INTERNAL)) {
                $all_can_be_deleted = true;
            }
        }
        foreach ($result as $image) {
            $image->can_be_deleted = $all_can_be_deleted || $image->uploader_user_id === $user_id;
            unset($image->uploader_user_id);
        }
        return $result;
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

    public function deleteImage(string $image_id)
    {
        if (!BaseUser::isSignedIn()) {
            throw new AuthenticationException('Must be signed in to delete an image');
        }
        $user = BaseUser::getDbUser();
        $image = Image::find($image_id);
        if (!$image) {
            return Response::json([
                    'message'   =>  'Image not found with specified id'
                ], 404);
        }
        if (!$user->hasRole(Role::INTERNAL) && $image->uploader_user_id !== $user->id) {
            return Response::json([
                    'message'   =>  'Only the uploader of an image can delete it'
                ], 403);
        }
        $image->delete();
        return Response::json([
            'message'   =>  'Image deleted'
        ], 200);
    }
}
