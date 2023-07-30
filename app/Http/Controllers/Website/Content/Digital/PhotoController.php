<?php

namespace App\Http\Controllers\Website\Content\Digital;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Photo;
use App\Traits\AssetProcessors;
use App\Traits\SystemFunctions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PhotoController extends Controller
{
    use AssetProcessors, SystemFunctions;

    public function upload_image(Request $request) {
        $validator = Validator::make($request->all(), [
            'request_type' => 'required',
            'image' => ['mimes:jpg,jpeg,png,webp', 'file']
        ]);

        if ($validator->fails()) {
            return $this->json('error', $validator->errors()->all(), 400);
        }

        $id = $request['id'];
        $batch_id = $request['batch_id'];
        $show_id = $request['show_id'];
        $jock_id = $request['jock_id'];
        $article_id = $request['article_id'];

        $request_type = $request['request_type'];
        $request['image'] = $request['file'];

        if ($batch_id) {
            $path = 'images/scholarBatch';
            $directory = 'batch';

            $image_name = $this->storePhoto($request, $path, $directory);
            $request['file'] = $image_name;
            $request['name'] = $this->IdGenerator(4);

            if ($request_type == "store") {
                $photo = new Photo($request->all());
                $photo->save();

                return response()->json([
                    'status' => 'success',
                    'message' => __('responses.success_image_upload', ['Model' => 'Scholar Batch'])
                ]);
            }

            $photo = Photo::with('Batch')->findOrFail($id);
            $photo->fill($request->all());
            $photo->save();

            return response()->json([
                'status' => 'success',
                'message' => __('responses.success_image_update', ['Model' => 'Scholar Batch'])
            ]);
        } else if ($show_id) {
            $path = 'images/shows';
            $directory = 'shows';

            $image_name = $this->storePhoto($request, $path, $directory);
            $request['file'] = $image_name;
            $request['name'] = $this->IdGenerator(4);

            if ($request_type == "store") {
                $photo = new Photo($request->all());
                $photo->save();

                return response()->json([
                    'status' => 'success',
                    'message' => __('responses.success_image_upload', ['Model' => 'Show'])
                ]);
            }

            $photo = Photo::with('Show')->findOrFail($id);
            $photo->fill($request->all());
            $photo->save();

            return response()->json([
                'status' => 'success',
                'message' => __('responses.success_image_update', ['Model' => 'Show'])
            ]);
        } else if ($jock_id) {
            $path = 'images/jocks';
            $directory = 'jocks';

            $image_name = $this->storePhoto($request, $path, $directory);
            $request['file'] = $image_name;
            $request['name'] = $this->IdGenerator(4);

            if ($request_type == "store") {
                $photo = new Photo($request->all());
                $photo->save();

                return response()->json([
                    'status' => 'success',
                    'message' => __('responses.success_image_upload', ['Model' => 'Jock'])
                ]);
            }

            $photo = Photo::with('Jock')->findOrFail($id);
            $photo->fill($request->all());
            $photo->save();

            return response()->json([
                'status' => 'success',
                'message' => __('responses.success_image_update', ['Model' => 'Jock'])
            ]);
        }

        $path = 'images/articles';
        $directory = 'articles';

        $image_name = $this->storePhoto($request, $path, $directory);
        $request['file'] = $image_name;
        $request['name'] = $this->IdGenerator(4);

        if ($request_type == "store") {
            $photo = new Photo($request->all());
            $photo->save();

            return response()->json([
                'status' => 'success',
                'message' => __('responses.success_image_upload', ['Model' => 'Article'])
            ]);
        }

        $photo = Photo::with('Article')->findOrFail($id);
        $photo->fill($request->all());
        $photo->save();

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_image_update', ['Model' => 'Article'])
        ]);
    }
}
