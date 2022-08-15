<?php

namespace App\Http\Controllers\Backoffice\Setting;

use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function index()
    {
        toastr();
        return viewKlhk('back-office.page.setting.admin.index', 'new-backoffice.users.index');
    }

    public function loadData()
    {
        $model = Admin::with('role');
        if (\request()->has('is_klhk')){
            $model = $model->where('is_klhk', request()->is_klhk);
        }
        return \DataTables::of($model)
            ->addIndexColumn()
            ->addColumn('action', function ($model) {
                $html = '';
                if ($model->role->role_slug !== 'super-admin') {
                    $html = '<a class="'.(!request()->is_klhk ? 'btn btn-outline-info btn-preview btn-sm m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill m-btn--air' : '').'" href="' . route('admin:setting.admin.edit', ['id' => $model->id]) . '"><i class="'.(request()->is_klhk ? 'icon-eye' : 'fa flaticon-visible').'"></i></a>';
                }

                return $html;
            })
            ->make(true);
    }

    public function edit($id)
    {
        $admin = Admin::whereId($id)->WhereHas('role', function ($r) {
            $r->where('role_slug', '!=', 'super-admin');
        })->first();
        if (!$admin) {
            msg('Admin Not Found', 2);
            return redirect()->route('admin:setting.admin.index');
        }

        return viewKlhk(['back-office.page.setting.admin.edit', compact('admin')], ['new-backoffice.users.edit_user', compact('admin')]);
    }

    public function update($id, Request $request)
    {
        $klhk = false;
        if ($request->is_klhk){
            $klhk = true;
        }

        $admin = Admin::where('is_klhk', $klhk)->whereId($id)->WhereHas('role', function ($r) {
            $r->where('role_slug', '!=', 'super-admin');
        })->first();
        if (!$admin) {
            msg('Admin Not Found', 2);
            return redirect()->route('admin:setting.admin.index');
        }
        $rules = [
            'admin_name' => 'required',
            'email' => 'email|unique:admins,email,' . $id,
            'admin_avatar' => 'nullable|file|mimes:png,jpg,jpeg'
        ];

        $attributes = [];
        $messages = [];

        if ($request->is_klhk) {
            $attributes = [
                'admin_name'  => 'nama'
            ];

            $messages = [
                'email.email'   => 'Kolom isian email harus berupa alamat email yang valid.'
            ];
        }

        $this->validate($request, $rules, $messages, $attributes);
        if (!\File::isDirectory(storage_path('app/public/admin'))) {
            \File::makeDirectory(storage_path('app/public/admin'), 777, true, true);
        }
        try {
            \DB::beginTransaction();
            $deleted = null;
            $admin->admin_name = $request->get('admin_name');
            $admin->email = $request->get('email');
            $admin->is_klhk = $request->is_klhk ? 1 : 0;
            if ($request->hasFile('admin_avatar')) {
                $name = $admin->id . '-' . generateRandomString() . '.' . $request->file('admin_avatar')
                        ->getClientOriginalExtension();
                if (\Image::make($request->file('admin_avatar'))->fit(200, 200)->save(storage_path('app/public/admin/'
                    . $name))) {
                    $deleted = $admin->admin_avatar;
                    $admin->admin_avatar = 'storage/admin/' . $name;
                }
            }

            $admin->save();
            \DB::commit();
            \File::delete($deleted);
            msg('Admin Profile Updated');
            return redirect()->route('admin:setting.admin.index');
        } catch (\Exception $exception) {
            \DB::rollBack();
            msg($exception->getMessage(), 2);
            return redirect()->route('admin:setting.admin.index');
        }

    }

    public function add()
    {
        toastr();
        return viewKlhk('back-office.page.setting.admin.add', 'new-backoffice.users.add_user');
    }

    public function store(Request $request)
    {
        $rules = [
            'admin_name' => 'required',
            'email' => 'email|unique:admins,email',
            'admin_avatar' => 'nullable|file|mimes:png,jpg,jpeg',
            'password' => 'required|min:6',
            'role_id'=>'required|exists:roles,id'
        ];

        $attributes = [];
        $messages = [];

        if ($request->is_klhk) {
            $attributes = [
                'admin_name'  => 'nama'
            ];

            $messages = [
                'email.email'   => 'Kolom isian email harus berupa alamat email yang valid.'
            ];
        }

        $this->validate($request, $rules, $messages, $attributes);

        if (!\File::isDirectory(storage_path('app/public/admin'))) {
            \File::makeDirectory(storage_path('app/public/admin'), 777, true, true);
        }
        try {
            \DB::beginTransaction();
            $admin = new Admin();
            $admin->password = bcrypt($request->get('password'));
            $admin->admin_name = $request->get('admin_name');
            $admin->role_id = $request->get('role_id');
            $admin->email = $request->get('email');
            $admin->is_klhk = request()->is_klhk?1:0;
            if ($request->hasFile('admin_avatar')) {
                $name = $admin->id . '-' . generateRandomString() . '.' . $request->file('admin_avatar')
                        ->getClientOriginalExtension();
                if (\Image::make($request->file('admin_avatar'))->fit(200, 200)->save(storage_path('app/public/admin/'
                    . $name))) {
                    $admin->admin_avatar = 'storage/admin/' . $name;
                }
            }

            $admin->save();
            \DB::commit();
            msg('Admin added');
            return redirect()->route('admin:setting.admin.index');
        } catch (\Exception $exception) {
            \DB::rollBack();
            msg($exception->getMessage(), 2);
            return redirect()->route('admin:setting.admin.index');
        }
    }
}
