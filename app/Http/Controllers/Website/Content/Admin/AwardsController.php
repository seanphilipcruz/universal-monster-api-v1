<?php

namespace App\Http\Controllers\Website\Content\Admin;

use App\Models\Award;
use App\Http\Controllers\Controller;
use App\Models\Jock;
use App\Models\Show;
use App\Traits\SystemFunctions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;

class AwardsController extends Controller
{
    use SystemFunctions;

    public function index()
    {
        $award = Award::with('Jock.Employee')->get();

        $jock = Jock::with('Employee')
            ->whereHas('Employee', function($builder) {
                $builder->where('location', $this->getStationCode());
            })
            ->where('is_active', '=', 1)
            ->get();

        $show = Show::with('Award')
            ->where('is_active', '=', 1)
            ->get();

        $data = [
            'awards' => $award,
            'jocks' => $jock,
            'shows' => $show
        ];

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'award_type' => 'required',
            'title' => 'required',
            'description' => 'required',
            'year' => 'required',
            'is_special' => 'required'
        ]);

        if ($validation->fails()) {
            return $this->json('error', $validation->errors()->all(), 404);
        }

        $request['location'] = $this->getStationCode();

        if ($request['award_type'] == 'show') {
            $award = new Award($request->all());

            $award->save();

            return $this->json('success', __('responses.success_created', ['Model' => 'Award']), 201);
        } else if ($request['award_type'] == 'jock') {
            $award = new Award($request->all());

            $award->save();

            return $this->json('success', __('responses.success_created', ['Model' => 'Award']), 201);
        } else {
            return $this->json('success', __('responses.error_invalid_request', ['Model' => 'Award']));
        }
    }

    public function show($id)
    {
        try {
            $award = Award::with('Jock', 'Show')->findOrFail($id);
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        return response()->json([
            'award' => $award
        ]);
    }

    public function update($id, Request $request)
    {
        try {
            $award = Award::with('Jock', 'Show')->findOrFail($id);
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        $award->update($request->all());

        return $this->json('success', __('responses.success_updated', ['Model' => 'Award']));
    }

    public function destroy($id)
    {
        try {
            $award = Award::with('Jock', 'Show')->findOrFail($id);

            $award->delete();
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        return $this->json('success', __('responses.success_deleted', ['Model' => 'Award']), 404);
    }
}
