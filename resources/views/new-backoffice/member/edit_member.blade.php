@extends('new-backoffice.header')

@section('content')
  <h3 class="mb-3 mt-3">Edit Penyedia Wisata</h3>
  <div class="card">
    <div class="card-body">
      <ul class="nav nav-tabs nav-tabs-highlight nav-justified d-block d-md-flex">
        <li class="nav-item"><a href="#tab1-product" class="nav-link active" data-toggle="tab">Profil Perusahaan</a></li>
        <li class="nav-item"><a href="#tab2-product" class="nav-link" data-toggle="tab">Login Anggota</a></li>
        <li class="nav-item"><a href="#tab3-product" class="nav-link" data-toggle="tab">Akun Bank</a></li>
{{--        <li class="nav-item"><a href="#tab4-product" class="nav-link" data-toggle="tab">Asosiasi</a></li>--}}
      </ul>

      <div class="tab-content">
        <div class="tab-pane fade show active" id="tab1-product">
          <form action="{{ route('admin:providers.update-company',['id'=>$company->id_company]) }}" method="post">
            {{ csrf_field() }}
            <div class="row">
              <div class="col-md-6">
                <div class="form-group form-group-float">
                  <label for="name" class="form-group-float-label animate is-visible">Domain</label>
                  <input type="text" class="form-control" name="domain_memoria" placeholder="Domain" value="{{ old('domain_memoria', $company->domain_memoria) }}">
                </div>
                <div class="form-group form-group-float">
                  <label for="company_name" class="form-group-float-label animate is-visible">Nama Anggota</label>
                  <input type="text" class="form-control" name="company_name" placeholder="Nama Anggota" value="{{ old('company_name', $company->company_name) }}">
                </div>
                <div class="form-group form-group-float">
                  <label for="email_company" class="form-group-float-label animate is-visible">Email Anggota</label>
                  <input type="email" class="form-control" name="email_company" placeholder="Email Anggota" value="{{ old('email_company', $company->email_company) }}">
                </div>
                <div class="form-group form-group-float">
                  <label for="phone_company" class="form-group-float-label animate is-visible">Telepon Anggota</label>
                  <input type="tel" class="form-control" name="phone_company" placeholder="Telepon Anggota" value="{{ old('phone_company', $company->phone_company) }}">
                </div>
                <div class="form-group form-group-float">
                  <label for="ownership_status" class="form-group-float-label animate is-visible">Status Kepemilikan</label>
                  @php
                    $ownership_status = [
                      'personal'  => 'Pribadi',
                      'corporate' => 'Korporasi'
                    ];
                  @endphp
                  <select name="ownership_status" id="" class="form-control">
                    @foreach ($ownership_status as $key => $value)
                      <option value="{{ $key }}" {{ old('ownership_status', $company->ownership_status) == $key ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="form-group form-group-float">
                  <label for="verified_provider" class="form-group-float-label animate is-visible">Anggota Terverifikasi</label>
                  @php
                    $status = [
                      0 => 'Tidak',
                      1 => 'Ya'
                    ];
                  @endphp
                  <select name="verified_provider" id="" class="form-control">
                    @foreach ($status as $key => $value)
                      <option value="{{ $key }}" {{ old('verified_provider', $company->verified_provider) == $key ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group form-group-float">
                  <label for="about_company" class="form-group-float-label animate is-visible">Tentang Anggota</label>
                  <textarea type="text" class="form-control summernote" name="about_company" placeholder="Tentang Anggota">{{ old('about_company', $company->about_company) }}</textarea>
                </div>
                <input type="hidden" name="is_klhk" value="1" />
                <div class="form-group form-group-float">
                  <label for="status" class="form-group-float-label animate is-visible">Status Anggota</label>
                  @php
                    $status_provider = [
                      0 => 'Banned',
                      1 => 'Aktif'
                    ];
                  @endphp
                  <select name="status" id="" class="form-control">
                    @foreach ($status_provider as $key => $value)
                      <option value="{{ $key }}" {{ old('status', $company->status) == $key ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
            <div class="col-12 text-center">
              <button class="btn btn-success" type="submit"><i class="icon-floppy-disk"></i> &nbsp;Simpan</button>
            </div>
          </form>
        </div>
        <div class="tab-pane fade" id="tab2-product">
          <form action="" method="post">
            <div class="row">
              @foreach($users as $user)
              <div class="col-md-6">
                <div class="form-group form-group-float">
                  <label for="first_name" class="form-group-float-label animate is-visible">Nama Pertama</label>
                  <input type="text" class="form-control" name="first_name" placeholder="Nama Pertama" value="{{ old('first_name', $user->first_name) }}">
                  {!! Form::hidden('id_user_agen', $user->id_user_agen,['class'=>'form-control m-input']) !!}
                </div>
                <div class="form-group form-group-float">
                  <label for="last_name" class="form-group-float-label animate is-visible">Nama Terakhir</label>
                  <input type="text" class="form-control" name="last_name" placeholder="Nama Terakhir"  value="{{ old('last_name', $user->last_name) }}">
                </div>
                <div class="form-group form-group-float">
                  <label for="email" class="form-group-float-label animate is-visible">Email</label>
                  <input type="text" class="form-control" name="email" placeholder="Email" value="{{ old('email', $user->email) }}">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group form-group-float">
                  <label for="password" class="form-group-float-label animate is-visible">Password</label>
                  <input type="password" class="form-control" name="password" placeholder="Password">
                </div>
                <div class="form-group form-group-float">
                  <label for="status" class="form-group-float-label animate is-visible">Status</label>
                  @php
                    $status_provider = [
                      1 => 'Aktif',
                      0 => 'Tidak Aktif'
                    ];
                  @endphp
                  <select name="status" id="" class="form-control">
                    @foreach ($status as $key => $value)
                      <option value="{{ $key }}" {{ old('status', $user->status) == $key ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              @endforeach
              <div class="col-12 text-center">
                <button class="btn btn-success" type="submit"><i class="icon-floppy-disk"></i> &nbsp;Simpan</button>
              </div>
            </div>
          </form>
        </div>
        <div class="tab-pane fade" id="tab3-product">
          @if($company->bank)
            {!! Form::model($company->bank,['style'=>'width:100%','url'=>route('admin:providers.update-bank',['id'=>$company->id_company])]) !!}
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group m-form__group">
                        {!! Form::label('bank','Pilih Bank') !!}
                        <select name="bank" class="form-control select2" placeholder="Select Bank" readonly="">
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
                                Bank Amar Indonesia (formerly Anglomas International Bank)
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
                                Bank Woori Saudara Indonesia 1906 (formerly Bank Himpunan Saudara and Bank Woori Indonesia)
                            </option>
                            <option value="BTPN_SYARIAH" {{ $company->bank->bank == 'BTPN_SYARIAH' ? 'selected' : '' }}>
                                BTPN Syariah (formerly BTPN UUS and Bank Sahabat Purba Danarta)
                            </option>
                            <option value="SHINHAN" {{ $company->bank->bank == 'SHINHAN' ? 'selected' : '' }}>
                                Bank Shinhan Indonesia (formerly Bank Metro Express)
                            </option>
                            <option value="BANTEN" {{ $company->bank->bank == 'BANTEN' ? 'selected' : '' }}>
                                BPD Banten (formerly Bank Pundi Indonesia)
                            </option>
                            <option value="CCB" {{ $company->bank->bank == 'CCB' ? 'selected' : '' }}>
                                China Construction Bank Indonesia (formerly Bank Antar Daerah and Bank Windu Kentjana International)
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
                        {!! Form::label('bank_account_name','Nama Rekening') !!}
                        {!! Form::text('bank_account_name',null,['class'=>'form-control m-input','readonly']) !!}
                    </div>
                    <div class="form-group m-form__group">
                        {!! Form::label('bank_account_number','No Rekening') !!}
                        {!! Form::text('bank_account_number',null,['class'=>'form-control m-input','readonly']) !!}
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
        <div class="tab-pane fade" id="tab4-product">
          <div class="row">
            <div class="col-12">
              <button class="btn btn-outline-success float-right" type="button" data-toggle="modal" data-target=".add-association"><i class="icon-plus-circle2"></i> Tambah Asosiasi</button>
            </div>
          </div>
          @include('new-backoffice.list.modal_add_association')
          <div class="row">
            @foreach($associations as $association)
            <div class="col-md-3">
              <div class="card">
                <img src="{{$association->association_logo}}" alt="{{$association->association_name}}" height="100">
                <div class="card-body">
                  <span>{{$association->association_name}}</span>
                </div>
                <div class="card-footer">
                  <div class="form-group">
                    <button type="button"
                            data-membership="{{$association->pivot->membership_id}}"
                            data-id="{{$association->id}}"
                            data-name="{{$association->association_name}}"
                            class="btn btn-block btn-danger btn-delete">
                        Hapus
                    </button>
                  </div>
                </div>
              </div>
            </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('additionalScript')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote-bs4.css" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote-bs4.js"></script>
  <script>
    window.$ = jQuery;

    $('.summernote').summernote({
      disableResizeEditor: true,
      height: '300px'
    });

    $('.tab-pane form').on('submit', function (e) {
      e.preventDefault();
      $(document).find('label.error').remove();

      let form = $(this);
      loadingStart();
      form.find('input[type=submit]').attr('disabled', true);

      $.ajax({
          url: form.attr('action'),
          type: 'POST',
          dataType: 'json',
          data: new FormData(form[0]),
          contentType: false,
          processData: false,
          enctype: 'multipart/form-data',
          success: function (data) {
              form.find('input[type=submit]').attr('disabled', false);
              loadingFinish();
              toastr.success(data.message, "{{ __('general.success') }}");
              $('.modal').modal('hide');
              setTimeout(function () {
                  window.location = data.result.redirect;
              }, 1000);
          },
          error: function (e) {
              form.find('input[type=submit]').attr('disabled', false);
              if (e.status !== undefined && e.status === 422) {
                  let errors = e.responseJSON.errors;
                  $.each(errors, function (i, el) {
                      form.closest('form').find('input[name=' + i + ']').closest('.form-group').append('<label class="error">' + el[0] + '</label>')
                      form.closest('form').find('textarea[name=' + i + ']').closest('.form-group').append('<label class="error">' + el[0] + '</label>')
                      form.closest('form').find('select[name=' + i + ']').closest('.form-group').append('<label class="error">' + el[0] + '</label>')
                  })

              }

              toastr.error(e.responseJSON.message,'{{__('general.whoops')}}');
              loadingFinish();
          }
      })
    });

    $(document).on('click', '.btn-delete', function () {
        let modal = $('#modal-delete');
        modal.find('.name').text($(this).data('name'))
        modal.find('input[name=association_id]').val($(this).data('id'))
        modal.modal();
    });
  </script>
@endsection
