<div class="modal fade" id="order-date-modal" tabindex="-1" role="dialog" aria-labelledby="ota-modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-center">@lang('product_provider.order_date_filter.modal.title')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="modal-body" method="get" action="{{ route('company.order.index') }}">
                <input type="hidden" name="product_id">
                <div class="form-group">
                    <label for="order_date">@lang('product_provider.order_date_filter.modal.date')</label>
                    <input id="order_date" type="text" class="form-control date-picker" name="date" value="{{ now()->format('d/m/Y') }}">
                </div>
                <div class="mt-2 text-right">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('product_provider.order_date_filter.modal.cancel')</button>
                    <button type="submit" class="btn btn-primary">@lang('product_provider.order_date_filter.modal.ok')</button>
                </div>
            </form>
        </div>
    </div>
</div>
