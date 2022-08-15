<?php

namespace App\Http\Controllers\Backoffice\Update;

use App\Models\Company;
use App\Models\Update;
use App\Notifications\Update\UpdateNewsNotification;
use App\Scopes\ActiveProviderScope;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Notifications\DatabaseNotification;

class UpdateCtrl extends Controller
{
    /**
     * show list info and updates page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('back-office.page.updates.index');
    }

    /**
     * provide info and updates data
     * @param Request $request
     * @param Update $update
     * @return mixed
     * @throws \Exception
     */
    public function loadAjaxData(Request $request, Update $update)
    {
        $model = $update->newQuery()->with('admin')->select('tbl_updates.*');
        if (checkRequestExists($request, 'type', 'GET')) {
            $model = $model->where('type', $request->get('type'));
        }
        return \DataTables::of($model)
            ->addIndexColumn()
            ->editColumn('type', function ($model){
                return ucwords(str_replace('_',' ',$model->type));
            })
            ->addColumn('action', function ($model) {
                $html = '<a href="' . route('admin:updates.edit', ['id' => $model->id]) . '"  class="btn btn-outline-info btn-preview btn-sm m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill m-btn--air" href=""><i class="fa flaticon-visible"></i></a>';
                return $html;
            })
            ->make(true);
    }

    /**
     * show add new updates page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function add()
    {
        toastr();
        return view('back-office.page.updates.add');
    }

    /**
     * show edit updates page
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function edit($id)
    {
        if (!$data['update'] = Update::find($id)){
            msg('Update Not Found',2);
            return redirect()->route('admin:updates.index');
        }
        toastr();
        return view('back-office.page.updates.edit',$data);
    }

    /**
     * save new info and update
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function save(Request $request)
    {
        $rules = [
            'type' => 'required|in:patch_notes,upcoming_features,new_features,info_promo',
            'title' => 'required',
            'title_indonesia' => 'required',
            'content' => 'required',
            'content_indonesia' => 'required',
        ];
        $this->validate($request, $rules);
        try {
            \DB::beginTransaction();
            $update = new Update();
            $update->type = $request->input('type');
            $update->title = $request->input('title');
            $update->title_indonesia = $request->input('title_indonesia');
            $update->content = $request->input('content');
            $update->content_indonesia = $request->input('content_indonesia');
            $update->update_by = auth('admin')->id();
            $update->save();
            $companies = Company::withoutGlobalScope(ActiveProviderScope::class)->get();
            $update->companies()->sync($companies);

//            \Notification::send(Company::all(),new UpdateNewsNotification($update->id,$update->type,$update->title,$update->content,$update->title_indonesia,$update->content_indonesia));

            \DB::commit();
            return apiResponse(200,'Sent',['redirect'=>route('admin:updates.index')]);
        } catch (\Exception $exception) {
            \DB::rollBack();
            return apiResponse(500, __('general.whoops'), getException($exception));
        }
    }

    /**
     * update info and updates
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, Request $request)
    {
        if (!$update = Update::find($id)){
            return apiResponse(404,'Not Found');
        }
        $rules = [
            'type' => 'required|in:patch_notes,upcoming_features,new_features,info_promo',
            'title' => 'required',
            'title_indonesia' => 'required',
            'content' => 'required',
            'content_indonesia' => 'required',
        ];
        $this->validate($request, $rules);
        try {
            \DB::beginTransaction();
            $update->type = $request->input('type');
            $update->title = $request->input('title');
            $update->title_indonesia = $request->input('title_indonesia');
            $update->content = $request->input('content');
            $update->content_indonesia = $request->input('content_indonesia');
            $update->update_by = auth('admin')->id();
            $update->save();
//            DatabaseNotification::where('data->external_id',$update->id)->update([
//                'data->type'=>$update->type,
//                'data->title'=>$update->title,
//                'data->title_indonesia'=>$update->title_indonesia,
//                'data->content'=>$update->content,
//                'data->content_indonesia'=>$update->content_indonesia,
//            ]);
            \DB::commit();
            return apiResponse(200,'Updated',['redirect'=>route('admin:updates.index')]);
        } catch (\Exception $exception) {
            \DB::rollBack();
            return apiResponse(500, __('general.whoops'), getException($exception));
        }
    }
}
