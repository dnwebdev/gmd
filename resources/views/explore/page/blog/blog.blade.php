@extends('explore.layout')

@section('content')
    <section id="content">
        <div class="container">
            <div class="row">
                @include('explore.page.blog.sidebar-blog')
                <div id="main" class="col-sm-8 col-md-9">
                    <div class="page">
                        <span style="display: none;" class="entry-title page-title">Blog Left Sidebar</span>
                        <span style="display: none;" class="vcard"><span class="fn"><a rel="author"
                                                                                       title="Posts by admin" href="#">admin</a></span></span>
                        <span style="display:none;" class="updated">2014-06-20T13:35:34+00:00</span>
                        <div class="post-content">
                            <div class="blog-infinite">

                            </div>
                            <a id="load-more" class="uppercase full-width button btn-large sky-blue1">LOAD MORE
                                POSTS</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop

@section('scripts')
    <script>
        let page = 1;
        function render() {
            let keyword = tjq('input[name=q]').val();
            let data = {
                q: keyword,
                page: page,
            };
            if (page !== undefined || page !== '' || page !== null) {
                tjq.ajax({
                    url: "{{route('blog.render')}}",
                    type: "POST",
                    data: data,
                    dataType: 'html',
                    success: function (data) {
                        if (page === 1) {
                            tjq('.blog-infinite').html(data);

                            // Empty Result
                            if(tjq(document).find('.pagination-blog').attr('data-total') === 0){
                                tjq('.blog-infinite').append('<div class="empty-result"> {{  trans("explore-lang.blog.search_empty") }} </div>');
                            }
                        } else {
                            tjq('.blog-infinite').append(data);
                        }
                        if (tjq(document).find('.pagination-blog').length > 0) {
                            // tjq('#total-blog').text(formatNumber(tjq(document).find('.pagination-blog').data('total')));
                            if (tjq(document).find('.pagination-blog').attr('data-nextpage') !== '') {
                                page = tjq(document).find('.pagination-blog').attr('data-nextpage');
                            } else {
                                page = null
                            }

                        } else {
                            page = null

                        }
                        tjq(document).find('.pagination-blog').remove();
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

        tjq(document).on('click', '#load-more', function () {
            render(page)
        });

        tjq(document).on('submit', '#search-blog', function (e) {
            e.preventDefault();
            page = 1;
            render(page);
        });

    </script>
@stop