@extends('back-office.layout.index')
@section('subheader')
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title ">Provider</h3>
        </div>

    </div>
@stop
@section('content')
    <div class="m-portlet">
        <div class="m-portlet__body  ">
            <div class="row ">
                <div class="col-12">
                    <div class="m-portlet m-portlet--tabs">
                        <div class="m-portlet__head">
                            <div class="m-portlet__head-tools">
                                <ul class="nav nav-tabs m-tabs-line m-tabs-line--primary m-tabs-line--2x"
                                    role="tablist">
                                    <li class="nav-item m-tabs__item">
                                        <a class="nav-link m-tabs__link  active show" data-toggle="tab"
                                           href="#m_tabs_6_1" role="tab"
                                           aria-selected="false">
                                            <i class="la la-user"></i> Profile Company
                                        </a>
                                    </li>
                                    <li class="nav-item m-tabs__item">
                                        <a class="nav-link m-tabs__link" data-toggle="tab" href="#m_tabs_6_2" role="tab"
                                           aria-selected="false">
                                            <i class="la la-user"></i> Login user
                                        </a>
                                    </li>
                                    <li class="nav-item m-tabs__item">
                                        <a class="nav-link m-tabs__link" data-toggle="tab"
                                           href="#m_tabs_6_3" role="tab" aria-selected="true">
                                            <i class="la la-bank"></i>Bank Account
                                        </a>
                                    </li>
                                    <li class="nav-item m-tabs__item">
                                        <a class="nav-link m-tabs__link" data-toggle="tab"
                                           href="#m_tabs_6_4" role="tab" aria-selected="true">
                                            <i class="la la-bookmark-o"></i>Association
                                        </a>
                                    </li>
                                    <li class="nav-item m-tabs__item">
                                        <a class="nav-link m-tabs__link" data-toggle="tab"
                                           href="#m_tabs_6_5" role="tab" aria-selected="true">
                                            <i class="la la-bookmark-o"></i>GoogleWidget
                                        </a>
                                    </li>

                                </ul>
                            </div>
                        </div>
                        <div class="m-portlet__body">
                            <div class="tab-content">
                                <div class="tab-pane  active show" id="m_tabs_6_1" role="tabpanel">
                                    {!! Form::model($company,['url'=>route('admin:providers.update-company',['id'=>$company->id_company])]) !!}
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group m-form__group">
                                                {!! Form::label('domain_memoria','Domain Gomodo') !!}
                                                {!! Form::text('domain_memoria',null,['class'=>'form-control m-input']) !!}
                                            </div>
                                            <div class="form-group m-form__group">
                                                {!! Form::label('company_name','Company name') !!}
                                                {!! Form::text('company_name',null,['class'=>'form-control m-input']) !!}
                                            </div>
                                            <div class="form-group m-form__group">
                                                {!! Form::label('email_company','Company Email') !!}
                                                {!! Form::text('email_company',null,['class'=>'form-control m-input']) !!}
                                            </div>
                                            <div class="form-group m-form__group">
                                                {!! Form::label('phone_company','Company Phone') !!}
                                                {!! Form::text('phone_company',null,['class'=>'form-control m-input']) !!}
                                            </div>
                                            <div class="form-group m-form__group">
                                                {!! Form::label('ownership_status','Ownership Status') !!}
                                                {!! Form::select('ownership_status',['personal'=>'Personal','corporate'=>'Corporate'],null,['class'=>'form-control m-input']) !!}
                                            </div>
                                            <div class="form-group m-form__group">
                                                {!! Form::label('verified_provider','Verified Provider') !!}
                                                {!! Form::select('verified_provider',[0=>'No',1=>'Yes'],null,['class'=>'form-control m-input']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group m-form__group">
                                                {!! Form::label('about','About Company') !!}
                                                {!! Form::textarea('about',null,['class'=>'summernote form-control m-input']) !!}
                                            </div>
                                            <div class="form-group m-form__group">
                                                {!! Form::label('google_analitics_code') !!}
                                                {!! Form::text('ga_code',null,['class'=>'form-control m-input']) !!}
                                            </div>
                                            <div class="form-group m-form__group">
                                                {!! Form::label('klhk','KLHK') !!}
                                                {!! Form::select('is_klhk',[1=>'Yes',0=>'No'],null,['class'=>'form-control m-input']) !!}
                                            </div>
                                            <div class="form-group m-form__group">
                                                {!! Form::label('status','Status Provider') !!}
                                                {!! Form::select('status',[0=>'Banned',1=>'Active'],null,['class'=>'form-control m-input']) !!}
                                            </div>
                                            {{--                                            <a href="{{route('admin:providers.login-as-user',['id'=>$company->id_company])}}"--}}
                                            {{--                                               class="btn btn-primary btn-block "><i--}}
                                            {{--                                                        class="fa fa-user"> Login As User</i>--}}
                                            {{--                                            </a>--}}

                                        </div>
                                        <div class="col-md-12 text-center mt-4">
                                            <button class="btn btn-primary btn-do-submit" type="button"><i
                                                        class="fa fa-save"> Save</i>
                                            </button>
                                        </div>
                                    </div>
                                    {!! Form::close() !!}
                                </div>
                                <div class="tab-pane" id="m_tabs_6_2" role="tabpanel">
                                    <div class="row">
                                        @foreach($users as $user)
                                            {{--                                            {{dd($user)}}--}}
                                            <div class="col-md-6" style="border: 1px solid #fafafa">
                                                {!! Form::model($user,['url'=>route('admin:providers.update-user',['id'=>$company->id_company])]) !!}
                                                <div class="form-group m-form__group">
                                                    {!! Form::hidden('id_user_agen',$user->id_user_agen,['class'=>'form-control m-input']) !!}
                                                    {!! Form::label('first_name','First Name') !!}
                                                    {!! Form::text('first_name',null,['class'=>'form-control m-input']) !!}
                                                </div>
                                                <div class="form-group m-form__group">
                                                    {!! Form::label('last_name','Last name') !!}
                                                    {!! Form::text('last_name',null,['class'=>'form-control m-input']) !!}
                                                </div>
                                                <div class="form-group m-form__group">
                                                    {!! Form::label('email','Email') !!}
                                                    {!! Form::email('email',null,['class'=>'form-control m-input']) !!}
                                                </div>
                                                <div class="form-group m-form__group">
                                                    {!! Form::label('phone','User Phone') !!}
                                                    {!! Form::text('phone',null,['class'=>'form-control m-input']) !!}
                                                </div>
                                                <div class="form-group m-form__group">
                                                    {!! Form::label('password','Password') !!}
                                                    {!! Form::password('password',['class'=>'form-control m-input']) !!}
                                                </div>

                                                <div class="form-group m-form__group">
                                                    {!! Form::label('status','Status') !!}
                                                    {!! Form::select('status',[1=>'Active',0=>'Non Active'],null,['class'=>'form-control m-input']) !!}
                                                </div>
                                                <div class="form-group m-form__group">
                                                    <button class="btn btn-primary btn-do-submit" type="button"><i
                                                                class="fa fa-save"> Save</i>
                                                    </button>
                                                </div>
                                                {!! Form::close() !!}
                                            </div>
                                        @endforeach
                                    </div>

                                </div>
                                <div class="tab-pane" id="m_tabs_6_3" role="tabpanel">
                                    <div class="row">
                                        <div class="col-12">

                                        </div>

                                        @if($company->bank)
                                            {!! Form::model($company->bank,['style'=>'width:100%','url'=>route('admin:providers.update-bank',['id'=>$company->id_company])]) !!}
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group m-form__group">
                                                        {!! Form::label('bank','BANK') !!}
                                                        <select name="bank" class="form-control select2"
                                                                placeholder="Select Bank">
                                                            <option value="BCA" {{ $company->bank->bank == 'BCA' ? 'selected' : '' }}>
                                                                Bank Central Asia (BCA)
                                                            </option>
                                                            <option value="DANAMON" {{ $company->bank->bank == 'DANAMON' ? 'selected' : '' }}>
                                                                Bank Danamon
                                                            </option>
                                                            <option value="ARTHA" {{ $company->bank->bank == 'ARTHA' ? 'selected' : '' }}>
                                                                Bank Artha Graha International
                                                            </option>
                                                            <option value="ANZ" {{ $company->bank->bank == 'ANZ' ? 'selected' : '' }}>
                                                                Bank ANZ Indonesia
                                                            </option>
                                                            <option value="BJB" {{ $company->bank->bank == 'BJB' ? 'selected' : '' }}>
                                                                Bank BJB
                                                            </option>
                                                            <option value="BJB_SYR" {{ $company->bank->bank == 'BJB_SYR' ? 'selected' : '' }}>
                                                                Bank BJB Syariah
                                                            </option>
                                                            <option value="PERMATA" {{ $company->bank->bank == 'PERMATA' ? 'selected' : '' }}>
                                                                Bank Permata
                                                            </option>
                                                            <option value="PANIN" {{ $company->bank->bank == 'PANIN' ? 'selected' : '' }}>
                                                                Bank Panin
                                                            </option>
                                                            <option value="CAPITAL" {{ $company->bank->bank == 'CAPITAL' ? 'selected' : '' }}>
                                                                Bank Capital Indonesia
                                                            </option>
                                                            <option value="ARTOS" {{ $company->bank->bank == 'ARTOS' ? 'selected' : '' }}>
                                                                Bank Artos Indonesia
                                                            </option>
                                                            <option value="BUMI_ARTA" {{ $company->bank->bank == 'BUMI_ARTA' ? 'selected' : '' }}>
                                                                Bank Bumi Arta
                                                            </option>
                                                            <option value="BNI_SYR" {{ $company->bank->bank == 'BNI_SYR' ? 'selected' : '' }}>
                                                                Bank BNI Syariah
                                                            </option>
                                                            <option value="BUKOPIN" {{ $company->bank->bank == 'BUKOPIN' ? 'selected' : '' }}>
                                                                Bank Bukopin
                                                            </option>
                                                            <option value="AGRONIAGA" {{ $company->bank->bank == 'AGRONIAGA' ? 'selected' : '' }}>
                                                                Bank BRI Agroniaga
                                                            </option>
                                                            <option value="MANDIRI" {{ $company->bank->bank == 'MANDIRI' ? 'selected' : '' }}>
                                                                Bank Mandiri
                                                            </option>
                                                            <option value="AGRIS" {{ $company->bank->bank == 'AGRIS' ? 'selected' : '' }}>
                                                                Bank Agris
                                                            </option>
                                                            <option value="CIMB" {{ $company->bank->bank == 'CIMB' ? 'selected' : '' }}>
                                                                Bank CIMB Niaga
                                                            </option>
                                                            <option value="SINARMAS" {{ $company->bank->bank == 'SINARMAS' ? 'selected' : '' }}>
                                                                Bank Sinarmas
                                                            </option>
                                                            <option value="BANGKOK" {{ $company->bank->bank == 'BANGKOK' ? 'selected' : '' }}>
                                                                Bangkok Bank
                                                            </option>
                                                            <option value="BISNIS_INTERNASIONAL" {{ $company->bank->bank == 'BISNIS_INTERNASIONAL' ? 'selected' : '' }}>
                                                                Bank Bisnis Internasional
                                                            </option>
                                                            <option value="ANGLOMAS" {{ $company->bank->bank == 'ANGLOMAS' ? 'selected' : '' }}>
                                                                Anglomas International Bank
                                                            </option>
                                                            <option value="ANDARA" {{ $company->bank->bank == 'ANDARA' ? 'selected' : '' }}>
                                                                Bank Andara
                                                            </option>
                                                            <option value="BNP_PARIBAS" {{ $company->bank->bank == 'BNP_PARIBAS' ? 'selected' : '' }}>
                                                                Bank BNP Paribas
                                                            </option>
                                                            <option value="COMMONWEALTH" {{ $company->bank->bank == 'COMMONWEALTH' ? 'selected' : '' }}>
                                                                Bank Commonwealth
                                                            </option>
                                                            <option value="BCA_SYR" {{ $company->bank->bank == 'BCA_SYR' ? 'selected' : '' }}>
                                                                Bank Central Asia (BCA) Syariah
                                                            </option>
                                                            <option value="DANAMON_UUS" {{ $company->bank->bank == 'DANAMON_UUS' ? 'selected' : '' }}>
                                                                Bank Danamon UUS
                                                            </option>
                                                            <option value="INA_PERDANA" {{ $company->bank->bank == 'INA_PERDANA' ? 'selected' : '' }}>
                                                                Bank Ina Perdania
                                                            </option>
                                                            <option value="DKI" {{ $company->bank->bank == 'DKI' ? 'selected' : '' }}>
                                                                Bank DKI
                                                            </option>
                                                            <option value="GANESHA" {{ $company->bank->bank == 'GANESHA' ? 'selected' : '' }}>
                                                                Bank Ganesha
                                                            </option>
                                                            <option value="CHINATRUST" {{ $company->bank->bank == 'CHINATRUST' ? 'selected' : '' }}>
                                                                Bank Chinatrust Indonesia
                                                            </option>
                                                            <option value="HANA" {{ $company->bank->bank == 'HANA' ? 'selected' : '' }}>
                                                                Bank Hana
                                                            </option>
                                                            <option value="DINAR_INDONESIA" {{ $company->bank->bank == 'DINAR_INDONESIA' ? 'selected' : '' }}>
                                                                Bank Dinar Indonesia
                                                            </option>
                                                            <option value="CIMB_UUS" {{ $company->bank->bank == 'CIMB_UUS' ? 'selected' : '' }}>
                                                                Bank CIMB Niaga UUS
                                                            </option>
                                                            <option value="DKI_UUS" {{ $company->bank->bank == 'DKI_UUS' ? 'selected' : '' }}>
                                                                Bank DKI UUS
                                                            </option>
                                                            <option value="FAMA" {{ $company->bank->bank == 'FAMA' ? 'selected' : '' }}>
                                                                Bank Fama International
                                                            </option>
                                                            <option value="HIMPUNAN_SAUDARA" {{ $company->bank->bank == 'HIMPUNAN_SAUDARA' ? 'selected' : '' }}>
                                                                Bank Himpunan Saudara 1906
                                                            </option>
                                                            <option value="ICBC" {{ $company->bank->bank == 'ICBC' ? 'selected' : '' }}>
                                                                Bank ICBC Indonesia
                                                            </option>
                                                            <option value="HARDA_INTERNASIONAL" {{ $company->bank->bank == 'HARDA_INTERNASIONAL' ? 'selected' : '' }}>
                                                                Bank Harda Internasional
                                                            </option>
                                                            <option value="DBS" {{ $company->bank->bank == 'DBS' ? 'selected' : '' }}>
                                                                Bank DBS Indonesia
                                                            </option>
                                                            <option value="INDEX_SELINDO" {{ $company->bank->bank == 'INDEX_SELINDO' ? 'selected' : '' }}>
                                                                Bank Index Selindo
                                                            </option>
                                                            <option value="PANIN_SYR" {{ $company->bank->bank == 'PANIN_SYR' ? 'selected' : '' }}>
                                                                Bank Panin Syariah
                                                            </option>
                                                            <option value="JAWA_TENGAH_UUS" {{ $company->bank->bank == 'JAWA_TENGAH_UUS' ? 'selected' : '' }}>
                                                                BPD Jawa Tengah UUS
                                                            </option>
                                                            <option value="KALIMANTAN_TIMUR_UUS" {{ $company->bank->bank == 'KALIMANTAN_TIMUR_UUS' ? 'selected' : '' }}>
                                                                BPD Kalimantan Timur UUS
                                                            </option>
                                                            <option value="BTN_UUS" {{ $company->bank->bank == 'BTN_UUS' ? 'selected' : '' }}>
                                                                Bank Tabungan Negara (BTN) UUS
                                                            </option>
                                                            <option value="ACEH_UUS" {{ $company->bank->bank == 'ACEH_UUS' ? 'selected' : '' }}>
                                                                BPD Aceh UUS
                                                            </option>
                                                            <option value="KALIMANTAN_BARAT_UUS" {{ $company->bank->bank == 'KALIMANTAN_BARAT_UUS' ? 'selected' : '' }}>
                                                                BPD Kalimantan Barat UUS
                                                            </option>
                                                            <option value="JASA_JAKARTA" {{ $company->bank->bank == 'JASA_JAKARTA' ? 'selected' : '' }}>
                                                                Bank Jasa Jakarta
                                                            </option>
                                                            <option value="DAERAH_ISTIMEWA" {{ $company->bank->bank == 'DAERAH_ISTIMEWA' ? 'selected' : '' }}>
                                                                BPD Daerah Istimewa Yogyakarta (DIY)
                                                            </option>
                                                            <option value="KALIMANTAN_BARAT" {{ $company->bank->bank == 'KALIMANTAN_BARAT' ? 'selected' : '' }}>
                                                                BPD Kalimantan Barat
                                                            </option>
                                                            <option value="MASPION" {{ $company->bank->bank == 'MASPION' ? 'selected' : '' }}>
                                                                Bank Maspion Indonesia
                                                            </option>
                                                            <option value="MAYAPADA" {{ $company->bank->bank == 'MAYAPADA' ? 'selected' : '' }}>
                                                                Bank Mayapada International
                                                            </option>
                                                            <option value="BRI_SYR" {{ $company->bank->bank == 'BRI_SYR' ? 'selected' : '' }}>
                                                                Bank Syariah BRI
                                                            </option>
                                                            <option value="TABUNGAN_PENSIUNAN_NASIONAL" {{ $company->bank->bank == 'TABUNGAN_PENSIUNAN_NASIONAL' ? 'selected' : '' }}>
                                                                Bank Tabungan Pensiunan Nasional
                                                            </option>
                                                            <option value="VICTORIA_INTERNASIONAL" {{ $company->bank->bank == 'VICTORIA_INTERNASIONAL' ? 'selected' : '' }}>
                                                                Bank Victoria Internasional
                                                            </option>
                                                            <option value="BALI" {{ $company->bank->bank == 'BALI' ? 'selected' : '' }}>
                                                                BPD Bali
                                                            </option>
                                                            <option value="JAWA_TENGAH" {{ $company->bank->bank == 'JAWA_TENGAH' ? 'selected' : '' }}>
                                                                BPD Jawa Tengah
                                                            </option>
                                                            <option value="KALIMANTAN_SELATAN" {{ $company->bank->bank == 'KALIMANTAN_SELATAN' ? 'selected' : '' }}>
                                                                BPD Kalimantan Selatan
                                                            </option>
                                                            <option value="MAYBANK_SYR" {{ $company->bank->bank == 'MAYBANK_SYR' ? 'selected' : '' }}>
                                                                Bank Maybank Syariah Indonesia
                                                            </option>
                                                            <option value="SAHABAT_SAMPOERNA" {{ $company->bank->bank == 'SAHABAT_SAMPOERNA' ? 'selected' : '' }}>
                                                                Bank Sahabat Sampoerna
                                                            </option>
                                                            <option value="KALIMANTAN_SELATAN_UUS" {{ $company->bank->bank == 'KALIMANTAN_SELATAN_UUS' ? 'selected' : '' }}>
                                                                BPD Kalimantan Selatan UUS
                                                            </option>
                                                            <option value="KALIMANTAN_TENGAH" {{ $company->bank->bank == 'KALIMANTAN_TENGAH' ? 'selected' : '' }}>
                                                                BPD Kalimantan Tengah
                                                            </option>
                                                            <option value="MUAMALAT" {{ $company->bank->bank == 'MUAMALAT' ? 'selected' : '' }}>
                                                                Bank Muamalat Indonesia
                                                            </option>
                                                            <option value="BUKOPIN_SYR" {{ $company->bank->bank == 'BUKOPIN_SYR' ? 'selected' : '' }}>
                                                                Bank Syariah Bukopin
                                                            </option>
                                                            <option value="NUSANTARA_PARAHYANGAN" {{ $company->bank->bank == 'NUSANTARA_PARAHYANGAN' ? 'selected' : '' }}>
                                                                Bank Nusantara Parahyangan
                                                            </option>
                                                            <option value="JAMBI_UUS" {{ $company->bank->bank == 'JAMBI_UUS' ? 'selected' : '' }}>
                                                                BPD Jambi UUS
                                                            </option>
                                                            <option value="JAWA_TIMUR" {{ $company->bank->bank == 'JAWA_TIMUR' ? 'selected' : '' }}>
                                                                BPD Jawa Timur
                                                            </option>
                                                            <option value="MEGA" {{ $company->bank->bank == 'MEGA' ? 'selected' : '' }}>
                                                                Bank Mega
                                                            </option>
                                                            <option value="DAERAH_ISTIMEWA_UUS" {{ $company->bank->bank == 'DAERAH_ISTIMEWA_UUS' ? 'selected' : '' }}>
                                                                BPD Daerah Istimewa Yogyakarta (DIY) UUS
                                                            </option>
                                                            <option value="KALIMANTAN_TIMUR" {{ $company->bank->bank == 'KALIMANTAN_TIMUR' ? 'selected' : '' }}>
                                                                BPD Kalimantan Timur
                                                            </option>
                                                            <option value="MULTI_ARTA_SENTOSA" {{ $company->bank->bank == 'MULTI_ARTA_SENTOSA' ? 'selected' : '' }}>
                                                                Bank Multi Arta Sentosa
                                                            </option>
                                                            <option value="OCBC" {{ $company->bank->bank == 'OCBC' ? 'selected' : '' }}>
                                                                Bank OCBC NISP
                                                            </option>
                                                            <option value="NATIONALNOBU" {{ $company->bank->bank == 'NATIONALNOBU' ? 'selected' : '' }}>
                                                                Bank Nationalnobu
                                                            </option>
                                                            <option value="BOC" {{ $company->bank->bank == 'BOC' ? 'selected' : '' }}>
                                                                Bank of China (BOC)
                                                            </option>
                                                            <option value="BTN" {{ $company->bank->bank == 'BTN' ? 'selected' : '' }}>
                                                                Bank Tabungan Negara (BTN)
                                                            </option>
                                                            <option value="BENGKULU" {{ $company->bank->bank == 'BENGKULU' ? 'selected' : '' }}>
                                                                BPD Bengkulu
                                                            </option>
                                                            <option value="RESONA" {{ $company->bank->bank == 'RESONA' ? 'selected' : '' }}>
                                                                Bank Resona Perdania
                                                            </option>
                                                            <option value="MANDIRI_SYR" {{ $company->bank->bank == 'MANDIRI_SYR' ? 'selected' : '' }}>
                                                                Bank Syariah Mandiri
                                                            </option>
                                                            <option value="WOORI" {{ $company->bank->bank == 'WOORI' ? 'selected' : '' }}>
                                                                Bank Woori Indonesia
                                                            </option>
                                                            <option value="YUDHA_BHAKTI" {{ $company->bank->bank == 'YUDHA_BHAKTI' ? 'selected' : '' }}>
                                                                Bank Yudha Bhakti
                                                            </option>
                                                            <option value="ACEH" {{ $company->bank->bank == 'ACEH' ? 'selected' : '' }}>
                                                                BPD Aceh
                                                            </option>
                                                            <option value="MAYORA" {{ $company->bank->bank == 'MAYORA' ? 'selected' : '' }}>
                                                                Bank Mayora
                                                            </option>
                                                            <option value="BAML" {{ $company->bank->bank == 'BAML' ? 'selected' : '' }}>
                                                                Bank of America Merill-Lynch
                                                            </option>
                                                            <option value="PERMATA_UUS" {{ $company->bank->bank == 'PERMATA_UUS' ? 'selected' : '' }}>
                                                                Bank Permata UUS
                                                            </option>
                                                            <option value="KESEJAHTERAAN_EKONOMI" {{ $company->bank->bank == 'KESEJAHTERAAN_EKONOMI' ? 'selected' : '' }}>
                                                                Bank Kesejahteraan Ekonomi
                                                            </option>
                                                            <option value="MESTIKA_DHARMA" {{ $company->bank->bank == 'MESTIKA_DHARMA' ? 'selected' : '' }}>
                                                                Bank Mestika Dharma
                                                            </option>
                                                            <option value="OCBC_UUS" {{ $company->bank->bank == 'OCBC_UUS' ? 'selected' : '' }}>
                                                                Bank OCBC NISP UUS
                                                            </option>
                                                            <option value="RABOBANK" {{ $company->bank->bank == 'RABOBANK' ? 'selected' : '' }}>
                                                                Bank Rabobank International Indonesia
                                                            </option>
                                                            <option value="ROYAL" {{ $company->bank->bank == 'ROYAL' ? 'selected' : '' }}>
                                                                Bank Royal Indonesia
                                                            </option>
                                                            <option value="MITSUI" {{ $company->bank->bank == 'MITSUI' ? 'selected' : '' }}>
                                                                Bank Sumitomo Mitsui Indonesia
                                                            </option>
                                                            <option value="UOB" {{ $company->bank->bank == 'UOB' ? 'selected' : '' }}>
                                                                Bank UOB Indonesia
                                                            </option>
                                                            <option value="INDIA" {{ $company->bank->bank == 'INDIA' ? 'selected' : '' }}>
                                                                Bank of India Indonesia
                                                            </option>
                                                            <option value="SBI_INDONESIA" {{ $company->bank->bank == 'SBI_INDONESIA' ? 'selected' : '' }}>
                                                                Bank SBI Indonesia
                                                            </option>
                                                            <option value="MEGA_SYR" {{ $company->bank->bank == 'MEGA_SYR' ? 'selected' : '' }}>
                                                                Bank Syariah Mega
                                                            </option>
                                                            <option value="JAMBI" {{ $company->bank->bank == 'JAMBI' ? 'selected' : '' }}>
                                                                BPD Jambi
                                                            </option>
                                                            <option value="JAWA_TIMUR_UUS" {{ $company->bank->bank == 'JAWA_TIMUR_UUS' ? 'selected' : '' }}>
                                                                BPD Jawa Timur UUS
                                                            </option>
                                                            <option value="MIZUHO" {{ $company->bank->bank == 'MIZUHO' ? 'selected' : '' }}>
                                                                Bank Mizuho Indonesia
                                                            </option>
                                                            <option value="MNC_INTERNASIONAL" {{ $company->bank->bank == 'MNC_INTERNASIONAL' ? 'selected' : '' }}>
                                                                Bank MNC Internasional
                                                            </option>
                                                            <option value="TOKYO" {{ $company->bank->bank == 'TOKYO' ? 'selected' : '' }}>
                                                                Bank of Tokyo Mitsubishi UFJ
                                                            </option>
                                                            <option value="VICTORIA_SYR" {{ $company->bank->bank == 'VICTORIA_SYR' ? 'selected' : '' }}>
                                                                Bank Victoria Syariah
                                                            </option>
                                                            <option value="LAMPUNG" {{ $company->bank->bank == 'LAMPUNG' ? 'selected' : '' }}>
                                                                BPD Lampung
                                                            </option>
                                                            <option value="MALUKU" {{ $company->bank->bank == 'MALUKU' ? 'selected' : '' }}>
                                                                BPD Maluku
                                                            </option>
                                                            <option value="SUMSEL_DAN_BABEL_UUS" {{ $company->bank->bank == 'SUMSEL_DAN_BABEL_UUS' ? 'selected' : '' }}>
                                                                BPD Sumsel Dan Babel UUS
                                                            </option>
                                                            <option value="MAYBANK" {{ $company->bank->bank == 'MAYBANK' ? 'selected' : '' }}>
                                                                Bank Maybank
                                                            </option>
                                                            <option value="JPMORGAN" {{ $company->bank->bank == 'JPMORGAN' ? 'selected' : '' }}>
                                                                JP Morgan Chase Bank
                                                            </option>
                                                            <option value="SULSELBAR_UUS" {{ $company->bank->bank == 'SULSELBAR_UUS' ? 'selected' : '' }}>
                                                                BPD Sulselbar UUS
                                                            </option>
                                                            <option value="SULAWESI_TENGGARA" {{ $company->bank->bank == 'SULAWESI_TENGGARA' ? 'selected' : '' }}>
                                                                BPD Sulawesi Tenggara
                                                            </option>
                                                            <option value="NUSA_TENGGARA_BARAT" {{ $company->bank->bank == 'NUSA_TENGGARA_BARAT' ? 'selected' : '' }}>
                                                                BPD Nusa Tenggara Barat
                                                            </option>
                                                            <option value="RIAU_DAN_KEPRI_UUS" {{ $company->bank->bank == 'RIAU_DAN_KEPRI_UUS' ? 'selected' : '' }}>
                                                                BPD Riau Dan Kepri UUS
                                                            </option>
                                                            <option value="SULUT" {{ $company->bank->bank == 'SULUT' ? 'selected' : '' }}>
                                                                BPD Sulut
                                                            </option>
                                                            <option value="SUMUT" {{ $company->bank->bank == 'SUMUT' ? 'selected' : '' }}>
                                                                BPD Sumut
                                                            </option>
                                                            <option value="DEUTSCHE" {{ $company->bank->bank == 'DEUTSCHE' ? 'selected' : '' }}>
                                                                Deutsche Bank
                                                            </option>
                                                            <option value="STANDARD_CHARTERED" {{ $company->bank->bank == 'STANDARD_CHARTERED' ? 'selected' : '' }}>
                                                                Standard Charted Bank
                                                            </option>
                                                            <option value="BRI" {{ $company->bank->bank == 'BRI' ? 'selected' : '' }}>
                                                                Bank Rakyat Indonesia (BRI)
                                                            </option>
                                                            <option value="HSBC" {{ $company->bank->bank == 'HSBC' ? 'selected' : '' }}>
                                                                HSBC Indonesia (formerly Bank Ekonomi Raharja)
                                                            </option>
                                                            <option value="SULSELBAR" {{ $company->bank->bank == 'SULSELBAR' ? 'selected' : '' }}>
                                                                BPD Sulselbar
                                                            </option>
                                                            <option value="SUMATERA_BARAT_UUS" {{ $company->bank->bank == 'SUMATERA_BARAT_UUS' ? 'selected' : '' }}>
                                                                BPD Sumatera Barat UUS
                                                            </option>
                                                            <option value="NUSA_TENGGARA_BARAT_UUS" {{ $company->bank->bank == 'NUSA_TENGGARA_BARAT_UUS' ? 'selected' : '' }}>
                                                                BPD Nusa Tenggara Barat UUS
                                                            </option>
                                                            <option value="HSBC_UUS" {{ $company->bank->bank == 'HSBC_UUS' ? 'selected' : '' }}>
                                                                Hongkong and Shanghai Bank Corporation (HSBC) UUS
                                                            </option>
                                                            <option value="PAPUA" {{ $company->bank->bank == 'PAPUA' ? 'selected' : '' }}>
                                                                BPD Papua
                                                            </option>
                                                            <option value="SULAWESI" {{ $company->bank->bank == 'SULAWESI' ? 'selected' : '' }}>
                                                                BPD Sulawesi Tengah
                                                            </option>
                                                            <option value="SUMATERA_BARAT" {{ $company->bank->bank == 'SUMATERA_BARAT' ? 'selected' : '' }}>
                                                                BPD Sumatera Barat
                                                            </option>
                                                            <option value="SUMUT_UUS" {{ $company->bank->bank == 'SUMUT_UUS' ? 'selected' : '' }}>
                                                                BPD Sumut UUS
                                                            </option>
                                                            <option value="BNI" {{ $company->bank->bank == 'BNI' ? 'selected' : '' }}>
                                                                Bank Negara Indonesia (BNI)
                                                            </option>
                                                            <option value="PRIMA_MASTER" {{ $company->bank->bank == 'PRIMA_MASTER' ? 'selected' : '' }}>
                                                                Prima Master Bank
                                                            </option>
                                                            <option value="MITRA_NIAGA" {{ $company->bank->bank == 'MITRA_NIAGA' ? 'selected' : '' }}>
                                                                Bank Mitra Niaga
                                                            </option>
                                                            <option value="NUSA_TENGGARA_TIMUR" {{ $company->bank->bank == 'NUSA_TENGGARA_TIMUR' ? 'selected' : '' }}>
                                                                BPD Nusa Tenggara Timur
                                                            </option>
                                                            <option value="SUMSEL_DAN_BABEL" {{ $company->bank->bank == 'SUMSEL_DAN_BABEL' ? 'selected' : '' }}>
                                                                BPD Sumsel Dan Babel
                                                            </option>
                                                            <option value="RBS" {{ $company->bank->bank == 'RBS' ? 'selected' : '' }}>
                                                                Royal Bank of Scotland (RBS)
                                                            </option>
                                                            <option value="ARTA_NIAGA_KENCANA" {{ $company->bank->bank == 'ARTA_NIAGA_KENCANA' ? 'selected' : '' }}>
                                                                Bank Arta Niaga Kencana
                                                            </option>
                                                            <option value="CITIBANK" {{ $company->bank->bank == 'CITIBANK' ? 'selected' : '' }}>
                                                                Citibank
                                                            </option>
                                                            <option value="RIAU_DAN_KEPRI" {{ $company->bank->bank == 'RIAU_DAN_KEPRI' ? 'selected' : '' }}>
                                                                BPD Riau Dan Kepri
                                                            </option>
                                                            <option value="CENTRATAMA" {{ $company->bank->bank == 'CENTRATAMA' ? 'selected' : '' }}>
                                                                Centratama Nasional Bank
                                                            </option>
                                                            <option value="OKE" {{ $company->bank->bank == 'OKE' ? 'selected' : '' }}>
                                                                Bank Oke Indonesia (formerly Bank Andara)
                                                            </option>
                                                            <option value="MANDIRI_ECASH" {{ $company->bank->bank == 'MANDIRI_ECASH' ? 'selected' : '' }}>
                                                                Mandiri E-Cash
                                                            </option>
                                                            <option value="AMAR" {{ $company->bank->bank == 'AMAR' ? 'selected' : '' }}>
                                                                Bank Amar Indonesia (formerly Anglomas International
                                                                Bank)
                                                            </option>
                                                            <option value="GOPAY" {{ $company->bank->bank == 'GOPAY' ? 'selected' : '' }}>
                                                                GoPay
                                                            </option>
                                                            <option value="SINARMAS_UUS" {{ $company->bank->bank == 'SINARMAS_UUS' ? 'selected' : '' }}>
                                                                Bank Sinarmas UUS
                                                            </option>
                                                            <option value="OVO" {{ $company->bank->bank == 'OVO' ? 'selected' : '' }}>
                                                                OVO
                                                            </option>
                                                            <option value="EXIMBANK" {{ $company->bank->bank == 'EXIMBANK' ? 'selected' : '' }}>
                                                                Indonesia Eximbank (formerly Bank Ekspor Indonesia)
                                                            </option>
                                                            <option value="JTRUST" {{ $company->bank->bank == 'JTRUST' ? 'selected' : '' }}>
                                                                Bank JTrust Indonesia (formerly Bank Mutiara)
                                                            </option>
                                                            <option value="WOORI_SAUDARA" {{ $company->bank->bank == 'WOORI_SAUDARA' ? 'selected' : '' }}>
                                                                Bank Woori Saudara Indonesia 1906 (formerly Bank
                                                                Himpunan Saudara and Bank Woori Indonesia)
                                                            </option>
                                                            <option value="BTPN_SYARIAH" {{ $company->bank->bank == 'BTPN_SYARIAH' ? 'selected' : '' }}>
                                                                BTPN Syariah (formerly BTPN UUS and Bank Sahabat Purba
                                                                Danarta)
                                                            </option>
                                                            <option value="SHINHAN" {{ $company->bank->bank == 'SHINHAN' ? 'selected' : '' }}>
                                                                Bank Shinhan Indonesia (formerly Bank Metro Express)
                                                            </option>
                                                            <option value="BANTEN" {{ $company->bank->bank == 'BANTEN' ? 'selected' : '' }}>
                                                                BPD Banten (formerly Bank Pundi Indonesia)
                                                            </option>
                                                            <option value="CCB" {{ $company->bank->bank == 'CCB' ? 'selected' : '' }}>
                                                                China Construction Bank Indonesia (formerly Bank Antar
                                                                Daerah and Bank Windu Kentjana International)
                                                            </option>
                                                            <option value="MANDIRI_TASPEN" {{ $company->bank->bank == 'MANDIRI_TASPEN' ? 'selected' : '' }}>
                                                                Mandiri Taspen Pos (formerly Bank Sinar Harapan Bali)
                                                            </option>
                                                            <option value="QNB_INDONESIA" {{ $company->bank->bank == 'QNB_INDONESIA' ? 'selected' : '' }}>
                                                                Bank QNB Indonesia (formerly Bank QNB Kesawan)
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group m-form__group">
                                                        {!! Form::label('bank_account_name','Bank Account Name') !!}
                                                        {!! Form::text('bank_account_name',null,['class'=>'form-control m-input']) !!}
                                                    </div>
                                                    <div class="form-group m-form__group">
                                                        {!! Form::label('bank_account_number','Bank Account Number') !!}
                                                        {!! Form::text('bank_account_number',null,['class'=>'form-control m-input']) !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <img class="img-fluid"
                                                         src="{{asset('uploads/bank_document/'.$company->bank->bank_account_document)}}"
                                                         alt="">
                                                </div>
                                                {{--                                                <div class="col-12 text-right mt-3 text-center">--}}
                                                {{--                                                    <button type="button" class="btn btn-brand btn-do-submit">Save--}}
                                                {{--                                                    </button>--}}
                                                {{--                                                </div>--}}
                                            </div>
                                            {!! Form::close() !!}
                                        @else

                                        @endif
                                    </div>
                                </div>
                                <div class="tab-pane" id="m_tabs_6_4" role="tabpanel">
                                    <div class="row">
                                        <div class="col-12 text-right">
                                            <button class="btn btn-outline-brand" type="button" id="btn-add-associaton">
                                                <i
                                                        class="fa fa-plus-circle"></i> Add Association
                                            </button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        @foreach($associations as $association)
                                            <div class="col-md-4">
                                                <div class="m-portlet">
                                                    <div class="m-portlet__head">
                                                        <div class="m-portlet__head-caption">
                                                            <div class="m-portlet__head-title">
                                                                <span class="m-portlet__head-icon">
                                                                    <img src="{{$association->association_logo}}" alt=""
                                                                         style="height: 50px;width: auto">
                                                                </span>
                                                                <h3 class="m-portlet__head-text">
                                                                    {{$association->association_name}}
                                                                </h3>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="m-portlet__body">
                                                        Membership ID :
                                                        <strong>{{$association->pivot->membership_id}}</strong>
                                                    </div>
                                                    <div class="m-portlet__foot">
                                                        <div class="row align-items-center">
                                                            <div class="col-lg-12 m--valign-middle">
                                                                <button type="button"
                                                                        data-membership="{{$association->pivot->membership_id}}"
                                                                        data-id="{{$association->id}}"
                                                                        data-name="{{$association->association_name}}"
                                                                        class="btn btn-block btn-danger btn-delete">
                                                                    Delete
                                                                </button>
                                                            </div>
                                                            {{--                                                            <div class="col-lg-6 m--align-right">--}}
                                                            {{--                                                                <button type="button"--}}
                                                            {{--                                                                        data-membership="{{$association->pivot->membership_id}}"--}}
                                                            {{--                                                                        data-id="{{$association->id}}"--}}
                                                            {{--                                                                        data-name="{{$association->association_name}}"--}}
                                                            {{--                                                                        class="btn btn-block btn-brand btn-edit">Edit--}}
                                                            {{--                                                                </button>--}}
                                                            {{--                                                            </div>--}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="tab-pane" id="m_tabs_6_5" role="tabpanel">
                                    @if($company->google_review_widget)
                                        {!! Form::model($company->google_review_widget,['url'=>route('admin:providers.update-google-widget',['id'=>$company->id_company])]) !!}
                                    @else
                                        {!! Form::open(['url'=>route('admin:providers.update-google-widget',['id'=>$company->id_company])]) !!}
                                    @endif
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-6">
                                                    <input type="text" name="lat" value="{{$company->lat}}" readonly id="lat" class="form-control">
                                                </div>
                                                <div class="col-6">
                                                    <input type="text" name="long" value="{{$company->long}}" readonly id="long" class="form-control">
                                                </div>
                                            </div>


                                        </div>
                                        <div class="col-12 mb-3">
                                            <div id="map" style="height: 600px">

                                            </div>
                                        </div>

                                        <div class="col-12">

                                            {!! Form::label('widget_script') !!}
                                            {!! Form::textarea('widget_script',null,['class'=>'form-control m-input']) !!}

                                        </div>
                                        <div class="col-12 mt-3 text-center">
                                            <button class="btn btn-sm btn-primary btn-do-submit" type="button">Submit</button>
                                        </div>
                                    </div>
                                    {!! Form::close() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-add" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            {!! Form::open(['url'=>route('admin:providers.save-association',['id'=>$company->id_company]),'style'=>'width:100%','id'=>'form-add','files'=>true]) !!}
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Add new Association</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group m-form__group">
                        {!! Form::label('association_name','Association Name') !!}
                        {!! Form::select('association_id',\App\Models\Association::where('status',1)->whereDoesntHave('companies', function ($c) use($company){
                            $c->where('tbl_company.id_company',$company->id_company);
                        })->pluck('association_name','id'),null,['class'=>' form-control m-input','id'=>'association_name','autocomplete'=>'off']) !!}
                    </div>
                    <div class="form-group m-form__group">
                        {!! Form::label('membership_id','Membership ID') !!}
                        {!! Form::text('membership_id',null,['class'=>'form-control m-input','id'=>'association_name','autocomplete'=>'off']) !!}
                    </div>

                </div>


                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary btn-do-submit" type="button" id="btn-do-delete">Save</button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>

    <div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            {!! Form::open(['url'=>route('admin:providers.update-association',['id'=>$company->id_company]),'style'=>'width:100%','id'=>'form-add','files'=>true]) !!}
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Edit Membership ID for <span class="name"></span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group m-form__group">
                        {!! Form::hidden('association_id',null,['class'=>'form-control m-input']) !!}
                        {!! Form::label('membership_id','Membership ID') !!}
                        {!! Form::text('membership_id',null,['class'=>'form-control m-input','id'=>'association_name','autocomplete'=>'off']) !!}
                    </div>

                </div>


                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary btn-do-submit" type="button" id="btn-do-delete">Save</button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
    <div class="modal fade" id="modal-delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            {!! Form::open(['url'=>route('admin:providers.delete-association',['id'=>$company->id_company]),'style'=>'width:100%','id'=>'form-add','files'=>true]) !!}
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Delete from Association: <span
                                class="name"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group m-form__group">
                        {!! Form::hidden('association_id',null,['class'=>'form-control m-input']) !!}
                        Are You Sure?
                    </div>

                </div>


                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary btn-do-submit" type="button" id="btn-do-delete">Save</button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@stop

@section('scripts')
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD3rfV14WCZO6iNH5iX37OltWufEx7AK4k&libraries=places&callback=initMap"></script>
    <script>
        var marker;
        var map;
        var longitude = isNaN(parseFloat({{$company->long}}))?0:parseFloat({{$company->long}});
        var latitude = isNaN(parseFloat({{$company->lat}}))?0:parseFloat({{$company->lat}});
        function initMap() {
            map = new google.maps.Map(document.getElementById('map'), {
                zoom: 20,
                center: {lat: latitude, lng: longitude},
                draggable: false, zoomControl: false, scrollwheel: false, disableDoubleClickZoom: true
            });

            marker = new google.maps.Marker({
                map: map,
                animation: google.maps.Animation.DROP,
                position: {lat: latitude, lng: longitude}
            });

            // Create the search box and link it to the UI element.
            var input = document.getElementById('pac-input');
            var searchBox = new google.maps.places.SearchBox(input);
            map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

            // Bias the SearchBox results towards current map's viewport.
            map.addListener('bounds_changed', function () {
                searchBox.setBounds(map.getBounds());
            });


            searchBox.addListener('places_changed', function () {
                var places = searchBox.getPlaces();

                if (places.length == 0)
                    return;

                var bounds = new google.maps.LatLngBounds();
                places.forEach(function (place) {

                    if (!place.geometry)
                        return;

                    if (place.geometry.viewport)
                        bounds.union(place.geometry.viewport);
                    else
                        bounds.extend(place.geometry.location);

                });

                map.fitBounds(bounds);
                placeMarker(map.center);
                setLongLat();

            });

            google.maps.event.addListener(map, 'click', function (event) {
                placeMarker(event.latLng);
                handleEvent(event)
                // setLongLat();
            });

            function placeMarker(location) {

                marker.setPosition(location);
                //map.setCenter(location);
                // setLongLat();
            }

            setLongLat();
        }
        function handleEvent(event) {
            document.getElementById('lat').value = event.latLng.lat();
            document.getElementById('long').value = event.latLng.lng();
        }
        function setLongLat() {
            if (map && map.getCenter()){
                document.getElementById('long').value = map.getCenter().lng();
                document.getElementById('lat').value = map.getCenter().lat();
            }

        }

        function toggleBounce() {
            if (marker.getAnimation() !== null) {
                marker.setAnimation(null);
            } else {
                marker.setAnimation(google.maps.Animation.BOUNCE);
            }
        }

        function changeToMap(el){
            var request = {
                query: el.find('option:selected').text(),
                fields: ['name', 'geometry'],
            };

            var service = new google.maps.places.PlacesService(map);

            service.findPlaceFromQuery(request, function(results, status) {
                if (status === google.maps.places.PlacesServiceStatus.OK) {
                    map.setCenter(results[0].geometry.location);
                    marker.setPosition(results[0].geometry.location);
                    setLongLat();
                }
            });
        }
        $('.summernote').summernote({height: 150});

        $(document).on('click', '#btn-add-associaton', function () {
            let modal = $('#modal-add');
            modal.modal();
        });
        $(document).on('click', '.btn-edit', function () {
            let modal = $('#modal-edit');
            modal.find('.name').text($(this).data('name'))
            modal.find('input[name=association_id]').val($(this).data('id'))
            modal.find('input[name=membership_id]').val($(this).data('membership'))
            modal.modal();
        });
        $(document).on('click', '.btn-delete', function () {
            let modal = $('#modal-delete');
            modal.find('.name').text($(this).data('name'))
            modal.find('input[name=association_id]').val($(this).data('id'))
            modal.modal();
        });
        $(document).on('click', '.btn-do-submit', function () {
            $(document).find('label.error').remove();
            loadingStart();
            let t = $(this);
            let form = t.closest('form');
            let fD = new FormData();
            $.each(form.find('.form-control.m-input'), function (i, e) {
                if ($(e).attr('type') === 'file') {
                    if ($(e)[0].files.length > 0) {
                        fD.append($(e).attr('name'), $(e)[0].files[0]);
                    }
                } else {
                    fD.append($(e).attr('name'), $(e).val())
                }

            });
            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                dataType: 'json',
                data: fD,
                contentType: false,
                processData: false,
                success: function (data) {
                    loadingFinish();
                    toastr.success(data.message, "Yey");
                    $('.modal').modal('hide');
                    setTimeout(function () {
                        window.location = data.result.redirect;
                    }, 1000);
                },
                error: function (e) {
                    if (e.status !== undefined && e.status === 422) {
                        let errors = e.responseJSON.errors;
                        $.each(errors, function (i, el) {
                            t.closest('form').find('input[name=' + i + ']').closest('.form-group').append('<label class="error">' + el[0] + '</label>')
                            t.closest('form').find('textarea[name=' + i + ']').closest('.form-group').append('<label class="error">' + el[0] + '</label>')
                            t.closest('form').find('select[name=' + i + ']').closest('.form-group').append('<label class="error">' + el[0] + '</label>')
                        })

                    }
                    loadingFinish();
                    toastr.error(e.responseJSON.message, '{{__('general.whoops')}}')
                }
            })

        })
    </script>
@stop
