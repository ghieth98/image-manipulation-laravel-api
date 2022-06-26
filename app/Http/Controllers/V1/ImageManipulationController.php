<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResizeImageRequest;
use App\Http\Resources\V1\ImageManipulationResource;
use App\Models\Album;
use App\Models\ImageManipulation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class ImageManipulationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        return ImageManipulationResource::collection(ImageManipulation::
        where('user_id', $request->user()->id)
            ->paginate());
    }

    /**
     * Sort by album
     * @param Request $request
     * @param Album $album
     * @return AnonymousResourceCollection
     */
    public function byAlbum(Request $request, Album $album)
    {
        if($request->user()->id != $album->user_id){
            abort(403, 'Unauthorized');
        }
        $where = ['album_id' => $album->id];
        return ImageManipulationResource::collection(ImageManipulation::where($where)->paginate());

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\ResizeImageRequest  $request
     * @return ImageManipulationResource
     */
    public function resize(ResizeImageRequest $request)
    {
       $all = $request->all();

        /** @var UPLOADEDFILE|string $image */
        $image = $all['image'];
        unset($all['image']);

        $data = [
            'type'=>ImageManipulation::TYPE_RESIZE,
            'data'=>json_encode($all),
            'user_id'=>$request->user()->id
        ];

        if (isset($all['album_id'])){
            $album = Album::find($all['album_id']);
            if ($album->user_id != $request->user()->id){
                return abort(403, 'unauthorized');
            }
            $data['album_id'] = $all['album_id'];
        }

        //this sequence is used to make a dir for the images
        $dir = 'images/' . Str::random() . '/';
        $absolutePath = public_path($dir);

        if (!File::exists($absolutePath)){
            File::makeDirectory($absolutePath, 0755, true);
        }

        if ($image instanceof UploadedFile){
            $data['name'] = $image->getClientOriginalName();
            $filename = pathinfo($data['name'], PATHINFO_FILENAME);
            $extension = $image->getClientOriginalExtension();
            $originalPath = $absolutePath . $data['name'];
            $data['path'] = $dir . $data['name'];
            $image->move($absolutePath, $data['name']);
        }else{
            $data['name'] = pathinfo($image, PATHINFO_BASENAME);
            $filename = pathinfo($image, PATHINFO_FILENAME);
            $extension = pathinfo($image, PATHINFO_EXTENSION);
            $originalPath = $absolutePath . $data['name'];

            copy($image, $originalPath);
            $data['path'] = $dir . $data['name'];
        }
        $w = $all['w'];
        $h = $all['h'] ?? false;

        list($image, $width, $height) = $this->getWidthAndHeight($w, $h, $originalPath);

        $resizedFilename = $filename . '-resized' . $extension;

        $image->resize($width, $height)->save($absolutePath.$resizedFilename);
        $data['output_path'] = $dir . $resizedFilename;
        $imageManipulation = ImageManipulation::create($data);

        return new ImageManipulationResource($imageManipulation);

    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param ImageManipulationController $image
     * @return ImageManipulationResource
     */
    public function show(Request $request, ImageManipulationController $image)
    {
        if($request->user()->id != $image->user_id){
            abort(403, 'Unauthorized');
        }
        return new ImageManipulationResource($image);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param ImageManipulationController $image
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, ImageManipulationController $image)
    {
        if($request->user()->id != $image->user_id){
            abort(403, 'Unauthorized');
        }
        $image->destroy();
        return response('', 204);
    }

    /**
     * Get the width and height of the image
     * @param $w
     * @param $h
     * @param string $originalPath
     * @return array
     */
    protected function getWidthAndHeight($w, $h, string $originalPath)
    {
        //1000 -50% => 500px
        $image = Image::make($originalPath);
        $originalWidth = $image->width();
        $originalHeight = $image->height();

        //Check if the received width has % inside it
        if (str_ends_with($w, '%')){
            $ratioW = (float) str_replace('%', '', $w);
            $ratioH = $h ? (float) str_replace('%', '', $h) : $ratioW;

            $newWidth = $originalWidth * $ratioW / 100;
            $newHeight = $originalHeight * $ratioH / 100;
        }else{
            $newWidth = (float)$w;
            //To calculate the new height
            $newHeight = $h ? (float)$h : $originalHeight * $newWidth/$originalWidth;
        }

        return [$newWidth, $newHeight, $image];
    }


}
