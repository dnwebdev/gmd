<!-- Modal -->
<div class="modal fade" id="logout_modal" tabindex="-1" role="dialog" aria-labelledby="logout_modal_label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mx-auto" id="logout_modal_label">{{ trans('sidebar_provider.logout_modal.text') }}</h5>
            </div>
            <div class="modal-footer mt-3">
                <div class="mx-auto">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('sidebar_provider.logout_modal.no') }}</button>
                    <a href="{{ Route('agent.logout') }}">
                        <button type="button" class="btn btn-primary">{{ trans('sidebar_provider.logout_modal.yes') }}</button>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>