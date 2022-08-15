<?php

namespace App\Http\Controllers\Backoffice\Profile;

use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProfileCtrl extends Controller
{
    public function index()
    {
        toastr();
        return viewKlhk('back-office.page.profile.edit', 'new-backoffice.profile.index');
    }

    public function update( Request $request )
    {
        $rules = [
            'admin_name' => 'required',
            'email' => 'email|unique:admins,email,' . auth('admin')->id(),
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

        $rules['new_password'] = 'nullable|confirmed|min:6';
        if (checkRequestExists($request,'new_password')){
            $rules['password'] = 'required_with:new_password|min:6';
        }


        $this->validate($request, $rules, $messages, $attributes);

        if (!\File::isDirectory(storage_path('app/public/admin'))) {
            \File::makeDirectory(storage_path('app/public/admin'), 777, true, true);
        }
        try {
            \DB::beginTransaction();
            $admin = Admin::find(auth('admin')->id());
            if (checkRequestExists($request, 'new_password')) {
                if (!\Hash::check($request->get('password'), $admin->password)) {
                    msg('password salah', 2);
                    return redirect()->back();
                }

                $admin->password = bcrypt($request->get('new_password'));
            }
            $deleted = null;
            $admin->admin_name = $request->get('admin_name');
            $admin->email = $request->get('email');
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
            msg('Profile Updated');
            return redirect()->back();
        } catch (\Exception $exception) {
            \DB::rollBack();
            msg($exception->getMessage(), 2);
            return redirect()->back();
        }
    }
}
