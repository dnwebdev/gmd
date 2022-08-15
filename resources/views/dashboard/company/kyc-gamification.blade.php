@if (!auth()->user()->company->kyc)
<div class="card card-body border-top-primary bg-dark" id="KycGamificationProgressbar">
    <div class="header-elements text-right position-absolute close-button-container">
        <div class="list-icons">
            <a class="list-icons-item" data-action="remove"></a>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="row">
                <div class="col-md-auto text-center">
                    <img src="{{ asset('dest-operator/img/crown.png') }}" alt="crown" class="m-auto p-1 crown"/>
                </div>
                <div class="col text-center text-md-left">
                    <h6 class="font-weight-semibold h2">{!! trans('kyc.kyc-gamification.title') !!}</h6>
                    <p class="text-muted">{!! trans('kyc.kyc-gamification.desc') !!}</p>
                </div>
            </div>
        </div>
        <div class="col-md-auto m-auto">
            <a href="{{ route('company.kyc.index') }}" class="btn btn-primary legitRipple withdrawBtn w-100">
                {!! trans('kyc.kyc-gamification.button') !!}
            </a>
        </div>
    </div>
</div>
@endif