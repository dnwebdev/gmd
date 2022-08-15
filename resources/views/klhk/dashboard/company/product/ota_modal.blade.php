<div class="modal fade" id="ota-modal" tabindex="-1" role="dialog" aria-labelledby="ota-modal" aria-hidden="true">
	@php
		$data = empty($data) ? [] : $data->toArray();
		$approved = empty($approved) ? [] : $approved->toArray();
        $rejected = empty($rejected) ? [] : $rejected->toArray();
	@endphp
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="header text-center mb-3">
                    <h3 class="strong">
                        {{ __('product_provider.ota_list') }}
                    </h3>
                    <span>
                        {{ __('product_provider.ota_list_desc') }}
                    </span>
                </div>
                <div class="ota-list">
                    @foreach (\App\Models\Ota::where('ota_status', true)->get() as $ota)
                    <div class="media mb-3">
                        <img class="mr-3" src="{{ url($ota->ota_icon) }}" width="100" alt="{{ $ota->ota_name }}">
                        <div class="media-body align-self-center">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="mt-0 pb-0 mb-1">
                                    	<strong>{{ $ota->ota_name }}</strong>
                                    	<i class="fa fa-check-circle text-success approved approved-{{ $ota->id }}" style="display: {{ in_array($ota->id, $approved) ? 'inline' : 'none' }};vertical-align: bottom;line-height: 1.9rem;"></i>
                                        <i class="fa fa-times-circle text-danger rejected rejected-{{ $ota->id }}" style="display: {{ in_array($ota->id, $rejected) ? 'inline' : 'none' }};vertical-align: bottom;line-height: 1.9rem;"></i>
                                   	</h6>
                                    <div>
                                        {{ __('product_provider.distribution_fee') }}: <spab class="text-success">{{ $ota->ota_original_markup + $ota->gomodo_markup }}%</spab>
                                    </div>
                                </div>
                                <div class="align-self-center">
                                    <div class="el-switch">
                                        <input data-ota-id="{{ $ota->id }}" class="ota-value" id="ota-{{ $ota->id }}" type="checkbox" value="{{ $ota->id }}" name="ota[]" {{ in_array($ota->id, $data) ? 'checked' : '' }}>
                                        <label class="el-switch-style" for="ota-{{ $ota->id }}"></label>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="ota-footer text-center mt-3">
                    <button id="btn-submit" class="btn btn-success btn-cta step4" type="button" name="action" id="save" data-submit="{{trans('product_provider.submit')}}">{{ trans('product_provider.submit') }}</button>
                    <button type="button" class="btn btn-danger btn-close d-none" data-dismiss="modal">{{trans('general.close')}}</button>
                </div>
            </div>
        </div>
    </div>
</div>
