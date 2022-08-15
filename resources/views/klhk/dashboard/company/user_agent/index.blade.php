@extends('dashboard.company.base_layout')

@section('title', 'User Agent')

@section('sidebar')
    
@endsection

@section('content')

	@foreach ($user_agent as $row)
	    {{ $row->first_name }}'-'
	    {{ $row->company->id_company }}  : 
	    {{ $row->company->company_name }} <br>
	@endforeach
@endsection
