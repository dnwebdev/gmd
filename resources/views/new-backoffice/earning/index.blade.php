@extends('new-backoffice.header')
@section('content')
  <div class="header-content-modify" id="content-earning">
    <h3 class="mt-3 mb-3">Pendapatan</h3>
{{--    <div class="card">--}}
{{--      <div class="card-body">--}}
{{--        <h4><strong>Coming Soon</strong></h4>--}}
{{--        <!--      ENABLE WHEN ALLREADY WILL USE-->--}}
{{--        <!--      <ul class="nav nav-tabs nav-tabs-highlight nav-justified">-->--}}
{{--        <!--        <li class="nav-item background-disabled" disabled="true"><a href="#tab-1" class="nav-link" data-toggle="tab"><i class="icon-diamond"></i> Pendapatan Gold</a></li>-->--}}
{{--        <!--        <li class="nav-item background-disabled" disabled="true"><a href="#tab-2" class="nav-link" data-toggle="tab"><i class="icon-diamond"></i> Pendapatan Platinum</a></li>-->--}}
{{--        <!--        <li class="nav-item background-disabled" disabled="true"><a href="#tab-3" class="nav-link" data-toggle="tab"><i class="icon-diamond"></i> Pendapatan Silver</a></li>-->--}}
{{--        <!--        <li class="nav-item background-disabled" disabled="true"><a href="#tab-4" class="nav-link" data-toggle="tab"><i class="icon-diamond"></i> Pendapatan Blue</a></li>-->--}}
{{--        <!--      </ul>-->--}}

{{--        <!--      <div class="tab-content">-->--}}
{{--        <!--        <div class="tab-fane fade show active" id="tab-1">-->--}}

{{--        <!--        </div>-->--}}
{{--        <!--&lt;!&ndash;        TAB 2&ndash;&gt;-->--}}
{{--        <!--        <div class="tab-fane fade" id="tab-2"></div>-->--}}
{{--        <!--        <div class="tab-fane fade" id="tab-3"></div>-->--}}
{{--        <!--        <div class="tab-fane fade" id="tab-4"></div>-->--}}

{{--        <!--      </div>-->--}}

{{--        <!--      TAB CHANGED TO SPAN FOR A WHILE-->--}}
{{--        <div class="row" id="disabled-tab">--}}
{{--          <div class="col-3 background-disabled">--}}
{{--          <span>--}}
{{--            <i class="icon-diamond"></i>--}}
{{--            &nbsp;Pendapatan Gold--}}
{{--          </span>--}}
{{--          </div>--}}
{{--          <div class="col-3 background-disabled">--}}
{{--          <span>--}}
{{--            <i class="icon-diamond"></i>--}}
{{--            &nbsp;Pendapatan Platinum--}}
{{--          </span>--}}
{{--          </div>--}}
{{--          <div class="col-3 background-disabled">--}}
{{--          <span>--}}
{{--            <i class="icon-diamond"></i>--}}
{{--            &nbsp;Pendapatan Silver--}}
{{--          </span>--}}
{{--          </div>--}}
{{--          <div class="col-3 background-disabled">--}}
{{--          <span>--}}
{{--            <i class="icon-diamond"></i>--}}
{{--            &nbsp;Pendapatan Blue--}}
{{--          </span>--}}
{{--          </div>--}}
{{--        </div>--}}

{{--        <div class="row" id="header-table">--}}
{{--          <div class="col-6 d-inline-flex">--}}
{{--            <span>Tampilkan </span>--}}
{{--            <select name="" id="" class="form-control">--}}
{{--              <option value="0">10</option>--}}
{{--            </select>--}}
{{--          </div>--}}
{{--          <div class="col-6 d-flex">--}}
{{--            <ul class="nav nav-tabs">--}}
{{--              <li class="nav-item"><a href="#tab-1" class="nav-link active" data-toggle="tab"><i class="icon-calendar"></i></a></li>--}}
{{--              <li class="nav-item"><a href="#tab-2" class="nav-link" id="tab-with-graph-line" data-toggle="tab"><i class="icon-graph"></i></a></li>--}}
{{--              <li class="nav-item"><a href="#tab-3" class="nav-link" id="tab-with-graph-circle" data-toggle="tab"><i class="icon-gradient"></i></a></li>--}}
{{--            </ul>--}}
{{--            <div id="aditional-input">--}}
{{--              <select name="" id="">--}}
{{--                <option value="1">Today</option>--}}
{{--              </select>--}}
{{--              <i class="icon-pencil"></i>--}}
{{--              <button type="button" class="btn btn-primary" disabled><i class="icon-download"></i> Export</button>--}}
{{--            </div>--}}
{{--          </div>--}}
{{--        </div>--}}

{{--        <div class="tab-content">--}}
{{--          <div class="tab-pane fade show active" id="tab-1">--}}
{{--            <table class="table datatable-responsive-column-controlled">--}}
{{--              <thead>--}}
{{--              <tr>--}}
{{--                <th></th>--}}
{{--                <th>Operator</th>--}}
{{--                <th>Transaksi</th>--}}
{{--                <th class="text-center">Aksi</th>--}}
{{--              </tr>--}}
{{--              </thead>--}}
{{--              <tbody>--}}
{{--              <tr>--}}
{{--                <td></td>--}}
{{--                <td>dummy.mygomodo.com</td>--}}
{{--                <td>Rp. 230.696</td>--}}
{{--                <td class="text-center action-table">--}}
{{--                  <a href="#" type="button" data-toggle="modal" data-target=".modal-earning"><i class="icon-eye"></i></a>--}}
{{--                </td>--}}
{{--              </tr>--}}
{{--              <tr>--}}
{{--                <td></td>--}}
{{--                <td>dummy.mygomodo.com</td>--}}
{{--                <td>Rp. 321.654</td>--}}
{{--                <td class="text-center action-table">--}}
{{--                  <a href="#" type="button" data-toggle="modal" data-target=".modal-earning"><i class="icon-eye"></i></a>--}}
{{--                </td>--}}
{{--              </tr>--}}
{{--              <tr>--}}
{{--                <td></td>--}}
{{--                <td>dummy.mygomodo.com</td>--}}
{{--                <td>Rp. 123.345</td>--}}
{{--                <td class="text-center action-table">--}}
{{--                  <a href="#" type="button" data-toggle="modal" data-target=".modal-earning"><i class="icon-eye"></i></a>--}}
{{--                </td>--}}
{{--              </tr>--}}
{{--              </tbody>--}}
{{--            </table>--}}
{{--            @include('new-backoffice.earning.modal_earning_detail')--}}
{{--          </div>--}}
{{--          <div class="tab-pane fade" id="tab-2">--}}
{{--            <!--          <div class="chart" id="app_sales"></div>-->--}}
{{--            <h4>Coming Soon</h4>--}}
{{--            <div class="row">--}}
{{--              <div class="col-6">--}}
{{--                <h6 class="card-title">Sales statistics</h6>--}}
{{--                <div class="header-elements">--}}
{{--                  <select class="form-control" id="select_date" data-fouc>--}}
{{--                    <option value="val1">June, 29 - July, 5</option>--}}
{{--                    <option value="val2">June, 22 - June 28</option>--}}
{{--                    <option value="val3" selected>June, 15 - June, 21</option>--}}
{{--                    <option value="val4">June, 8 - June, 14</option>--}}
{{--                  </select>--}}
{{--                </div>--}}
{{--              </div>--}}

{{--              <div class="card-body py-0">--}}
{{--                <div class="row text-center">--}}
{{--                  <div class="col-4">--}}
{{--                    <div class="mb-3">--}}
{{--                      <h5 class="font-weight-semibold mb-0">5,689</h5>--}}
{{--                      <span class="text-muted font-size-sm">new orders</span>--}}
{{--                    </div>--}}
{{--                  </div>--}}

{{--                  <div class="col-4">--}}
{{--                    <div class="mb-3">--}}
{{--                      <h5 class="font-weight-semibold mb-0">32,568</h5>--}}
{{--                      <span class="text-muted font-size-sm">this month</span>--}}
{{--                    </div>--}}
{{--                  </div>--}}

{{--                  <div class="col-4">--}}
{{--                    <div class="mb-3">--}}
{{--                      <h5 class="font-weight-semibold mb-0">23,464</h5>--}}
{{--                      <span class="text-muted font-size-sm">expected profit</span>--}}
{{--                    </div>--}}
{{--                  </div>--}}
{{--                </div>--}}
{{--              </div>--}}
{{--            </div>--}}
{{--            <div class="chart mb-2" id="app_sales"></div>--}}
{{--            <div class="chart" id="monthly-sales-stats"></div>--}}
{{--            @include('new-backoffice.partial.coming_soon')--}}
{{--          </div>--}}
{{--          <div class="tab-pane fade centerize-svg" id="tab-3">--}}
{{--            <h4>Coming Soon</h4>--}}
{{--            <div id="campaign-status-pie-hnd"></div>--}}
{{--            @include('new-backoffice.partial.coming_soon')--}}
{{--          </div>--}}
{{--        </div>--}}
        <!--      TABLE START HERE-->
{{--      </div>--}}
{{--    </div>--}}
    @include('new-backoffice.partial.coming_soon')
  </div>
@endsection