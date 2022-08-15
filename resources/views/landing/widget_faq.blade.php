@extends('landing.landing_base_layout')

@section('additionalStyle')
    <style>
        html {
            position: relative;
            overflow-x: hidden;
            min-height: 100vh;
        }
        body {
            background-color: #f0f8fe;
        }
        #top-navbar {
            background: #282d32;
            top: 0;
        }
        .widget-faq {
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
        .widget-faq .accordion .card-body {
            padding: 1.25rem 2rem;
            background-color: #f0f8fe;
            border: none;
            text-align: justify;
        }
        .widget-faq .accordion .card-body p {
            text-indent: 30px;
        }
        .widget-faq .accordion .card-body img {
            max-width: 900px;
            margin-left: auto;
            margin-right: auto;
            display: block;
            margin-bottom: 1rem;
            box-shadow: 0px 0px 7px 1px #0000001f;
            border-radius: 5px;
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
        .code {
            width: 100%;
            color: #212121;
            font-family: monospace;
            resize: none;
            border: none;
            text-align: center;
            background: #ffffff;
            border-radius: 10px;
            padding: 10px;
        }
        .widget-faq .accordion .card-body ol {
            margin-left: 30px;
        }
        .widget-faq .accordion .card-body ol li {
            text-indent: 0;
            padding-left: 10px;
        }
        .widget-faq {
            margin-bottom: 400px;
        }
        .footer-dark {
            position: absolute;
            bottom: 0;
            width: 100vw;
        }
        @media screen and (max-width: 767px){
            .btn-link {
                padding: .375rem 0;
                padding-right: 60px;
            }
            .widget-faq .accordion .card-body {
                padding: 1.25rem 1.3rem;
            }
            .widget-faq .accordion .card-body ol {
                margin-left: 10px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="widget-faq" id="widget_faq">
        <div class="font-weight-bold text-center mb-4 h3">
            {{ trans('gomodo_widget_faq.caption') }}
        </div>
        <div class="container">
            <div class="accordion" id="accordionExample">
                @foreach (trans('gomodo_widget_faq.content') as $content)
                    <div class="card">
                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse{{ $loop->index }}" aria-expanded="{{ $loop->index == 0 ? 'true' : 'false' }}" aria-controls="collapse{{ $loop->index }}">
                            <div class="card-header" id="heading{{ $loop->index }}">
                                <h5 class="mb-0 accordion-caption">
                                    {{-- <button class="btn btn-link" type="button" > --}}
                                        {!! $content['caption'] !!}
                                    {{-- </button> --}}
                                    <span class="plus-toggle">+</span>
                                </h5>
                            </div>
                        </button>
                    
                        <div id="collapse{{ $loop->index }}" class="collapse collapse-d {{ $loop->index == 0 ? 'show' : '' }}" aria-labelledby="heading{{ $loop->index }}" data-parent="#accordionExample">
                            <div class="card-body">
                                @if(isset($content['description']))
                                    <p>{!! $content['description'] !!}</p>
                                @endif
                                @if (isset($content['data-list']))
                                    <ol>
                                        @foreach ($content['data-list'] as $item)
                                            <li>{!! $item !!}</li>
                                        @endforeach
                                    </ol>                  
                                @endif
                                @if (isset($content['code-set']))
                                    @foreach ($content['code-set'] as $item)
                                        <p>{{ $item['prefix'] }}</p>
                                        <div class="code" readonly>{{ $item['code'] }}</div>
                                    @endforeach
                                @endif
                                @if (isset($content['setting-step']))
                                    @foreach ($content['setting-step'] as $item)
                                        <p>{!! $item['title'] !!}</p>
                                        @if (isset($item['desc']))
                                            <div>{!! $item['desc'] !!} </div>
                                        @endif
                                    @endforeach
                                @endif
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