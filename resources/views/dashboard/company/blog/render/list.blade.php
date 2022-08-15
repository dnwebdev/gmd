
<!-- Blog layout #3 with image -->
@if($blogs->count() > 0)
    @foreach ($blogs as $blog)
    <div class="card">
        <div class="card-body">
            <div class="card-img-actions mb-3">
                <img class="card-img img-fluid" src="{{ $blog->image_url }}" alt="">
                <div class="card-img-actions-overlay card-img">
                    <a href="{{ route('company.blog.detail', ['slug'=>$blog->slug]) }}" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round link-to-blog-detail">
                        <i class="icon-link"></i>
                    </a>
                </div>
            </div>

            <h5 class="font-weight-semibold mb-1">
                <a href="{{ route('company.blog.detail', ['slug'=>$blog->slug]) }}" class="text-default link-to-blog-detail">{{ $blog->title }}</a>
            </h5>

            <ul class="list-inline list-inline-dotted text-muted mb-3">
                <li class="list-inline-item">{{ trans('article.by') }} <a href="" class="text-muted">{{ $blog->author->admin_name }}</a></li>
                <li class="list-inline-item">{{ $blog->created_at->format('d M Y') }}</li>
            </ul>

            {!! Illuminate\Support\Str::limit(strip_tags($blog->description)) !!}
        </div>

        <div class="card-footer bg-transparent d-sm-flex justify-content-sm-between align-items-sm-center border-top-0 pt-0 pb-3">
            <a href="{{ route('company.blog.detail', ['slug'=>$blog->slug]) }}" class="btn btn-primary link-to-blog-detail">{{ trans('article.read_more') }} <i class="icon-arrow-right14 ml-2"></i></a>
        </div>
    </div>
    @endforeach
    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-3 mb-3" id="b2b_blog_pagination">
        {!! $blogs->links() !!}
    </div>
    <!-- /pagination -->
    @else
    {{-- empty state --}}
    <div class="card">
        <div class="card-body">
            <div class="card-img-actions text-center">
                {{  trans('product.search_empty') }}
            </div>
        </div>
    </div>
    @endif
    <div class="pagination-blog hide" data-total="{{$blogs->total()}}" data-current="{{$blogs->currentPage()}}"
        data-nextPage="{{$blogs->nextPageUrl()?$blogs->currentPage()+1:null}}">
    </div>
<!-- /blog layout #3 with image -->