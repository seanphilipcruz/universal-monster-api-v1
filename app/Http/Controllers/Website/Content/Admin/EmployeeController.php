<?php

namespace App\Http\Controllers\Website\Content\Admin;

use App\Http\Controllers\Controller;
use App\Models\Designation;
use App\Models\Employee;
use App\Traits\SystemFunctions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    use SystemFunctions;

    public function index()
    {
        $employees = Employee::with('Designation')
            ->whereNull('deleted_at')
            ->where('location', $this->getStationCode())
            ->get();

        $designations = Designation::with('Employee')
            ->whereNull('deleted_at')
            ->get();

        return response()->json([
            'employees' => $employees,
            'designations' => $designations
        ]);
    }

    public function store(Request $request)
    {
        $request['location'] = $this->getStationCode();
        $request['employee_number'] = $this->IdGenerator(8);

        $validator = Validator::make($request->all(), [
            'designation_id' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'gender' => 'required',
            'birthday' => 'required',
            'contact_number' => 'required',
            'address' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->all()
            ], 400);
        }

        $employee = new Employee($request->all());
        $employee->save();

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_created', ['model' => 'employee'])
        ], 201);
    }

    public function show($id)
    {
        try {
            $employee = Employee::with('Designation')->findOrFail($id);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage()
            ], 404);
        }

        return response()->json([
            'employee' => $employee
        ]);
    }

    public function update($id, Request $request)
    {
        $request['location'] = $this->getStationCode();

        $validator = Validator::make($request->all(), [
            'designation_id' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'gender' => 'required',
            'birthday' => 'required',
            'contact_number' => 'required',
            'address' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->all()
            ], 400);
        }

        try {
            $employee = Employee::with('Designation')->findOrFail($id);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage()
            ], 404);
        }

        $employee->update($request->all());

        return response()->json([
            'status' => 'error',
            'message' => __('responses.success_updated', ['model' => 'employee'])
        ]);
    }

    public function destroy($id)
    {
        try {
            $employee = Employee::with('Designation')->findOrFail($id);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage()
            ], 404);
        }

        try {
            $employee->delete();
        } catch (QueryException $exception) {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage()
            ], 400);
        }

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_deleted', ['model' => 'employee'])
        ]);
    }
}
