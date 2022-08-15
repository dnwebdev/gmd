<div class="sidebar col-sm-4 col-md-3">
    <form id="search-press" autocomplete="off">
        <div class="travelo-box">
            <h5 class="box-title">{{trans('explore-lang.stories.search')}}</h5>
            <div class="with-icon full-width">
                <input type="text" class="input-text full-width" name="q"
                       placeholder="{{trans('explore-lang.stories.search-p')}}">
                <button class="icon blue-bg white-color" id="search-press"><i class="soap-icon-search"></i></button>
            </div>
        </div>
    </form>
    <div class="tab-container box">
        <ul class="tabs full-width">
            <li><a data-toggle="tab" href="#recent-posts">{{trans('explore-lang.stories.recent')}}</a></li>
            <li class="active"><a data-toggle="tab" href="#popular-posts">{{trans('explore-lang.stories.popular')}}</a>
            </li>
        </ul>

        <div class="tab-content">
            <div id="recent-posts" class="tab-pane fade">
                <div class="image-box style14">
                    @forelse ($recentBlog as $recent)
                        <article class="box">
                            <figure><a href="{{route('pres_releases.detail',['slug'=>$recent->slug])}}" title=""><img
                                            style="object-fit: cover;width: 70px;height: 70px" src="{{ asset($recent->image_blog) }}" alt=""></a>
                            </figure>
                            <div class="details">
                                <h5 class="box-title"><a
                                            href="{{route('pres_releases.detail',['slug'=>$recent->slug])}}">
                                        @if (App::getLocale() ==='id')
                                            {{ str_limit($recent->title_ind, 15) }}
                                        @else
                                            {{ str_limit($recent->title_eng, 15) }}
                                        @endif
                                    </a></h5>
                                @if (App::getLocale() ==='id')
                                    {!! (str_limit(strip_tags($recent->description_ind), 25)) !!}
                                @else
                                    {!! (str_limit(strip_tags($recent->description_eng), 25)) !!}
                                @endif
                            </div>
                        </article>
                    @empty

                    @endforelse
                </div>
            </div>
            <div id="popular-posts" class="tab-pane fade in active">
                <div class="image-box style14">
                    @forelse ($popularBlog as $popular)
                        <article class="box">
                            <figure><a href="{{route('pres_releases.detail',['slug'=>$popular->slug])}}" title=""><img
                                            style="object-fit: cover;width: 70px;height: 70px" src="{{ asset($popular->image_blog) }}" alt=""></a>
                            </figure>
                            <div class="details">
                                <h5 class="box-title"><a
                                            href="{{route('pres_releases.detail',['slug'=>$popular->slug])}}">
                                        @if (App::getLocale() ==='id')
                                            {{ str_limit($popular->title_ind, 15) }}
                                        @else
                                            {{ str_limit($popular->title_eng, 15) }}
                                        @endif
                                    </a></h5>
                                @if (App::getLocale() ==='id')
                                    {!! (str_limit(strip_tags($popular->description_ind), 25)) !!}
                                @else
                                    {!! (str_limit(strip_tags($popular->description_eng), 25)) !!}
                                @endif
                            </div>
                        </article>
                    @empty

                    @endforelse
                </div>
            </div>

        </div>
    </div>
</div>
