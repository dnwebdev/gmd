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
        .landing-faq {
            margin: 90px 0 66px;
        }
        .breadcrumb {
            background-color: unset
        }
        .accordion-caption {
            font-weight: bold!important;
            color: #3a4555;
            white-space: normal;
            text-align: left;
            display: inline-block;
            background-color: #ffffff;
            font-size: 1rem;
        }
        .accordion-caption:hover {
            text-decoration: none
        }
        .accordion .card {
            margin-bottom: 10px
        }
        .accordion .card, .accordion .card-header {
            border: none;
            background: white;
            text-align: left;
        }
        .landing-faq .accordion .card-body {
            text-indent: 30px;
            padding: 1.25rem 2rem;
            background-color: #f0f8fe;
            border: none;
            text-align: justify;
        }
        .accordion .btn-link {
            border: 1px solid #dfdfdf;
            margin-right: 0;
            padding-right: 60px;
        }
        .plus-toggle {
            float: right;
            background: #2699fb;
            padding: 0px 11px 1px;
            border-radius: 5px;
            font-size: 1.9rem;
            color: white;
            font-weight: bold;
            line-height: 1.2;
            position: absolute;
            right: 17px;
            top: 13px;
        }
        .minus {
            color: #7f7f7f;
            background: #e8e8e8;
            padding: 0px 15px 1px;
        }
        .navbar-brand .gomodo-text {
            color: white
        }
        @media screen and (max-width: 767px){
            .btn-link {
                padding: .375rem 0;
                padding-right: 60px;
            }
            .landing-faq .accordion .card-body {
                padding: 1.25rem 1.3rem;
            }
        }
    </style>
@endsection

@section('content')
    <div class="landing-faq" id="landing_faq">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{request()->isSecure()?'https://':'http://'}}{{env('B2B_DOMAIN')}}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">FAQ</li>
                </ol>
            </nav>
            <div class="accordion" id="accordionExample">
                @foreach (trans('landing_faq.faq') as $faq)
                    <div class="card">
                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse{{ $loop->index }}" aria-expanded="{{ $loop->index == 0 ? 'true' : 'false' }}" aria-controls="collapse{{ $loop->index }}">
                            <div class="card-header" id="heading{{ $loop->index }}">
                                <h5 class="mb-0 accordion-caption">
                                    {{-- <button class="btn btn-link" type="button" > --}}
                                        {!! $faq['q'] !!}
                                    {{-- </button> --}}
                                    <span class="plus-toggle">+</span>
                                </h5>
                            </div>
                        </button>
                    
                        <div id="collapse{{ $loop->index }}" class="collapse collapse-d {{ $loop->index == 0 ? 'show' : '' }}" aria-labelledby="heading{{ $loop->index }}" data-parent="#accordionExample">
                            <div class="card-body">
                                {!! $faq['a'] !!}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@section('additionalScript')
    <script type="text/javascript">
        $(document).on('click', '.btn-link', function(){
            plusMinus()
        })
        $(document).ready(function(){
            plusMinus();
        })
        function plusMinus(){
            setTimeout(function(){
                $('.collapse-d').each(function(i, e){
                    if($(e).hasClass('show')){
                        $(e).prev().find('.plus-toggle').text('-').addClass('minus');
                    } else {
                        $(e).prev().find('.plus-toggle').text('+').removeClass('minus');
                    }
                })       
            }, 400)
        }
        $('.nav-left').remove();
    </script>
@endsection