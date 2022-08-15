<!-- Post -->
<div class="card">
    <div class="card-body blob-b2b-detail">
        <div class="mb-4">
            <div class="mb-3 text-center">
                <a href="#" class="d-inline-block">
                    <img src="{{ $blog->image_url }}" class="img-fluid" alt="">
                </a>
            </div>

            <h4 class="font-weight-semibold mb-1">
                <a href="#" class="text-default">{{ $blog->title }}</a>
            </h4>

            <ul class="list-inline list-inline-dotted text-muted mb-3">
                <li class="list-inline-item">{{ trans('article.by') }} <a href="#" class="text-muted">{{ $blog->author->admin_name }}</a></li>
                <li class="list-inline-item">{{ $blog->created_at->format('d M Y') }}</li>
            </ul>

            <div class="mb-3">
                    {!! $blog->description !!}
            </div>
        </div>

    </div>
</div>
<button type="button" id="backToBlog" class="btn btn-primary float-right">{{ trans('article.back') }}</button>
<!-- /post -->