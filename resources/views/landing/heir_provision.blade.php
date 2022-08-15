@extends('landing.landing_base_layout')

@section('additionalStyle')
    <style>
        body {
            background-color: #f0f8fe;
        }
        #top-navbar {
            background: #282d32;
            top: 0;
        }
        .heir_provision {
            margin: 90px 0 66px;
        }
        .caption {
            font-size: 1.25rem;
            margin: 30px;
        }
        .closing {
            text-indent: 40px;
        }
    </style>
@endsection

@section('content')
    <div class="heir_provision" id="heir_provision">
        <div class="container text-center font-weight-bold">
            <div class="caption">{{ trans('heir_provision.caption') }}</div>
            <ol class="text-justify font-weight-normal">
                @foreach (trans('heir_provision.heir') as $heir)
                    <li>{!! $heir['parent'] !!}</li>
                    @if (isset($heir['child']))
                        <ol type="a">
                            @foreach ($heir['child'] as $child)
                                <li>{!! $child !!}</li>    
                            @endforeach
                        </ol>
                    @endif
                @endforeach
            </ol>
            <div class="closing text-justify font-weight-normal"><p>{{ trans('heir_provision.closing') }}</p></div>
        </div>
    </div>
@endsection

@section('additionalScript')
    <script>
        $('.nav-left').remove();
    </script>
@endsection