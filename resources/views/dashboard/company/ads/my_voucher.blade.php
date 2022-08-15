                <div class="widget available-adds">
                  <div class="container-fluid row">
                    @forelse ($cash_back->cash_backs as $item)
                    <div class="col-12 col-lg-4">
                      <div class="card card-my-voucher">
                        {{-- <img src="http://www.narrewarrengirlguides.org/wp-content/uploads/2012/04/800px-F1_light_blue_flag_svg.png"> --}}
                        <div class="card-body text-center">
                          <input type="hidden" name="id" value="{{ $item->id }}" id="id_myvoucher">
                          <input type="hidden" name="nominal_voucher" value="{{ $item->nominal }}" id="nominal_voucher">
                          {{-- <input type="hidden" name="code" value="{{ $item->code }}" id="code_voucher"> --}}
                          <label id="total-discount-text">{{ $item->currency }} {{ $item->nominal }} Solusi Pemasaran</label><br>
                          <label id="date-text">Periode Promo</label><br>
                          <label id="date-text-until">{{ date('d M Y', strtotime($item->expired_at)) }}</label>
                        </div>
                        <div class="text-center">
                          <button type="button" class="btn btn-primary" id="btn-use-voucher">Use Voucher</button>
                        </div>
                      </div>
                    </div>
                    @empty
                    <div class="col-12 col-lg-12 text-center">
                      <p class="margin-2-0">{{ trans('premium.banner.empty_data') }}</p>
                    </div>
                    @endforelse
                  </div>
                </div>