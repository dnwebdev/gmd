<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CompanyThemeController extends Controller
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
        return view('dashboard.company.theme.index',['banner'=>$banner]);   
    }

    public function edit(Request $request){
        $this->initalize($request);
        $id = $request->segment(3);

        $theme = \App\Models\CompanyTheme::where(['id_company'=>$this->company,'id_theme'=>$id])->first();
        $list_status = \App\Models\CompanyTheme::list_status();
        if(!$theme){
            $theme = \App\Models\Theme::find($id)->first();
        }

        return view('dashboard.company.theme.edit',['theme'=>$theme,'list_status'=>$list_status]);
    }

    public function update(\App\Models\Theme $theme){

        $request = app('\App\Http\Requests\ThemeCompanyFormRequest');
        $this->initalize($request);
        
        if(!$theme){
            return response()->json([
                                'status' => 404,
                                'message' => 'Theme is not found'
                            ]);
        }


        $company_theme = \App\Models\CompanyTheme::where(['id_theme'=>$theme->id_theme,'id_company'=>$this->company])->first();
        if(!$company_theme){
            \DB::transaction(function () use($company_theme,$theme,$request) {
            
                $status = $request->get('status');
                if(empty($status)){
                    $status = 0;
                }
                
                \App\Models\CompanyTheme::create([
                                'header_bgcolor'=>$request->get('header_bgcolor'),
                                'navbar_bgcolor'=>$request->get('navbar_bgcolor'),
                                'navbar_textcolor'=>$request->get('navbar_textcolor'),
                                'body_bgcolor'=>$request->get('body_bgcolor'),
                                'body_secondary_bgcolor'=>$request->get('body_secondary_bgcolor'),
                                'button_primary_bgcolor'=>$request->get('button_primary_bgcolor'),
                                'button_primary_textcolor'=>$request->get('button_primary_textcolor'),
                                'button_secondary_bgcolor'=>$request->get('button_secondary_bgcolor'),
                                'button_secondary_textcolor'=>$request->get('button_secondary_textcolor'),
                                'status'=>$status,
                                'id_company'=>$this->company,
                                'id_theme'=>$theme->id_theme,
                            ]);

        
            });
        }
        else{
            \DB::transaction(function () use($company_theme,$request) {
                
                $status = $request->get('status');
                if(empty($status)){
                    $status = 0;
                }
                
                $company_theme->update([
                                'header_bgcolor'=>$request->get('header_bgcolor'),
                                'navbar_bgcolor'=>$request->get('navbar_bgcolor'),
                                'navbar_textcolor'=>$request->get('navbar_textcolor'),
                                'body_bgcolor'=>$request->get('body_bgcolor'),
                                'body_secondary_bgcolor'=>$request->get('body_secondary_bgcolor'),
                                'button_primary_bgcolor'=>$request->get('button_primary_bgcolor'),
                                'button_primary_textcolor'=>$request->get('button_primary_textcolor'),
                                'button_secondary_bgcolor'=>$request->get('button_secondary_bgcolor'),
                                'button_secondary_textcolor'=>$request->get('button_secondary_textcolor'),
                                'status'=>$status,
                            ]);

        
            });
        }


                
        return response()->json([
                                'status' => 200,
                                'message' => 'Theme Configuration Saved'
                            ]);
    }
}
