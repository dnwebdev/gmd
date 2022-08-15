@extends('dashboard.company.base_layout')
@section('breadcrumb')
	@yield('tab_breadcrumb')
@endsection


@section('content')
<div class="row">
    <div class="col s12">
    @yield('tab_content')
    </div>
</div>
@endsection

@section('additionalScript')

@endsection