@extends('explore.layout')

@section('content')
    <section id="content">
      <div class="container">
        <div id="main">
          <div class="large-block image-box style6">
            <article class="box">
              <figure class="col-md-5">
                <div class="middle-block"><img class="middle-item" src="{{asset('img/gomodo.png')}}" alt="" width="476" height="318" /></div>
              </figure>
              <div class="details col-md-offset-5">
                <h4 class="box-title">{{trans('explore-lang.about_us.1')}}</h4>
                <p>{{trans('explore-lang.about_us.2')}}</p>
              </div>
            </article>
            <article class="box">
              <figure class="col-md-5 pull-right middle-block">
                <img class="middle-item" src="{{asset('explore-assets/images/how-it-works.png')}}" alt="" width="476" height="318" />
              </figure>
              <div class="details col-md-7">
                <h4 class="box-title">{{trans('explore-lang.about_us.3')}}</h4>
                <p>{{trans('explore-lang.about_us.4')}}</p>
              </div>
            </article>
            <article class="box">
              <figure class="col-md-5 pull-left middle-block">
                <img class="middle-item" src="{{asset('explore-assets/images/what-we-do2.jpg')}}" alt="" width="476" height="318" style="margin-top: -209px; opacity: 0.8;"/>
              </figure>
              <div class="details col-md-offset-5">
                <h4 class="box-title">{{trans('explore-lang.about_us.5')}}</h4>
                <p>{{trans('explore-lang.about_us.6')}}</p>
              </div>
            </article>
          </div>
        </div>
      </div>
    </section>

@stop

