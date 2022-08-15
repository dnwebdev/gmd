<?php

namespace App\Http\Controllers\Backoffice\Master;

use App\Models\Association;
use App\Models\Company;
use File;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Image;
use Storage;

class AssociationCtrl extends Controller
{
    /**
     * show page Association list
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        toastr();
        return view('back-office.page.master.association.index');
    }

    /**
     * get data association
     * @return mixed
     * @throws \Exception
     */
    public function loadData()
    {
        $models = Association::withCount('companies');
        return \DataTables::of($models)
            ->addIndexColumn()
            ->editColumn('association_logo', function ($model){
                return '<img width=40 src="'.asset($model->association_logo).'">';
            })
            ->addColumn('action', function ($model) {
                $html = '<button data-name="' . $model->association_name . '" data-desc="' . $model->association_desc .'" data-id="' . $model->id . '"  class="btn btn-outline-success btn-add-provider btn-sm m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill m-btn--air" href=""><i class="fa flaticon-user-add"></i></button>';
                $html .= ' <button data-name="' . $model->association_name . '" data-desc="' . $model->association_desc .'" data-id="' . $model->id . '"  class="btn btn-outline-info btn-preview btn-sm m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill m-btn--air" href=""><i class="fa flaticon-visible"></i></button>';
                $html .= ' <button data-name="' . $model->association_name .'" data-id="' .$model->id . '" class="btn-delete btn btn-outline-danger btn-sm m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill m-btn--air">';
                $html .= '<i class="fa fa-trash"></i>';
                $html .= '</button>';
                return $html;
            })
            ->rawColumns(['association_logo','action'])
            ->make(true);
    }

    /**
     * search provider to save in association
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchProvider(Request $request)
    {
        $providers=Company::whereDoesntHave('associations', function ($as) use ($request){
            $as->where('id',$request->id);
        })->where(function ($where) use ($request){
            $where->where('company_name','like','%'.$request->q.'%')->orWHere('domain_memoria','like','%'.$request->q.'%');
        });
        $data=[];
        foreach ($providers->paginate(10) as $item){
            $data[] = [
                'id'=>$item->id_company,
                'text'=>$item->company_name.' | '.$item->domain_memoria
            ];
        }
        return response()->json([
            'incomplete_results' => false,
            'items' => $data,
            'total_count' => $providers->count()
        ]);

    }

    /**
     * attach data provider to association
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveProvider(Request $request)
    {
        $provider = Company::find($request->provider_id);
        $association = Association::find($request->id);
        if (!$provider){
            msg('provider not found',2);
            return redirect()->route('admin:master.association.index');
        }
        if (!$association){
            msg('association not found',2);
            return redirect()->route('admin:master.association.index');
        }
        $max = env('MAX_ASSOCIATION',3);
        if ($provider->associations->count()>=$max){
            msg('Max Association ('.$max.') reached ',2);
            return redirect()->route('admin:master.association.index');
        }
        if (!$provider->associations()->where('id',$association->id)->first()){
            if (!checkRequestExists($request,'membership_id','POST')) {
                $membershipID = $association->id . '-' . generateRandomString(6);
                while (\DB::table('tbl_company_association')->where('membership_id', $membershipID)->first()) {
                    $membershipID = $association->id . '-' . generateRandomString(6);
                }
            }else{
                $membershipID = $request->get('membership_id');
            }
            $provider->associations()->attach($association, ['membership_id' => $membershipID]);
        }

        msg('Success');
        return redirect()->route('admin:master.association.index');
    }

    /**
     * save ne association
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $rule = [
            'association_name' => 'required|unique:tbl_association,association_name',
            'association_logo' => 'required|image',
        ];
        $this->validate($request, $rule);
        try{
            \DB::beginTransaction();
            $data = [
                'association_name'=>$request->input('association_name'),
                'association_desc'=>$request->input('association_desc'),

            ];
            $path = 'uploads/assoication';
            if (!File::isDirectory(Storage::disk('public')->path($path))) {
                File::makeDirectory(Storage::disk('public')->path($path), 0777, true, true);
            }
            $source = $request->file('association_logo');
            $name = 'association-' . generateRandomString(6) . time() . '.' . $source->getClientOriginalExtension();
            if (Image::make($source)->save(Storage::disk('public')->path($path . '/' . $name))) {
                $data['association_logo'] = Storage::url($path . '/' . $name);
            }
            $lang= Association::create($data);
            msg($lang->name.' created');
            \DB::commit();
            return redirect()->back();
        }catch (\Exception $exception){
            \DB::rollBack();
            dd($exception);
            msg('Something Wrong');
            return redirect()->back();
        }


    }

    /**
     * update association
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $rule = [
            'id'=>'required|exists:tbl_association,id',
            'association_name' => [
                'required',
                Rule::unique('tbl_association','association_name')->whereNot('id',$request->id)
            ],
        ];
        if ($request->hasFile('association_logo')){
            $rule['association_logo']='image';
        }
        $this->validate($request, $rule);
        $lang = Association::find($request->id);
        if ($lang) {
            $data = [
                'association_name'=>$request->input('association_name'),
                'association_desc'=>$request->input('association_desc'),

            ];
            if ($request->hasFile('association_logo')){
                $path = 'uploads/assoication';
                if (!File::isDirectory(Storage::disk('public')->path($path))) {
                    File::makeDirectory(Storage::disk('public')->path($path), 0777, true, true);
                }
                $source = $request->file('association_logo');
                $name = 'association-' . generateRandomString(6) . time() . '.' . $source->getClientOriginalExtension();
                if (Image::make($source)->save(Storage::disk('public')->path($path . '/' . $name))) {
                    $data['association_logo'] = Storage::url($path . '/' . $name);
                    File::delete(public_path($lang->association_logo));
                }
            }
            $lang->update($data);
            msg('Association Updated!');
            return redirect()->back();
        }
        msg('Association not found!', 2);
        return redirect()->back();
    }

    /**
     * delete association
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function delete(Request $request)
    {
        $lang = Association::find($request->id);
        if ($lang) {
            $lang->delete();
            msg('Association Deleted!');
            return redirect()->back();
        }
        msg('Association not found!', 2);
        return redirect()->back();
    }
}
