@forelse($careers as $career)
    <div class="row list-career">
        <div class="col-lg-6 ">
            <a href="{{route('explore.careers.detail',['id'=>$career->id])}}"><h4 class="title-career">{{$career->title}}</h4></a>
            <p>{{trans('explore-lang.career.location')}} : {{$career->location}}</p>
            <a class="link-career" href="{{route('explore.careers.detail',['id'=>$career->id])}}">{{trans('explore-lang.career.vw')}}</a>
        </div>
        <div class="col-lg-6 text-center">
            <a href="{{route('explore.careers.request',['id'=>$career->id])}}" class="btn btn-primary btn-apply-career">{{trans('explore-lang.career.apply-position')}}
            </a>
        </div>
    </div>

@empty
    <div class="row list-career">
        <div class="col-lg-12 text-center">
            <p>{{trans('explore-lang.career.no-avail')}} </p>
        </div>
    </div>
@endforelse
<div class="pagination-career hide" data-current="{{$careers->currentPage()}}"
     data-nextPage="{{$careers->nextPageUrl()?$careers->currentPage()+1:null}}">
</div>
