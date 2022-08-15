@extends('explore.layout')

@section('content')
    <section id="content" class="content-term-condition">
        <div class="container">
            <div id="main">
                <h2>{{trans('explore-tos.tos.header')}}</h2>
                <p>{{trans('explore-tos.tos.1')}}</p><br>
                <p>{{trans('explore-tos.tos.2')}}</p><br>
                <p>{{trans('explore-tos.tos.3')}}</p><br>
                <ol>
                    @foreach (trans('explore-tos.tos.4') as $caption)
                        <li>{!! $caption['caption'] !!}
                            @if(isset($caption['content']))
                            <ul>
                                @foreach ($caption['content'] as $content)
                                    <li>
                                        {!! $content['content'] !!}
                                        @if (isset($content['subcontent']))
                                            <ul style="list-style: circle!important;">
                                                @foreach ($content['subcontent'] as $item)
                                                    <li>
                                                        {!! $item['subcontent'] !!}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                            @endif
                        </li>   
                    @endforeach
                </ol>
            </div>
        </div>
    </section>
@stop
