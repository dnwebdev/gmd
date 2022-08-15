@extends('new-backoffice.header')
@section('content')
  <!-- new update alert -->
{{--  <div class="alert alert-info alert-styled-left alert-dismissible">--}}
{{--    <button type="button" class="close" data-dismiss="alert"><span>×</span></button>--}}
{{--    <span class="font-weight-semibold">Heads up!</span> This alert needs your attention, we have <a href="#" class="alert-link">new update</a>.--}}
{{--  </div>--}}

  <!-- promo carousel/ use slick carousel -->
  <!-- <div class="row dashboard-promo--container">
    <div class="col-lg-12">
      <div class="card">
        <div class="dashboard-promo">
            <div style="padding: .5rem">
              <a href="#"><img src="assets/img/banner-1-indo.png" style="width: 100%; max-width: 100%;"/></a>
            </div>
            <div style="padding: .5rem">
              <a href="#"><img src="assets/img/banner-2-indo.png" style="width: 100%; max-width: 100%;"/></a>
            </div>
            <div style="padding: .5rem">
                <a href="#"><img src="assets/img/banner-3-indo.png" style="width: 100%; max-width: 100%;"/></a>
            </div>
          </div>
      </div>
    </div>
  </div> -->

  <!-- tab  -->
  <h3 class="mt-3 mb-3">Dasbor</h3>
  <div class="row mt-3">
    <div class="col-lg-12">
      <div class="card" id="card-dashboard">
        <div class="card-body">
          <ul class="nav nav-tabs nav-tabs-highlight nav-justified d-block d-md-flex">
            <li class="nav-item"><a href="#highlighted-justified-tab1" class="nav-link active" data-toggle="tab">Hari ini</a></li>
            <li class="nav-item"><a href="#highlighted-justified-tab2" class="nav-link" data-toggle="tab">14 hari</a></li>
            <li class="nav-item"><a href="#highlighted-justified-tab3" class="nav-link" data-toggle="tab">30 hari</a></li>
            <li class="nav-item"><a href="#highlighted-justified-tab4" class="nav-link" data-toggle="tab">90 hari</a></li>
          </ul>

          <div class="tab-content" id="tab-dashboard">
            @foreach ($statistic as $data)
              <div class="tab-pane fade {{ $loop->index == 0 ? 'show active' : '' }}" id="highlighted-justified-tab{{ $loop->iteration }}">
                <!-- content tab here -->
                <div class="card-deck">
                  <div class="card">
                    <div class="card-body">
                      <div class="media">
                        <div class="mr-3 align-self-center">
                          <i class="icon-coins icon-3x text-indigo-400"></i>
                        </div>

                        <div class="media-body text-right">
                          <h6 class="font-weight-semibold mb-0">Rp. {{ $data['total_order'] }}</h6>
                          <span class="text-uppercase font-size-sm text-muted">Jumlah Order</span>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="card">
                    <div class="card-body">
                      <div class="media">
                        <div class="mr-3 align-self-center">
                          <i class="icon-cart icon-3x text-indigo-400"></i>
                        </div>

                        <div class="media-body text-right">
                          <h6 class="font-weight-semibold mb-0">Rp. {{ $data['total_order_online'] }}</h6>
                          <span class="text-uppercase font-size-sm text-muted">Order Online</span>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="card">
                    <div class="card-body">
                      <div class="media">
                        <div class="mr-3 align-self-center">
                          <i class="icon-cart2 icon-3x text-indigo-400"></i>
                        </div>

                        <div class="media-body text-right">
                          <h6 class="font-weight-semibold mb-0">Rp. {{ $data['total_order_offline'] }}</h6>
                          <span class="text-uppercase font-size-sm text-muted">Order di tempat</span>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="card">
                    <div class="card-body">
                      <div class="media">
                        <div class="mr-3 align-self-center">
                          <i class="icon-user icon-3x text-indigo-400"></i>
                        </div>

                        <div class="media-body text-right">
                          <h6 class="font-weight-semibold mb-0">Coming Soon</h6>
                          <span class="text-uppercase font-size-sm text-muted">Distribusi</span>
                        </div>
                      </div>
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

  <!-- TOP 10 -->
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-6">
              <h3>Top KUPS</h3>
            </div>
            <div class="col-6">
              <form method="get" action="" id="change-range-top">
                @php
                  $range = [30, 60, 90, 180, 360];
                @endphp
                <select id="change-top" class="form-control" name="top-provider">
                  @foreach ($range as $day)
                    <option value="{{ $day }}" {{ old('top-provider') == $day ? 'selected' : '' }}>{{ $day }} hari</option>
                  @endforeach
              </form>
              </select>
            </div>
          </div>

          <div class="table-responsive table-top-10">
            <table class="table text-nowrap">
              <thead>
              <tr>
                <th style="width:4rem;">No</th>
                <th>Operator</th>
                <th>Online</th>
                <th>Onsite</th>
              </tr>
              </thead>
              <tbody>
                @foreach ($top_providers as $provider)
                  <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                      <div class="d-flex align-items-center">
                        {{-- <div class="mr-2">
                          <img src="{{ $provider['logo'] }}" width="32" alt="">
                        </div> --}}
                        <div>
                          <span>{{ $provider['name'] }}</span>
                        </div>
                      </div>
                    </td>
                    <td>
                      Rp. {{ $provider['order_online'] }}
                    </td>
                    <td>
                      Rp. {{ $provider['order_offline'] }}
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>


        </div>
      </div>
    </div>
  </div>

  <!-- AVERAGE OPERATOR EARNING -->
  <div class="row" id="average-operator-earning">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body">
          <h3>Rata-Rata Pendapatan Operator (Coming Soon)</h3>
          <div class="row">
            <div class="col-lg-3" style="min-height: 200px">
              <button style="height: 100%" class="text-center align-items-center average-earning" disabled>
                <i class="icon-diamond icon-2x mb-3"></i><br>
                <span>RATA-RATA PENDAPATAN BLUE</span>
              </button>
            </div>
            <div class="col-lg-3" style="min-height: 200px">
              <button style="height: 100%" type="button" class="text-center align-items-center average-earning" disabled>
                <i class="icon-diamond icon-2x mb-3"></i><br>
                <span>RATA-RATA PENDAPATAN SILVER</span>
              </button>
            </div>
            <div class="col-lg-3" style="min-height: 200px">
              <button style="height: 100%" class="text-center align-items-center average-earning" disabled>
                <i class="icon-diamond icon-2x mb-3"></i><br>
                <span>RATA-RATA PENDAPATAN GOLD</span>
              </button>
            </div>
            <div class="col-lg-3" style="min-height: 200px">
              <button style="height: 100%" class="text-center align-items-center average-earning" disabled>
                <i class="icon-diamond icon-2x mb-3"></i><br>
                <span>RATA-RATA PENDAPATAN PLATINUM</span>
              </button>
            </div>


{{--            UNCOMENT WHEN READY TO USE--}}
{{--            <div class="col-6 mt-2">--}}
{{--              <h4 class="mt-1">Average Gold Operator Earnings</h4>--}}
{{--              <img src="{{asset('limitless/img/grafik.png')}}" alt="">--}}
{{--            </div>--}}
{{--            <div class="col-6 mt-2">--}}
{{--              <select name="" id="" class="form-control" disabled>7 Hari</select>--}}
{{--              <div class="money-big d-flex align-content-center">--}}
{{--                <i class="icon-coin-dollar"></i>--}}
{{--                <div class="align-content-center">--}}
{{--                  <span>Rp. 670.000.000,</span>--}}
{{--                  <small>Total average earning</small>--}}
{{--                </div>--}}
{{--              </div>--}}
{{--              <div class="row mt-3 earning-each-operator">--}}
{{--                <div class="col-6">--}}
{{--                  <h6>Operators</h6>--}}
{{--                  <span>Hutan Kemasyarakatan Kalibiru</span>--}}
{{--                  <span>Hutan Kemasyarakatan Kalibiru</span>--}}
{{--                  <span>Hutan Kemasyarakatan Kalibiru</span>--}}
{{--                  <span>Hutan Kemasyarakatan Kalibiru</span>--}}
{{--                  <span>Hutan Kemasyarakatan Kalibiru</span>--}}
{{--                </div>--}}
{{--                <div class="col-6">--}}
{{--                  <h6>Earnings</h6>--}}
{{--                  <span>Rp. 250.000,-</span>--}}
{{--                  <span>Rp. 250.000,-</span>--}}
{{--                  <span>Rp. 250.000,-</span>--}}
{{--                  <span>Rp. 250.000,-</span>--}}
{{--                  <span>Rp. 250.000,-</span>--}}
{{--                </div>--}}
{{--              </div>--}}
{{--            </div>--}}
            <div class="col-12 mt-3">
              <h3>Statistik</h3>
              @include('new-backoffice.partial.coming_soon')
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
@endsection

@section('additionalScript')
<script type="text/javascript">
  $('#change-top').on('change', function () {
    $.get('{{ route('admin:dashboard.top.provider') }}/' + $(this).val(), function (data) {
      $('.table-top-10 tbody tr').remove();
      $.each(data, function (key, value) {
        $('.table-top-10 tbody').append('<tr><td>'+(key+1)+'</td>'+'<td>'+value.name+'</td>'+ '<td> Rp. '+value.order_online+'</td>'+'<td> Rp. '+value.order_offline+'</td></tr>');
      })
    })
  })
</script>
@endsection
