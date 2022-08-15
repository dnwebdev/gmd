<?php

namespace App\Http\Controllers\Backoffice\Directory;
use App\Models\City;
use App\Models\OrderDetail;
use App\Models\State;
use File;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Image;
use Storage;

class PopularCityCtrl extends Controller
{
    /**
     * show view popular city
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {

        $data['top_cities'] = State::whereHas('city', function ($city) {
            $city->whereHas('product', function ($p) {
                $p->where('publish', 1);
            });
        })->paginate(12);

        return view('back-office.page.directory.popular.city', $data);
    }

    /**
     * save popular city Image
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveImage(Request $request)
    {
        if ($city = State::find($request->input('id_city'))) {
            $path = 'uploads/directory/popular/state/' . $city->id_state;
            $source = $request->file('city_image');
            if (!File::isDirectory(Storage::disk('public')->path($path))) {
                File::makeDirectory(Storage::disk('public')->path($path), 0777, true, true);
            }
            $name = 'popular-state-' . generateRandomString(6) . time() . '.' . $source->getClientOriginalExtension();
            if (Image::make($source)->fit(1440,893)->save(Storage::disk('public')->path($path . '/' . $name))) {
                $delete = $city->state_image;
                $city->state_image = Storage::url($path . '/' . $name);
                $city->save();
                if (\File::exists(public_path($delete))) {
                    File::delete(public_path($delete));
                }
                return apiResponse(200, 'OK');
            }
            return apiResponse(400, 'Cannot Update Image');
        }
        return apiResponse(404,'City Not Found');
    }
}
