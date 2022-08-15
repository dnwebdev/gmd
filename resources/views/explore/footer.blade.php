<footer id="footer">
    <div class="footer-wrapper text-md-center">
        <div class="container">
            <div class="row">
                <div class="col-sm-6 col-md-4">
                    <h2>{{ trans('explore-lang.footer.discover.title') }}</h2>
                    <ul class="discover triangle hover row text-left">
                        <li class="col-xs-6 {{request()->route()->getName() == 'pres_releases.index' ? 'active':''}}"><a href="{{ route('pres_releases.index') }}">{{ trans('explore-lang.footer.discover.1') }}</a></li>
                        <li class="col-xs-6 {{request()->route()->getName() == 'explore.about_us' ? 'active':''}}"><a href="{{ route('explore.about_us') }}">{{ trans('explore-lang.footer.discover.2') }}</a></li>
                        @if(env('APP_ENV')=='production')
                        <li class="col-xs-6"><a href="{{request()->isSecure()?'https://':'http://'}}{{env('B2B_DOMAIN')}}">{{ trans('explore-lang.footer.discover.3') }}</a></li>
                        @else
                        <li class="col-xs-6"><a href="{{route('memoria.partner')}}">{{ trans('explore-lang.footer.discover.3') }}</a></li>
                        @endif
                        <li class="col-xs-6 {{request()->route()->getName() == 'explore.blog.index' ? 'active':''}}"><a href="{{ route('explore.blog.index') }}">{{ trans('explore-lang.footer.discover.4') }}</a></li>
                        <li class="col-xs-6"><a href="{{route('login')}}">{{ trans('explore-lang.footer.discover.5') }}</a></li>
                        <li class="col-xs-6 {{request()->route()->getName() == 'explore.careers.index' ? 'active':''}}"><a href="{{ route('explore.careers.index') }}">{{ trans('explore-lang.footer.discover.6') }}</a></li>
                        <li class="col-xs-6 {{request()->route()->getName() =='explore.help'?'active':''}}"><a href="{{ route('explore.help') }}">{{ trans('explore-lang.footer.discover.7') }}</a></li>
                        <li class="col-xs-6 {{request()->route()->getName() == 'explore.policy' ? 'active':''}}"><a href="{{ route('explore.policy') }}">{{ trans('explore-lang.footer.discover.8') }}</a></li>
                        <li class="col-xs-6 {{request()->route()->getName() == 'explore.term-condition' ? 'active':''}}"><a href="{{ route('explore.term-condition') }}">{{ trans('explore-lang.footer.discover.9')}}</a></li>
                    </ul>
                </div>
                <div class="col-sm-6 col-md-3">
                    <h2>{{ trans('explore-lang.footer.mailing_list.1') }}</h2>
                    <p>{{ trans('explore-lang.footer.mailing_list.2') }}</p>
                    <br/>
                    <div class="form-newsletter">
                        {!! Form::open(['method'=>'GET','id'=>'homepage-newsletter']) !!}
                        <div class="icon-check text-left">
                            <input type="email" class="input-text width-email-footer" name="email" placeholder="{{ trans('explore-lang.footer.mailing_list.3') }}" maxlength="50"/>
                            <button class="subscribe loading-button width-button-email-footer">{{trans('explore-lang.footer.subscribe')}}</button>
                        </div>
                        {!! Form::close() !!}
                    </div>

                    <br/>
                    <span>{{ trans('explore-lang.footer.mailing_list.4') }}</span>
                </div>

                <div class="col-sm-6 col-md-2">
                    <h2>Gomodo</h2>
                    <p>Discover. Book. Empower.</p>
                    <i class="fa fa-phone-square"> 0274 - 4288 - 422</i>
                    <br/>
                    <br/>
                    <ul class="social-icons clearfix">
                        <li class="twitter"><a title="twitter" href="https://twitter.com/Gomodo_official" data-toggle="tooltip" target="_blank"><i
                                        class="soap-icon-twitter"></i></a></li>
                        <li class="googleplus"><a title="instagram" href="https://www.instagram.com/gomodo.official/" data-toggle="tooltip" target="_blank"><i
                                        class="soap-icon-instagram"></i></a></li>
                        <li class="facebook"><a title="facebook" href="https://www.facebook.com/gomodo.official/" data-toggle="tooltip" target="_blank"><i
                                        class="soap-icon-facebook"></i></a></li>
                        <li class="youtube"><a title="youtube" href="https://www.youtube.com/channel/UC63SauNWdORLCBh5J9U5T9g" data-toggle="tooltip" target="_blank"><i
                                        class="soap-icon-youtube"></i></a></li>
                    </ul>
                </div>

                <div class="col-sm-6 col-md-3">
                    <h2 class="margin-bottom-0">{{ trans('explore-lang.footer.payment') }}</h2>
                    <div class="bank-icons clearfix text-center">
                        <img class="image-bank" src="{{asset('explore-assets/images/payment/bri.png')}}" alt="BRI Bank">
                        <img class="image-bank" src="{{asset('explore-assets/images/payment/bni.png')}}" alt="BNI Bank">
                        <img class="image-bank" src="{{asset('explore-assets/images/payment/mandiri-bank.png')}}" alt="Mandiri Bank">
                        <img class="image-bank" src="{{asset('explore-assets/images/payment/master-card.png')}}" alt="Master Card">
                        <img class="image-bank" src="{{asset('explore-assets/images/payment/visa.png')}}" alt="Visa">
                        <img class="image-bank" src="{{asset('explore-assets/images/payment/verifiedbyvisa.png')}}" alt="Verified by Visa">
                        <img class="image-bank" src="{{asset('explore-assets/images/payment/mastercard-securecode.png')}}" alt="Mastercard Securecode">
                        <img class="image-bank" src="{{asset('explore-assets/images/payment/ssl.png')}}" alt="SSL">
                        <a href="https://www.certipedia.com/quality_marks/0000065832" target="_blank">
                            <img class="image-bank" src="{{asset('explore-assets/images/payment/pci-dss-watermark.png')}}" alt="PCI" style="width:8rem;">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="bottom gray-area">
        <div class="container">
            <div class="logo pull-left">
                <a href="index.html" title="Gomodo">
                    <img src="{{asset('explore-assets/images/tour/home/logo.png')}}" alt="Travelo HTML5 Template"/>
                </a>
            </div>
            <div class="pull-right back-top">
                <a id="back-to-top" href="#" class="animated" data-animation-type="bounce"><i
                            class="soap-icon-longarrow-up circle"></i></a>
            </div>
            <div class="copyright pull-right">
                <p>&copy; 2018 Powered by Gomodo Technologies</p>
            </div>
        </div>
    </div>
</footer>
