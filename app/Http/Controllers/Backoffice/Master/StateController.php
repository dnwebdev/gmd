<?php

namespace App\Http\Controllers\Backoffice\Master;

use App\Models\State;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class StateController extends Controller
{
    public function index()
    {
        toastr();
        return view('back-office.page.master.state.index');
    }

    public function loadData(Request $request)
    {
        $model = State::where('id_country', $request->input('country'));
        return \DataTables::of($model)
            ->addIndexColumn()
            ->addColumn('action', function ($model) {
                $html = '<button data-name="' . $model->state_name_en . '"data-nameindo="' . $model->state_name . '"  data-id="' . $model->id_state . '"  class="btn btn-outline-info btn-preview btn-sm m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill m-btn--air" href=""><i class="fa flaticon-visible"></i></button>';
                return $html;
            })
            ->make(true);
    }

    public function update(Request $request)
    {
        $rule = [
            'id_state' => 'required|exists:tbl_state,id_state',
            'state_name_en' => [
                'required',
                Rule::unique('tbl_state', 'state_name_en')->whereNot('id_state', $request->id_state)
            ],
            'state_name' => [
                'required',
                Rule::unique('tbl_state', 'state_name')->whereNot('id_state', $request->id_state)
            ],
        ];
        $this->validate($request, $rule);
        $lang = State::find($request->id_state);
        if ($lang) {
            $lang->update([
                'state_name_en' => $request->input('state_name_en'),
                'state_name' => $request->input('state_name'),
            ]);
            msg('State Updated!');
            return redirect()->back();
        }
        msg('State not found!', 2);
        return redirect()->back();
    }
}
