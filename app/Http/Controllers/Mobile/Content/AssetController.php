<?php

namespace App\Http\Controllers\Mobile\Content;

use App\Http\Controllers\Controller;
use App\Models\Mobile\Content\Asset;
use App\Traits\AssetProcessors;
use App\Traits\MediaProcessors;
use App\Traits\SystemFunctions;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AssetController extends Controller
{
    use AssetProcessors;
    use SystemFunctions;
    use MediaProcessors;

    public function index() {
        $monster_assets = Asset::all();

        foreach ($monster_assets as $monster_asset) {
            $monster_asset['logo'] = $this->verifyMobileAsset($monster_asset['logo']);
            $monster_asset['chart_icon'] = $this->verifyMobileAsset($monster_asset['chart_icon']);
            $monster_asset['article_icon'] = $this->verifyMobileAsset($monster_asset['article_icon']);
            $monster_asset['podcast_icon'] = $this->verifyMobileAsset($monster_asset['podcast_icon']);
            $monster_asset['article_page_icon'] = $this->verifyMobileAsset($monster_asset['article_page_icon']);
            $monster_asset['youtube_page_icon'] = $this->verifyMobileAsset($monster_asset['youtube_page_icon']);
        }

        return response()->json([
            'monster_assets' => $monster_assets
        ]);
    }

    public function show($id) {
        try {
            $monster_asset = Asset::with('Title')->findOrFail($id);

            $monster_asset->logo = $this->verifyMobileAsset($monster_asset['logo']);
            $monster_asset->chart_icon = $this->verifyMobileAsset($monster_asset['chart_icon']);
            $monster_asset->article_icon = $this->verifyMobileAsset($monster_asset['article_icon']);
            $monster_asset->podcast_icon = $this->verifyMobileAsset($monster_asset['podcast_icon']);
            $monster_asset->article_page_icon = $this->verifyMobileAsset($monster_asset['article_page_icon']);
            $monster_asset->youtube_page_icon = $this->verifyMobileAsset($monster_asset['youtube_page_icon']);



        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error occurred! ' . $exception->getMessage()
            ], 404);
        }

        return response()->json([
            'monster_asset' => $monster_asset
        ]);
    }

    public function store(Request $request) {
        $request['location'] = $this->getStationCode();

        $validator = Validator::make($request->all(), [
            'logo' => 'required',
            'chart_icon' => 'required',
            'article_icon' => 'required',
            'podcast_icon' => 'required',
            'article_page_icon' => 'required',
            'youtube_page_icon' => 'required',
            'location' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->all()
            ], 400);
        }

        $website_entry = Asset::all()
            ->where('location', '=', $this->getStationCode())
            ->count();

        if ($website_entry > 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'This website already has an existing entry.'
            ], 400);
        }

        $monster_asset = new Asset($request->all());

        $monster_asset->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Mobile app images for ' . $this->getStationName() . ' has been created!'
        ], 201);
    }

    public function update($id, Request $request) {
        $request['location'] = $this->getStationCode();

        $validator = Validator::make($request->all(), [
            'asset_type' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->all()
            ], 400);
        }

        try {
            $monster_asset = Asset::with('Title')->findOrFail($id);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage()
            ], 404);
        }

        if ($request['asset_type'] === 'charts') {
            // TODO: Image upload has been separate, see public function upload
            $request['chart_title'] = $request['title'];
            $request['chart_sub_title'] = $request['subtitle'];

            $monster_asset->fill($request->only('chart_title', 'chart_subtitle'));
            $monster_asset->Title->fill($request->all());
            $monster_asset->push();

            return response()->json([
                'status' => 'success',
                'message' => 'Mobile application charts assets for '. $this->getStationName() .' has been updated.'
            ]);
        }

        if ($request['asset_type'] === 'articles') {
            $request['article_title'] = $request['title'];
            $request['article_sub_title'] = $request['subtitle'];

            $monster_asset->fill($request->only('article_title', 'article_sub_title'));
            $monster_asset->Title->fill($request->all());
            $monster_asset->push();

            return response()->json([
                'status' => 'success',
                'message' => 'Mobile application article assets for '. $this->getStationName() .' has been updated.'
            ]);
        }

        if ($request['asset_type'] === 'podcasts') {
            $request['podcast_title'] = $request['title'];
            $request['podcast_sub_title'] = $request['subtitle'];

            $monster_asset->fill($request->only('podcast_title', 'podcast_sub_title'));
            $monster_asset->Title->fill($request->all());
            $monster_asset->push();

            return response()->json([
                'status' => 'success',
                'message' => 'Mobile application podcast assets for '. $this->getStationName() .' has been updated.'
            ]);
        }

        if ($request['asset_type'] === 'articlesMain') {
            $request['articles_main_page_title'] = $request['main_title'];

            $monster_asset->fill($request->only('articles_main_page_title'));
            $monster_asset->Title->fill($request->all());
            $monster_asset->push();

            return response()->json([
                'status' => 'success',
                'message' => 'Mobile application articles main page assets for '. $this->getStationName() .' has been updated.'
            ]);
        }

        if ($request['asset_type'] === 'podcastMain') {
            $request['podcast_main_page_title'] = $request['main_title'];

            $monster_asset->fill($request->only('articles_main_page_title'));
            $monster_asset->Title->fill($request->all());
            $monster_asset->push();

            return response()->json([
                'status' => 'success',
                'message' => 'Mobile application podcast main page assets for '. $this->getStationName() .' has been updated.'
            ]);
        }

        if ($request['asset_type'] === 'youtube') {
            $request['youtube_main_page_title'] = $request['main_title'];

            $monster_asset->fill($request->only('youtube_main_page_title'));
            $monster_asset->Title->fill($request->all());
            $monster_asset->push();

            return response()->json([
                'status' => 'success',
                'message' => 'Mobile application youtube main page assets for '. $this->getStationName() .' has been updated.'
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Unknown asset type, contact your IT Developer'
        ], 400);
    }

    public function destroy($id) {
        try {
            $monster_asset = Asset::with('Title')->findOrFail($id);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage()
            ], 404);
        }

        $monster_asset->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Mobile app asset for ' . $this->getStationName() . ' has been deleted!'
        ]);
    }

    public function uploadImage(Request $request) {
        $validator = Validator::make($request->all(), [
            'asset_type' => 'required',
            'image' => ['mimes:jpg,jpeg,png,webp', 'file']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->all()
            ], 400);
        }

        $path = 'images/_assets/mobile';
        $directory = '_assets/mobile';

        // for icon upload.
        $icon = $this->storePhoto($request, $path, $directory);
        $asset_type = $request['asset_type'];

        try {
            $asset = Asset::with('Title')->findOrFail($request['id']);

            if ($asset_type == "charts") {
                $asset['chart_icon'] = $icon;
                $asset->save();
            } else if ($asset_type == "articles") {
                $asset['article_icon'] = $icon;
                $asset->save();
            } else if ($asset_type == "podcasts") {
                $asset['podcast_icon'] = $icon;
                $asset->save();
            } else if ($asset_type == "articlesMain") {
                $asset['article_page_icon'] = $icon;
                $asset->save();
            } else if ($asset_type == "youtube") {
                $asset['youtube_page_icon'] = $icon;
                $asset->save();
            }
        } catch (Exception $exception) {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage()
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Icon for '.$request['asset_type'].' has been uploaded.'
        ], 201);
    }
}
