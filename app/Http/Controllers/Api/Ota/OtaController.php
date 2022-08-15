<?php

namespace App\Http\Controllers\Api\Ota;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Ota\OtaRequest;
use App\Http\Resources\Ota\OtaResource;
use App\Models\Ota;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Image;
use Illuminate\Http\Request;

class OtaController extends Controller
{
    public function index()
    {
        return OtaResource::collection(Ota::paginate(12));
    }

    public function show($id)
    {
        return new OtaResource(Ota::findOrfail($id));
    }

    public function store(OtaRequest $request)
    {
        try {
            DB::beginTransaction();
            $ota = new Ota();

            $ota->ota_name = $request->input('name');
            $ota->ota_slug = str_slug($request->input('slug'));
            $ota->ota_status = (boolean) $request->input('status');
            $ota->ota_original_markup = $request->input('original_markup');
            $ota->ota_gomodo_markup = $request->input('gomodo_markup');

            if ($request->hasFile('image')) {
                $icon = $request->file('image');
                $path = storage_path('app/public/uploads/ota/');
                $name = time() . '-' . $ota->ota_slug . '.' . $icon->getClientOriginalExtension();
                if (!File::isDirectory($path)) {
                    File::makeDirectory($path, 0777, true, true);
                }
                \Image::make($icon)->save($path . $name);
                $ota->ota_icon = 'storage/uploads/ota/' . $name;

            }

            $ota->save();
    
            DB::commit();

            return new OtaResource($ota);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'ok' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(OtaRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $ota = Ota::findOrFail($id);

            $ota->ota_name = $request->input('name');
            $ota->ota_slug = str_slug($request->input('slug'));

            if ($request->has('status'))
                $ota->ota_status = (boolean) $request->input('status');
                
            $ota->ota_original_markup = $request->input('original_markup');
            $ota->ota_gomodo_markup = $request->input('gomodo_markup');

            if ($request->hasFile('image')) {
                $icon = $request->file('image');
                $path = storage_path('app/public/uploads/ota/');
                $name = time() . '-' . $ota->ota_slug . '.' . $icon->getClientOriginalExtension();
                if (!File::isDirectory($path)) {
                    File::makeDirectory($path, 0777, true, true);
                }
                if (\Image::make($icon)->save($path . $name)) {
                    $deleteIcon = $ota->icon;
                    if (File::exists($deleteIcon))
                        File::delete($deleteIcon);
                    $ota->ota_icon = 'storage/uploads/ota/' . $name;
                }

            }

            $ota->save();
            DB::commit();
    
            return new OtaResource($ota);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'ok' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function toggle(Request $request, $id)
    {
        $ota = Ota::findOrFail($id);

        $ota->ota_status = !((boolean) $request->input('status'));

        $ota->save();

        return new OtaResource($ota);
    }
}