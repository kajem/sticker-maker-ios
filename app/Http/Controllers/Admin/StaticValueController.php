<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\StaticValue;

class StaticValueController extends Controller
{
    public function index()
    {
        $static_values = StaticValue::all();
        return view('admin.static-value.list')->with(['static_values' => $static_values]);
    }

    public function save(Request $request)
    {
        if (empty($request->input('id'))) {
            return $this->errorOutput('Something went wrong!');
        }
        if (empty($request->input('label'))) {
            return $this->errorOutput('Label field required');
        }
        if (empty($request->input('value')) && $request->input('value') != 0) {
            return $this->errorOutput('Value field required');
        }

        $data = [
            'label' => $request->input('label'),
            'value' => $request->input('value')
        ];

        StaticValue::where('id', $request->input('id'))->update($data);

        return $this->successOutput([], 'Saved successfully.');
    }
}
