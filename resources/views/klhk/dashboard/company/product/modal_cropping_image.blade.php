<button type="button" class="btn btn-primary d-none" data-toggle="modal" 
data-target=".modal-cropping" id="launchCropModal" data-backdrop="static" 
data-keyboard="false">
        {{-- Launch demo modal --}}
      </button>
      
<div class="modal fade modal-cropping" tabindex="-1" role="dialog" aria-labelledby="modalCropImage"
 aria-hidden="true" id="croppingModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title">{{ trans('product_provider.crop_image') }} <label for="modal-title" class="tooltips" title="{{ trans('product_provider.tooltip_crop') }}"><span class="fa fa-info-circle fs-14" style="width: auto; height: auto;"></span></label> </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <canvas class="canvas-cropping-image">
                    Your browser not support canvas tag
                </canvas>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn-crop-image" data-dismiss="modal">
                        {{ trans('product_provider.crop_btn') }}
                </button>
                <button type="button" class="btn btn-danger cancel-crop" data-cancel="" data-dismiss="modal" data-file-name="{{
                    trans('product_provider.file_name')}}">
                    {{ trans('offline_invoice.page-2.cancel') }}
                </button>
                <div class="position-btn-crop">
                    <button type="button" id="btn-zoom-in"><i class="fa fa-search-plus"></i></button>
                    <button type="button" id="btn-zoom-out"><i class="fa fa-search-minus"></i></button>
                    <button type="button" id="btn-up"><i class="fa fa-arrow-up"></i></button>
                    <button type="button" id="btn-down"><i class="fa fa-arrow-down"></i></button>
                    <button type="button" class=" btn-rotate" data-rotate="-90">
                        <i class="fa fa-undo"></i>
                    </button>
                    <button type="button" class="btn-rotate" data-rotate="90">
                        <i class="fa fa-repeat"></i>
                    </button>
                </div>            
            </div>
        </div>
    </div>
    <div class="display-none list-validation-cropping-image" data-validation-image="{{ trans('product_provider.not_image') }}"
    data-validation-value="{{ trans('product_provider.no_image_selected') }}"></div>
</div>