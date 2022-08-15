<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CompanyBannerController extends Controller
{
    


    var $company = 0;

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

    public function index(Request $request)
    {
        $this->initalize($request);
        $banner = \App\Models\WebsiteBanner::where('id_company',$this->company)->get();
        return view('dashboard.company.banner.index',['banner'=>$banner]);
    }

    public function create()
    {
        return view('dashboard.company.banner.create');
    }

    
    public function store(\App\Http\Requests\BannerFormRequest $request)
    {
        $this->initalize($request);
        $id = null;
        
        if ($request->hasFile('image'))
        {
            $file = $request->file('image');
        
            $image_name = time()."-".$file->getClientOriginalName();
            $file->move('uploads/banners/', $image_name);
            $image = \Image::make(sprintf('uploads/banners/%s', $image_name))->resize(null, 400,function($constraint){
                $constraint->aspectRatio();
            })->save();

            
            \DB::transaction(function () use($request,$image_name,&$id) {
                $newbanner = \App\Models\WebsiteBanner::create([
                                                        'link'=>$request->get('link'),
                                                        'image'=>$image_name,
                                                        'description'=>$request->get('description'),
                                                        'status'=>$request->get('status')?true:false,
                                                        'id_company'=>$this->company,
                                                        ]);
                $id = $newbanner->id;

            });

            return json_encode(['status'=>200,'message'=>'<a href="'.Route('company.banner.edit',$id).'">New Banner Created</a>']);
        }
        else{
            return response()->json([
                                        'status' => 400,
                                        'message' => 'No Image Uploaded'
                                    ]);
        }

        

        
    }


    public function edit(Request $request,\App\Models\WebsiteBanner $banner)
    {
        
        $this->initalize($request);
        if($banner->id_company != $this->company){
            return response()->json([
                                        'status' => 405,
                                        'message' => 'Action not allowed'
                                    ]);
        }
        return view('dashboard.company.banner.edit',['banner'=>$banner]);
    }

    
    public function update(\App\Models\WebsiteBanner $banner)
    {
        $request = app('App\Http\Requests\BannerFormRequest');
        $this->initalize($request);

        $this->company = $request->get('my_company');

        if($banner->id_company != $this->company){
            return response()->json([
                                    'status' => 400,
                                    'message' => 'Action not allowed'
                                ]);
                                
        }

        \DB::transaction(function () use($banner,$request) {
         
            $banner->update([
                            'link'=>$request->get('link'),
                            //'image'=>$request->get('image'),
                            'description'=>$request->get('description'),
                            'status'=>$request->get('status')?true:false,
                            ]);

            if ($request->hasFile('image'))
            {
                //Delete existing
                $file = $banner->image;
                $filename = public_path().'/uploads/banners/'.$file;
                \File::delete($filename);

                //Upload New Image
                $file = $request->file('image');            
                $image_name = time()."-".$file->getClientOriginalName();
                $file->move('uploads/banners/', $image_name);
                $image = \Image::make(sprintf('uploads/banners/%s', $image_name))->resize(800, 400)->save();

                $banner->image = $image_name;
                $banner->save();
            }
        });

        return response()->json([
                                'status' => 200,
                                'message' => 'Banner Saved'
                            ]);
        
    }

    public function delete_image(Request $request){
        $this->initalize($request);
        $this->company = $request->get('my_company');

        $image = $request->get('image');
        $banner = \App\Models\WebsiteBanner::where(['image'=>$image,'id_company'=>$this->company])->first();
        
        if($banner){
            $file = $banner->image;
            $filename = public_path().'/uploads/banners/'.$file;
            \File::delete($filename);

            \App\Models\WebsiteBanner::where(['id'=>$banner->id])->update(['image'=>null]);
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
}
