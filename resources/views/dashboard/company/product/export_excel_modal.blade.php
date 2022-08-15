<div class="modal fade" id="export-excel-modal" tabindex="-1" role="dialog" aria-labelledby="ota-modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-center">@lang('product_provider.export_order.modal.title')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="modal-body" method="post" action="{{ route('company.product.export') }}">
                {{ csrf_field() }}
                <input type="hidden" name="id">
                <div class="d-flex my-3">
                    <div>@lang('product_provider.export_order.modal.paid_only')</div>
                    <div class="el-switch ml-auto">
                        <input type="checkbox" id="paid_only" name="paid_only" value="true">
                        <label class="el-switch-style" for="paid_only"></label>
                    </div>
                </div>
                <div class="mt-2 text-right">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('product_provider.export_order.modal.cancel')</button>
                    <button type="submit" class="btn btn-primary">@lang('product_provider.export_order.modal.export')</button>
                </div>
            </form>
        </div>
    </div>
</div>
