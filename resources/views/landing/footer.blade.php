<div class="footer-dark">
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-sm-6 col-md-4 item">
                    <h3>{{trans('landing.about_gomodo')}}</h3>
                    <p>{{trans('landing.about_gomodo_content')}}</p>
                </div>
                <div class="col-sm-6 col-md-2 item">
                    <h3>{{trans('landing.links')}}</h3>
                    <ul>
                        {{-- <li><a class="disabled" target="_blank"
                        data-toggle="tooltip" href="{{request()->isSecure()?'https://':'http://'}}{{env('APP_URL')}}">{{ trans('landing.gomodo_directory') }}</a></li> --}}
                        <li><a class="disabled" target="_blank"
                            data-toggle="tooltip" href="https://medium.com/gomodo">Blog</a></li>
                        <li><a class="disabled" target="_blank"
                            data-toggle="tooltip" href="{{ url('faq') }}">FAQ</a></li>
                        <li><a class="disabled hide" target="_blank"
                                data-toggle="tooltip" title="{{trans('landing.new.comming_soon')}}"
                            >{{trans('landing.company')}}</a></li>
                        <li><a class="disabled hide" target="_blank"
                                data-toggle="tooltip" title="{{trans('landing.new.comming_soon')}}"
                            >{{trans('landing.team')}}</a></li>
                        <li><a class="disabled hide" target="_blank"
                                data-toggle="tooltip" title="{{trans('landing.new.comming_soon')}}"
                            >{{trans('landing.careers')}}</a></li>
                    </ul>
                </div>
                <div class="col-sm-6 col-md-3 item address">
                    <h3>{{trans('landing.address')}}</h3>
                    <div class="mb-2">
                        <div class="title">
                            {{ trans('landing.singapore') }} <i class="fa fa-caret-down pl-5px"></i>
                        </div>
                        <div class="content hide">
                            Republic Plaza 5f<br>
                            9 Raffles Pl, Central Business District, 048619, Singapore.
                        </div>

                    </div>
                    <div class="mb-2">
                        <div class="title">
                            Yogyakarta <i class="fa fa-caret-down pl-5px"></i>
                        </div>
                        <div class="content hide">
                            Gomodo Command Centre<br> Jl. Nitipuran  No.370 B, Sonosewu, Ngestiharjo, Kec. Kasihan, Bantul, Daerah Istimewa Yogyakarta 55184.
                        </div>

                    </div>
                    <div class="mb-2">
                        <div class="title">
                            Jakarta <i class="fa fa-caret-down pl-5px"></i>
                        </div>
                        <div class="content hide">
                            Synthesis Square Tower 2, Lt. 15<br>
                            Jl. Gatot Subroto No.177A, RT.9/RW.1, Menteng Dalam, Tebet, Kota Jakarta Selatan, 12870
                        </div>

                    </div>
                    <div class="mb-2" data-toggle="tooltip" title="{{trans('landing.new.comming_soon')}}">
                        <div class="title">
                            Bali <i class="fa fa-caret-down pl-5px"></i>
                        </div>
                        <div class="content hide">
                            Genesis Creative Centre <br>Genesis Co-Working Hub<br>
                            Tamora Gallery<br>
                            Jl. Pantai Berawa 99, Bali
                        </div>

                    </div>
                </div>
                <div class="col item social">
                    <h3 class="">{{trans('landing.follow_us')}}</h3>
                    <a target="_blank" href="https://www.facebook.com/gomodo.official/"><i
                                class="icon ion-social-facebook"></i></a>
                    <a target="_blank" href="https://twitter.com/Gomodo_official"><i class="icon ion-social-twitter"></i></a>
                    <a target="_blank" href="https://www.youtube.com/channel/UC63SauNWdORLCBh5J9U5T9g?view_as=subscriber"><i
                                class="icon ion-social-youtube"></i></a>
                    <a target="_blank" href="https://www.instagram.com/gomodo.official/"><i
                                class="icon ion-social-instagram"></i></a>
                </div>
            </div>
            <p class="copyright">© 2019 Gomodo Technologies. All Right Reserved.</p>
        </div>
    </footer>

</div>