@extends('explore.layout')

@section('content')
    <section id="content">
      <div class="container">
        <div id="main" class="faqs style1">
          <div class="row">
            <div class="col-sm-4 col-md-3">
              <div class="travelo-box search-questions">
                <h5 class="box-title">{{ trans('explore-lang.help.search') }}</h5>
                <form id="search">
                  <div class="with-icon full-width">
                    <input type="text" class="input-text full-width" placeholder="{{ trans('explore-lang.help.search-placeholder') }}" id="search-word">
                    <button class="icon blue-bg white-color"><i class="soap-icon-search"></i></button>
                  </div>
                </form>
              </div>
              <div class="travelo-box filters-container faq-topics">
                <h4 class="box-title">{{ trans('explore-lang.help.faq_topic') }}</h4>
                  <ul class="triangle filters-option">
                    <li><a id="help_all" class="active" href="#">{{ trans('explore-lang.help.all') }}</a></li>
                    <li><a id="help_order_process" href="#">{{ trans('explore-lang.help.order_process') }}</a></li>
                    <li><a id="help_coupon" href="#">{{ trans('explore-lang.help.coupon') }}</a></li>
                    <li><a id="help_payment_process" href="#">{{ trans('explore-lang.help.payment_process') }}</a></li>
                    <li><a id="help_cancelation" href="#">{{ trans('explore-lang.help.cancelation') }}</a></li>
                  </ul>
              </div>
            </div>
            <div class="col-sm-8 col-md-9">
              <div class="travelo-box question-list">
                <div class="toggle-container">
                  
                  <div class="panel style1 help_order_process">
                    <h4 class="panel-title">
                      <a data-toggle="collapse" href="#tgg1" class="collapsed">{{ trans('explore-lang.help.quest1') }}</a>
                    </h4>
                    <div id="tgg1" class="panel-collapse collapse">
                      <div class="panel-content">
                          <p>{{ trans('explore-lang.help.answer1') }}</p>
                      </div>
                    </div>
                  </div>

                  <div class="panel style1 help_order_process">
                    <h4 class="panel-title">
                      <a data-toggle="collapse" href="#tgg2" class="collapsed">{{ trans('explore-lang.help.quest2') }}</a>
                    </h4>
                    <div id="tgg2" class="panel-collapse collapse">
                      <div class="panel-content">
                          <p>{{ trans('explore-lang.help.answer2') }}</p>
                      </div>
                    </div>
                  </div>

                  <div class="panel style1 help_order_process">
                    <h4 class="panel-title">
                      <a data-toggle="collapse" href="#tgg3" class="collapsed">{{ trans('explore-lang.help.quest3') }}</a>
                    </h4>
                    <div id="tgg3" class="panel-collapse collapse">
                      <div class="panel-content">
                          <p>{{ trans('explore-lang.help.answer3') }}</p>
                      </div>
                    </div>
                  </div>

                  <div class="panel style1 help_order_process">
                    <h4 class="panel-title">
                      <a data-toggle="collapse" href="#tgg4" class="collapsed">{{ trans('explore-lang.help.quest4') }}</a>
                    </h4>
                    <div id="tgg4" class="panel-collapse collapse">
                      <div class="panel-content">
                          <p>{{ trans('explore-lang.help.answer4') }}</p>
                      </div>
                    </div>
                  </div>

                  <div class="panel style1 help_order_process">
                    <h4 class="panel-title">
                      <a data-toggle="collapse" href="#tgg5" class="collapsed">{{ trans('explore-lang.help.quest5') }}</a>
                    </h4>
                    <div id="tgg5" class="panel-collapse collapse">
                      <div class="panel-content">
                          <p>{{ trans('explore-lang.help.answer5') }}</p>
                      </div>
                    </div>
                  </div>

                  <div class="panel style1 help_order_process">
                    <h4 class="panel-title">
                      <a data-toggle="collapse" href="#tgg6" class="collapsed">{{ trans('explore-lang.help.quest6') }}</a>
                    </h4>
                    <div id="tgg6" class="panel-collapse collapse">
                      <div class="panel-content">
                          <p>{{ trans('explore-lang.help.answer6') }}</p>
                      </div>
                    </div>
                  </div>

                  <div class="panel style1 help_order_process">
                    <h4 class="panel-title">
                      <a data-toggle="collapse" href="#tgg7" class="collapsed">{{ trans('explore-lang.help.quest7') }}</a>
                    </h4>
                    <div id="tgg7" class="panel-collapse collapse">
                      <div class="panel-content">
                          <p>{{ trans('explore-lang.help.answer7') }}</p>
                      </div>
                    </div>
                  </div>

                  <div class="panel style1 help_coupon">
                    <h4 class="panel-title">
                      <a data-toggle="collapse" href="#tgg8" class="collapsed">{{ trans('explore-lang.help.quest8') }}</a>
                    </h4>
                    <div id="tgg8" class="panel-collapse collapse">
                      <div class="panel-content">
                          <p>{{ trans('explore-lang.help.answer8') }}</p>
                      </div>
                    </div>
                  </div>

                  <div class="panel style1 help_payment_process">
                    <h4 class="panel-title">
                      <a data-toggle="collapse" href="#tgg9" class="collapsed">{{ trans('explore-lang.help.quest9') }}</a>
                    </h4>
                    <div id="tgg9" class="panel-collapse collapse">
                      <div class="panel-content">
                          <p>{{ trans('explore-lang.help.answer9') }}</p>
                      </div>
                    </div>
                  </div>

                  <div class="panel style1 help_payment_process">
                    <h4 class="panel-title">
                      <a data-toggle="collapse" href="#tgg10" class="collapsed">{{ trans('explore-lang.help.quest10') }}</a>
                    </h4>
                    <div id="tgg10" class="panel-collapse collapse">
                      <div class="panel-content">
                          <p>{{ trans('explore-lang.help.answer10') }}</p>
                      </div>
                    </div>
                  </div>

                  <div class="panel style1 help_payment_process">
                    <h4 class="panel-title">
                      <a data-toggle="collapse" href="#tgg11" class="collapsed">{{ trans('explore-lang.help.quest11') }}</a>
                    </h4>
                    <div id="tgg11" class="panel-collapse collapse">
                      <div class="panel-content">
                          <p>{{ trans('explore-lang.help.answer11') }}</p>
                      </div>
                    </div>
                  </div>

                  <div class="panel style1 help_payment_process">
                    <h4 class="panel-title">
                      <a data-toggle="collapse" href="#tgg12" class="collapsed">{{ trans('explore-lang.help.quest12') }}</a>
                    </h4>
                    <div id="tgg12" class="panel-collapse collapse">
                      <div class="panel-content">
                          <p>{{ trans('explore-lang.help.answer12') }}</p>
                      </div>
                    </div>
                  </div>

                  <div class="panel style1 help_payment_process">
                    <h4 class="panel-title">
                      <a data-toggle="collapse" href="#tgg13" class="collapsed">{{ trans('explore-lang.help.quest13') }}</a>
                    </h4>
                    <div id="tgg13" class="panel-collapse collapse">
                      <div class="panel-content">
                          <p>{{ trans('explore-lang.help.answer13') }}</p>
                      </div>
                    </div>
                  </div>

                  <div class="panel style1 help_payment_process">
                    <h4 class="panel-title">
                      <a data-toggle="collapse" href="#tgg14" class="collapsed">{{ trans('explore-lang.help.quest14') }}</a>
                    </h4>
                    <div id="tgg14" class="panel-collapse collapse">
                      <div class="panel-content">
                          <p>{{ trans('explore-lang.help.answer14') }}</p>
                      </div>
                    </div>
                  </div>

                  <div class="panel style1 help_payment_process">
                    <h4 class="panel-title">
                      <a data-toggle="collapse" href="#tgg15" class="collapsed">{{ trans('explore-lang.help.quest15') }}</a>
                    </h4>
                    <div id="tgg15" class="panel-collapse collapse">
                      <div class="panel-content">
                          <p>{{ trans('explore-lang.help.answer15') }}</p>
                      </div>
                    </div>
                  </div>

                  <div class="panel style1 help_payment_process">
                    <h4 class="panel-title">
                      <a data-toggle="collapse" href="#tgg16" class="collapsed">{{ trans('explore-lang.help.quest16') }}</a>
                    </h4>
                    <div id="tgg16" class="panel-collapse collapse">
                      <div class="panel-content">
                          <p>{{ trans('explore-lang.help.answer16') }}</p>
                      </div>
                    </div>
                  </div>

                  <div class="panel style1 help_payment_process">
                    <h4 class="panel-title">
                      <a data-toggle="collapse" href="#tgg17" class="collapsed">{{ trans('explore-lang.help.quest17') }}</a>
                    </h4>
                    <div id="tgg17" class="panel-collapse collapse">
                      <div class="panel-content">
                          <p>{{ trans('explore-lang.help.answer17') }}</p>
                      </div>
                    </div>
                  </div>

                  <div class="panel style1 help_cancelation">
                    <h4 class="panel-title">
                      <a data-toggle="collapse" href="#tgg18" class="collapsed">{{ trans('explore-lang.help.quest18') }}</a>
                    </h4>
                    <div id="tgg18" class="panel-collapse collapse">
                      <div class="panel-content">
                          <p>{{ trans('explore-lang.help.answer18') }}</p>
                      </div>
                    </div>
                  </div>

                  <div class="panel style1 help_order_process">
                    <h4 class="panel-title">
                      <a data-toggle="collapse" href="#tgg19" class="collapsed">{{ trans('explore-lang.help.quest19') }}</a>
                    </h4>
                    <div id="tgg19" class="panel-collapse collapse">
                      <div class="panel-content">
                          <p>{{ trans('explore-lang.help.answer19') }}</p>
                      </div>
                    </div>
                  </div>

                  <div class="panel style1 help_order_process">
                    <h4 class="panel-title">
                      <a data-toggle="collapse" href="#tgg20" class="collapsed">{{ trans('explore-lang.help.quest20') }}</a>
                    </h4>
                    <div id="tgg20" class="panel-collapse collapse">
                      <div class="panel-content">
                          <p>{{ trans('explore-lang.help.answer20') }}</p>
                      </div>
                    </div>
                  </div>

                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
@stop

@section('scripts')
    <script>
      // Search Function
      tjq(document).on('submit', '#search', function(e){
        e.preventDefault();
      });
      tjq(document).on('change', '#search-word', function(e){
        tjq('.panel').removeClass('show');
        tjq('.panel').addClass('hide');
        var low = tjq(this).val();
        var word = low.toLowerCase();
        tjq('.panel-content p').each(function(i, e){
          var allWord = tjq(e).text();
          var lowercase = allWord.toLowerCase();
          var n = lowercase.search(word);
          if(n != -1){
            tjq(e).closest('.panel').removeClass('hide').addClass('show');
          }
        });
        tjq('.panel-title a').each(function(i, e){
          var allWord = tjq(e).text();
          var lowercase = allWord.toLowerCase();
          var n = lowercase.search(word);
          if(n != -1){
            tjq(e).closest('.panel').removeClass('hide').addClass('show');
          }
        })

        // Empty Result
        tjq('.question-list').find('.empty-result').remove();
        var show_empty = true
        for(i = 0; i < tjq('.panel').length; i++){
          if(tjq('.panel').eq(i).hasClass('show')){
            show_empty = false
          }
        }
        if(show_empty == true){
          tjq('.question-list').append('<div class="empty-result"> {{  trans("explore-lang.help.search_empty") }} </div>');
        }
      });
      
      // Categories Function
      tjq(document).on('click', '#help_all, #help_order_process, #help_coupon, #help_payment_process, #help_cancelation', function(e){
        tjq('.question-list').find('.empty-result').remove();
        tjq('#search-word').val('');
        var id = e.target.id;
        var panel = tjq('.panel');
        panel.removeClass('show');
        panel.addClass('hide');
        tjq('.filters-option>li').removeClass('actived');
        tjq(this).parent().addClass('actived');
        if(id == 'help_all'){
          panel.removeClass('hide').addClass('show');
        } else {
          tjq('.'+id+'').each(function(i, e){
            tjq(e).addClass('show');
          })
        }
      })
    </script>
@stop