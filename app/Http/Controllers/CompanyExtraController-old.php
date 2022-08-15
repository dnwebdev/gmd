<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CompanyExtraController extends Controller
{
    var $company = 0;
    var $user = 0;
    public function __construct(Request $request)
    {
        $this->middleware('host');
        $this->middleware('auth');
        //$this->middleware('company');
        
    }

    private function initalize(Request $request){
        $user = \Auth::user();
        $this->user = $user->id_user_agen;
        $this->company = $user->id_company;
        // $this->company = $request->get('my_company');
    }

    public function index(Request $request){
        $this->initalize($request);
        
        $extra = \App\Models\ExtraItem::where('id_company',$this->company)->get();
        return view('dashboard.company.extra.index',['extra'=>$extra]);
    }

    
    public function create()
    {
        $list_currency = \App\Models\ExtraItem::list_currency();
        
        $extra_price_type = \App\Models\ExtraItem::list_price_type();
        return view('dashboard.company.extra.create',['list_currency'=>$list_currency,'list_price_type'=>$extra_price_type]);
    }

    public function store(\App\Http\Requests\ExtraItemFormRequest $request)
    {
        $this->initalize($request);

        $id = null;
        \DB::transaction(function () use($request,&$id) {
            $newextra = \App\Models\ExtraItem::create([
                                                    'extra_name'=>$request->get('extra_name'),
													'currency'=>$request->get('currency'),
                                                    'amount'=>$request->get('amount'),
                                                    'extra_price_type'=>$request->get('extra_price_type'),
                                                    'description'=>$request->get('description'),
                                                    'created_by'=>$this->user,
                                                    'id_company'=>$this->company,
                                                    ]);
            $id = $newextra->id_extra;

            ################# ISERT IMAGE #####################
            if ($request->hasFile('image'))
            {
                $file = $request->file('image');
                $image_name = time()."-".$file->getClientOriginalName();
                $file->move('uploads/extras/', $image_name);
                $image = \Image::make(sprintf('uploads/extras/%s', $image_name))->resize(400, 400)->save();


                $newextra->image = $image_name;
                $newextra->save();
            }
    

        });

        return json_encode(['status'=>200,'message'=>'<a href="'.Route('company.extra.edit',$id).'">New Extra Item Created</a>']);
    }

    
    
    public function edit(Request $request,\App\Models\ExtraItem $extra)
    {
        $this->initalize($request);

        if($extra->id_company != $this->company){
            return response()->json([
                                        'status' => 405,
                                        'message' => 'Action not allowed'
                                    ]);
        }
        return view('dashboard.company.extra.edit',['extra'=>$extra]);
    }

    
    public function update(\App\Http\Requests\ExtraItemFormRequest $request,\App\Models\ExtraItem $extra)
    {
        
        $this->initalize($request);
        
        if($extra->id_company != $this->company){
            return response()->json([
                                    'status' => 400,
                                    'message' => 'Action not allowed'
                                ]);
                                
        }

        \DB::transaction(function () use($extra,$request) {
         
            $extra->update([
                            'extra_name'=>$request->get('extra_name'),
							'currency'=>$request->get('currency'),
                            'amount'=>$request->get('amount'),
                            'extra_price_type'=>$request->get('extra_price_type'),
                            'description'=>$request->get('description'),
                            'created_by'=>$this->user,
                            'id_company'=>$this->company,
                        ]);

            ################# ISERT IMAGE #####################
            if ($request->hasFile('image'))
            {
                $file = $request->file('image');
                $image_name = time()."-".$file->getClientOriginalName();
                $file->move('uploads/extras/', $image_name);
                $image = \Image::make(sprintf('uploads/extras/%s', $image_name))->resize(400, 400)->save();


                $extra->image = $image_name;
                $extra->save();
            }


        });

        return response()->json([
                                'status' => 200,
                                'message' => 'Extra Item Saved'
                            ]);
        
    }

    public function delete_image(Request $request){
        $this->initalize($request);
        $image = $request->get('image');
        
        $extra = \App\Models\ExtraItem::where('image',$image)->first();
        if($extra && $extra->id_company != $this->company){
            return response()->json([
                                'status' => 405,
                                'message' => 'Action not allowed'
                            ]);
        }

        if($extra){
            
            $file = $extra->image;
            
            $extra->image = null;
            $extra->save();
            
            $filename = public_path().'/uploads/extras/'.$file;

            \File::delete($filename);

            return response()->json([
                                'status' => 200,
                                'message' => 'Image Deleted'
                            ]);
        }
        else{
            return response()->json([
                                'status' => 400,
                                'message' => 'Image not found'
                            ]);
        }
        
    }


    public function search(Request $request){
        $this->initalize($request);
        $extra = \App\Models\ExtraItem::where('id_company',$this->company)->get();
        $view = view('dashboard.company.extra.search',['extra'=>$extra]);
        return response()->json([
                                'status' => 200,
                                'message' => 'Ok',
                                'data' => $view->render(),
                            ]);
    }

}
