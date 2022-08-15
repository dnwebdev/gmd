<div class="modal modal-add-category" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form method="post" action="{{ route('admin:providers.export') }}">
      {{ csrf_field() }}
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Unduh</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
          <div class="d-block">
            <span class="d-inline-flex">
              <span class="mr-5">Transaksi Yang Berhasil</span>
              <label class="label-switch switch-success">
              <input type="checkbox" class="switch switch-bootstrap status" name="hasSuccessfulTransaction" id="status" value="1">
              <span class="lable"></span></label>
            </span>
          </div>
          <div class="d-inline-flex w-100 d-none" id="dateRangPickerDiv">
            <span class="mr-4">Dari tanggal - Sampai tanggal </span>
            <input value="03/01/2019 - {{\Carbon\Carbon::now()->format('m/d/Y')}}" type="text" class="form-control w-50" id="daterange" name="range" readonly="" placeholder="Select time">
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-primary">Download</button>
        </div>
      </div>
    </form>

  </div>
</div>
