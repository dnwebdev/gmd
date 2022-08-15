@extends('klhk.explore.layout')

@section('content')
    <section id="content">
      <div class="container">
        <div id="main">
          <div class="large-block image-box style6">
            <article class="box">
              <div class="col-md-4">
                <div class="middle-block"><img class="middle-item" src="{{asset('explore-assets/images/logo-pesona.png')}}" alt="" width="476" height="318" /></div>
              </div>
              <div class="details col-md-offset-4">
                <h4 class="box-title">{!! trans('explore-klhk-lang.about_us.title') !!}</h4>
                <p>{!! trans('explore-klhk-lang.about_us.descriptions') !!}</p>
              </div>
            </article>
          </div>
        </div>
      </div>
    </section>

@stop

