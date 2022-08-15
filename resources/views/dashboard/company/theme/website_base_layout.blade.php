@extends('dashboard.company.profile.integration_base_layout')

@section('indicator_website')
  active
@endsection

@section('breadcrumb')
	@yield('tab_breadcrumb')
@endsection


@section('content')
@yield('tab_content')
@endsection

@section('additionalScript')
<!-- Library -->
<script type="text/javascript" src="{{ asset('materialize/js/plugins/jquery-1.11.2.min.js') }}"></script>
		<script type="text/javascript" src="{{ asset('dest-operator/lib/js/bootstrap.min.js') }}"></script>    

		<!-- Plugin -->
		<script type="text/javascript" src="{{ asset('materialize/js/materialize.js') }}"></script>
		<script type="text/javascript" src="{{ asset('materialize/js/datedropper.js') }}"></script>
		<script type="text/javascript" src="{{ asset('dest-operator/lib/js/chosen.jquery.min.js') }}"></script>
		<script type="text/javascript" src="{{ asset('materialize/js/plugins/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
		
		<!-- Custom -->
		<script type="text/javascript" src="{{ asset('materialize/js/custom-script.js') }}"></script>
@endsection