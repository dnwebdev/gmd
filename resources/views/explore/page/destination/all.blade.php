@extends('explore.layout')

@section('content')
    <section id="content">
        <div class="container">
            <div id="main">
                <div class="lds-dual-ring display-none"></div>
                <div class="tour-packages row add-clearfix image-box">

                </div>
                <a id="load-more" class="button uppercase full-width btn-large box sky-blue1 loading-button">{{ trans('explore-lang.search.more_packages') }}</a>
            </div>
        </div>
    </section>

@stop

@section('scripts')
    <script>

        let page = 1;

        function render(){
        let data = {
          page: page,
        };
        loadingStart('#main');
        if (page !== undefined || page !== '' || page !== null) {
            tjq.ajax({
                url: "{{route('explore.all-destination.render')}}",
                type: "POST",
                data: data,
                dataType: 'html',
                success: function(data){
                tjq('#main .lds-dual-ring').show();
                loadingEnd('#main','{{ trans('explore-lang.search.more_packages') }}');
                if (page === 1) {
                    tjq('.tour-packages').html(data)
                } else {
                    tjq('.tour-packages').append(data);
                }
                if (tjq(document).find('.pagination-product').length > 0) {
                
                    if (tjq(document).find('.pagination-product').attr('data-nextpage') !== '') {
                    page = tjq(document).find('.pagination-product').attr('data-nextpage');
                    } else {
                    page = null
                    }

                } else {
                    page = null
                }
                    tjq('#main .lds-dual-ring').hide();
                    tjq(document).find('.pagination-product').remove();
                if (page === null) {
                    tjq('#load-more').remove();
                }

                }
            })
        } else {
            tjq('#load-more').remove();
        }
      }

      render();

      tjq(document).on('click', '#load-more', function(){
        render(page);
      });
    </script>
@endsection
