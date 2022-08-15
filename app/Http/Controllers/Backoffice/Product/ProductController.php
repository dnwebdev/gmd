<?php

namespace App\Http\Controllers\Backoffice\Product;

use App\Models\City;
use App\Models\Country;
use App\Models\Product;
use App\Models\State;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TagProduct;
use App\Exports\ProductExport;

class ProductController extends Controller
{
    public function index($tag_id = null)
    {
        toastr();
        $tag = null;
        if (!empty($tag_id) && request()->is_klhk) {
            $tag = TagProduct::findOrFail($tag_id);
        }
        return viewKlhk('back-office.page.product.index', ['new-backoffice.list.list_product', compact('tag')]);
    }

    public function loadData($tag_id = null)
    {
        $model = Product::with('company', 'city')
            ->when($tag_id, function ($query, $tag_id) {
                return $query->whereHas('tags', function ($query) use ($tag_id) {
                    return $query->where('tag_product_id', $tag_id);
                });
            })
            ->has('city')
            ->has('company')
            ->select('tbl_product.*');

        return \DataTables::of($model)
            ->addIndexColumn()
            ->addColumn('action', function ($model) {
                if (request()->is_klhk) {
                    $html = '<a href="http'.(request()->secure() ? 's' : '').'://'.$model->company->domain_memoria.'/product/detail/'.$model->unique_code.'" target="_blank" data-popup="tooltip" title="Lihat Produk"><i class="icon-eye mr-3"></i></a>';
                    $html .= '<a href="' . route('admin:product.detail', ['id' => $model->id_product]) . '" data-name="' . $model->company_name . '" data-id="' . $model->id . '" data-popup="tooltip" title="Edit"><i class="icon-pencil"></i></a>';
                } else {
                    $html = '<a href="' . route('admin:product.detail', ['id' => $model->id_product]) . '" data-name="' . $model->company_name . '" data-id="' . $model->id . '"  class="btn btn-outline-info btn-preview btn-sm m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill m-btn--air" href=""><i class="fa flaticon-visible"></i></a>';
                }


                return $html;
            })
            ->make(true);
    }

    public function detail($id)
    {
        if (!$data['product'] = Product::find($id)) {
            msg('product not found', 2);
            return redirect()->route('admin:product.index');
        }
        $data['country'] = Country::all()->pluck('country_name', 'id_country');
        $data['state'] = State::whereIdCountry($data['product']->city->state->id_country)->pluck('state_name', 'id_state');
        $data['city'] = City::whereIdState($data['product']->city->id_state)->pluck('city_name', 'id_city');
        return viewKlhk(['back-office.page.product.detail', $data], ['new-backoffice.list.edit_product', $data]);
    }

    public function update($id, Request $request)
    {
        if (!$data['product'] = Product::find($id)) {
            msg('product not found', 2);
            return redirect()->route('admin:product.index');
        }
        $rules = [
//            'product_name' => 'required|max:100',
//            'brief_description' => 'required|max:100',
            'city' => 'required|exists:tbl_city,id_city'
        ];
        $this->validate($request, $rules);
        $data['product']->update(['id_city'=>$request->input('city')]);
        msg('product updated', 1);
        return redirect()->route('admin:product.index');

    }

    public function getCitiesFromState(Request $request)
    {
        if ($state = State::find($request->id)) {
            return response()->json(['cities' => $state->city->pluck('city_name', 'id_city')]);
        }

        return response()->json(['not found'], 404);
    }

    public function getStateFromCountry(Request $request)
    {
        if ($state = Country::find($request->id)) {
            return response()->json([
                'states' => $state->state->pluck('state_name', 'id_state'),
                'cities' => $state->state()->first()->city->pluck('city_name', 'id_city'),
            ]);
        }

        return response()->json(['not found'], 404);
    }

    public function export(Request $request)
    {
        if ($request->input('id') == 'all') {
            $file_name = 'Semua Produk - '.today()->format('d M Y');
        } else {
            $file_name = 'Produk '.$request->input('id');
        }
        return (new ProductExport($request->input('id', 'all'), $request->input('tag_id', 'all')))
            ->download($file_name.'.xls');
    }
}
