<?php

namespace App\Http\Controllers\Website\Content\Indieground;

use App\Http\Controllers\Controller;
use App\Models\Feature;
use App\Models\Indie;
use App\Traits\AssetProcessors;
use App\Traits\SystemFunctions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;

class FeaturedArtistController extends Controller
{
    use AssetProcessors, SystemFunctions;

    public function index()
    {
        $indie = Indie::with('Artist')
            ->whereHas('Artist', function(Builder $builder) {
            $builder->orderBy('name');
        })->get();

        $featured_indie = Feature::with('Indie')
            ->whereNull('deleted_at')
            ->get();

        $data = [
            'featured' => $featured_indie,
            'indie' => $indie
        ];

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'indieground_id' => 'required',
            'content' => 'required',
            'month' => 'required',
            'year' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->json('error', $validator->errors()->all(), 400);
        }

        $request['location'] = $this->getStationCode();

        $feature = new Feature($request->all());
        $feature->save();

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_created', ['Model' => 'Featured Artist'])
        ], 201);
    }

    public function show($id)
    {
        try {
            $feature = Feature::with('Indie.Artist.Album.Song')->findOrFail($id);
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        return response()->json([
            'feature' => $feature
        ]);
    }

    public function update($id, Request $request)
    {
        try {
            $feature = Feature::with('Indie.Artist.Album.Song')->findOrFail($id);

            $feature->update($request->all());
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_updated', ['Model' => 'Featured Artist'])
        ]);
    }

    public function destroy($id)
    {
        try {
            $feature = Feature::with('Indie.Artist.Album.Song')->findOrFail($id);

            $feature->delete();
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_deleted', ['Model' => 'Featured Artist'])
        ]);
    }
}
