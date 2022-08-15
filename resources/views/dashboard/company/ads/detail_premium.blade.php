<!-- CONTENT MY PREMIUM -->
<div class="tab-pane" id="nav-mypremium" role="tabpanel" aria-labelledby="nav-my-premium">
  <div class="widget available-adds">
    <table class="table table-striped table-borderless dt-responsive display responsive no-wrap table-status-ads " id="data-table" cellspacing="0" style="width: 100%; min-width: auto;">
      <thead class="title-table">
        <tr>
          <th scope="col">{{ trans('premium.my_premium.date_order') }}</th>
          <th scope="col">{{ trans('premium.my_premium.no_invoice') }}</th>
          <th scope="col">{{ trans('premium.my_premium.premium_product') }}</th>
          <th scope="col">{{ trans('premium.my_premium.active_date') }}</th>
          <th scope="col">{{ trans('premium.my_premium.active_duration') }}</th>
          <th scope="col">Status</th>
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>

      <!-- MODAL PREMIUM UNPAID -->
      @include('dashboard.company.ads.modal_unpaid')
      <!-- MODAL PAID ADS -->
      @include('dashboard.company.ads.modal_paid')
      <!-- MODAL ACTIVE ADS -->
      @include('dashboard.company.ads.modal_active')
      <!-- MODAL INACTIVE ADS -->
      @include('dashboard.company.ads.modal_inactive')

  </div>
</div>