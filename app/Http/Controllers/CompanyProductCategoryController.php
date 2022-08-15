<?php

namespace App\Http\Controllers;

use App\product_category;
use Illuminate\Http\Request;
use App\Http\Requests\ProductCategoryFormRequest;

class CompanyProductCategoryController extends Controller
{

    var $company = 0;
    var $user = 0;

    public function __construct(Request $request)
    {
        $this->middleware('host');
        $this->middleware('auth');
        //$this->middleware('company');
        
    }

    /**
     * Function initalize get data user
     *
     * @param  mixed $request
     *
     * @return void
     */
    private function initalize(Request $request){
        $user = \Auth::user();
        $this->user = $user->id_user_agen;
        $this->company = $user->id_company;
        // $this->company = $request->get('my_company');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->initalize($request);

        $product_category = \App\Models\ProductCategory::where('id_company',$this->company)->get();
        

        return view('dashboard.company.product_category.index'
                        ,['product_category'=>$product_category,
                            
                        ]
                    );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function create()
    {
        $product_type = \App\Models\ProductType::get();
        

        return view('dashboard.company.product_category.create'
                        ,['product_type'=>$product_type,
                            
                        ]
                    ); 
    }

    
    /**
     * Store a newly created resource in storage.
     *
     * @param  mixed $request
     *
     * @return void
     */
    public function store(ProductCategoryFormRequest $request){
        $this->user = \Auth::user()->id_user_agen;
        $this->initalize($request);
        
        $status = $request->get('status');
        if(empty($status)){
            $status = 0;
        }

        $new_instance = \App\Models\ProductCategory::create([
                                                    'category_name'=>$request->get('category_name'),
                                                    'id_product_type'=>$request->get('product_type'),
                                                    'created_by'=>$this->user,
                                                    'id_company'=>$this->company,
                                                    'status'=>$status,
                                                ]);

        return json_encode(['status'=>200,'message'=>'New Product Category Created']);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  mixed $product_category
     *
     * @return void
     */
    public function edit(\App\Models\ProductCategory $product_category)
    {
        
        $product_type = \App\Models\ProductType::get();
        return view('dashboard.company.product_category.edit',[
                    'product_category' => $product_category,
                    'product_type' => $product_type,
                ]);
    }

    


    
    /**
     * Update the specified resource in storage.
     *
     * @param  mixed $request
     * @param  mixed $product_category
     *
     * @return void
     */
    public function update(\App\Http\Requests\ProductCategoryFormRequest $request, \App\Models\ProductCategory $product_category)
    {
        
        $this->initalize($request);
        
        \DB::transaction(function () use($product_category,$request) {

            if($product_category->id_company != $this->company){
                return response()->json([
                                        'status' => 400,
                                        'message' => 'Action not allowed'
                                    ]);
                                    
            }

            \App\Models\ProductCategory::where(['id_company'=>$this->company
                                                ,'id_category'=>$product_category->id_category])
                                        ->update([
                                                    'category_name'=>$request->get('category_name'),
                                                    'id_product_type'=>$request->get('product_type'),
                                                    'id_company'=>$this->company,
                                                    'status'=>$request->get('status')
                                                ]);

            
            
        });

        return response()->json([
                                    'status' => 200,
                                    'message' => 'Category Saved'
                                ]);

    }

    /**
     * function find category data
     *
     * @param  mixed $request
     *
     * @return void
     */
    public function find_category(Request $request){
        $this->initalize($request);
        $product_type = $request->get('product_type');
        $category = \App\Models\ProductCategory::where(['id_company'=>$this->company,'id_product_type'=>$product_type,'status'=>true])->get();

        $d_data = [];
        foreach($category as $row){
            array_push($d_data,['value'=>$row->category_name,'data'=>$row->id_category]);
        }
        return response()->json([
                                'suggestions' => $d_data
                            ]);
    }
}
