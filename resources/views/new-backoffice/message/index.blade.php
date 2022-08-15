@extends('new-backoffice.header')
@section('content')
  <h3 class="mt-3">Pesan</h3>
{{--  <div class="card mt-3" id="messages">--}}
{{--    <div class="card-body">--}}
{{--      <ul class="nav nav-tabs nav-tabs-highlight nav-justified">--}}
{{--        <li class="nav-item"><a href="#inbox" class="nav-link active" data-toggle="tab">Pesan Masuk</a></li>--}}
{{--        <li class="nav-item"><a href="#draft" class="nav-link" data-toggle="tab">Konsep</a></li>--}}
{{--        <li class="nav-item"><a href="#sent" class="nav-link" data-toggle="tab">Terkirim</a></li>--}}
{{--      </ul>--}}
{{--      <div class="tab-content">--}}
{{--        <div class="tab-pane fade show active" id="inbox">--}}
{{--          @include('new-backoffice.partial.coming_soon')--}}
{{--        </div>--}}
{{--        <div class="tab-pane fade" id="draft">--}}
{{--          @include('new-backoffice.partial.coming_soon')--}}
{{--        </div>--}}
{{--        <div class="tab-pane fade" id="sent">--}}
{{--          @include('new-backoffice.partial.coming_soon')--}}
{{--        </div>--}}
{{--      </div>--}}
{{--    </div>--}}
{{--  </div>--}}
  @include('new-backoffice.partial.coming_soon')
@endsection