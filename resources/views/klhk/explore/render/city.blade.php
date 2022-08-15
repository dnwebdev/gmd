@foreach($cities as $city)
    <div class="col-sm-6 col-md-4">
        <article class="box">
            <figure>
                <a href="{{route('explore.search',['type'=>'city','city'=>$city->id_state])}}">
                    @if($city->state_image)
                        <img src="{{File::exists(public_path($city->state_image)) ? asset($city->state_image):'http://placehold.it/1440x893'}}" alt="">
                    @else
                        <img src="http://placehold.it/1440x893" alt="">
                    @endif
                    <figcaption>
                        @if(app()->getLocale() == 'id')
                            <h2 class="caption-title">{{$city->state_name}}</h2>

                        @else
                            <h2 class="caption-title">{{$city->state_name_en}}</h2>
                        @endif

                    </figcaption>
                </a>
            </figure>
        </article>
    </div>
@endforeach

<div class="pagination-product hide" data-total="{{$cities->total()}}" data-current="{{$cities->currentPage()}}"
    data-nextPage="{{$cities->nextPageUrl()?$cities->currentPage()+1:null}}">
</div>
