<div class="modal fade modal-faq-premium" id="modalTermConditionSignUp" tabindex="-1" role="dialog" aria-labelledby="modalFaqForPremium" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="text-center modal-title">{!! trans('tnc_provider.caption') !!}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" style="text-align: justify">
          <ol>
            <h5>{!! trans('tnc_provider.title') !!}</h5>
            {!! trans('tnc_provider.description') !!}
            <ol>
                @foreach (trans('tnc_provider.points') as $points)
                    <b><li>{!! $points['parent'] !!}</li></b>
                    @if (isset($points['child']))
                        <ol>
                            @foreach ($points['child'] as $child)
                                <li>{!! $child['children'] !!}</li>
                                @if (isset($child['grandchild']))
                                    <ol type="a">
                                        @foreach ($child['grandchild'] as $grandchild)
                                            <li>{!! $grandchild !!}</li>
                                        @endforeach
                                    </ol>        
                                @endif
                            @endforeach
                        </ol>        
                    @endif
                @endforeach
            </ol>
            <p>
                {!! trans('tnc_provider.agreement') !!}
            </p>
          </ol>
        </div>
      </div>
    </div>
  </div>