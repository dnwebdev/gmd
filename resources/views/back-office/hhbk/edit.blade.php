@extends('back-office.layout.index')
@section('subheader')
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title ">Edit HHBK</h3>
        </div>

    </div>
@stop
@section('content')

    <div class="m-portlet">
        <div class="m-portlet__body  table-responsive">
            <div class="row ">
                <div class="col-12">
                    {!! Form::model($hhbk,['url'=>route('admin:hhbk.update')]) !!}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('hhbk Id','HHBK Id') !!}
                                {!! Form::text('id',null,['class'=>'form-control','readonly']) !!}
                                @if($errors->has('id'))
                                    <label for="" class="error">{{$errors->first('id')}}</label>
                                @endif
                            </div>
                            <div class="form-group">
                                {!! Form::label('product_name','Product Name') !!}
                                {!! Form::text('product_name',null,['class'=>'form-control']) !!}
                                @if($errors->has('product_name'))
                                    <label for="" class="error">{{$errors->first('product_name')}}</label>
                                @endif
                            </div>
                            <div class="form-group">
                                {!! Form::label('city','Kota') !!}
                                {!! Form::text('city',null,['class'=>'form-control']) !!}
                                @if($errors->has('city'))
                                    <label for="" class="error">{{$errors->first('city')}}</label>
                                @endif
                            </div>
                            <div class="form-group">
                                {!! Form::label('domain','Domain Gomodo (if comes form Gomodo Provider)') !!}
                                {!! Form::text('domain',$hhbk->company?$hhbk->company->domain_memoria:null,['class'=>'form-control']) !!}
                                @if($errors->has('domain'))
                                    <label for="" class="error">{{$errors->first('domain')}}</label>
                                @endif
                            </div>
                            <div class="form-group">
                                {!! Form::label('description','Description') !!}
                                {!! Form::textarea('product_description',null,['class'=>'form-control']) !!}
                                @if($errors->has('product_description'))
                                    <label for="" class="error">{{$errors->first('product_description')}}</label>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">

                            <div class="form-group">
                                {!! Form::label('detail','Detail') !!}
                                {!! Form::textarea('product_detail',null,['class'=>'form-control','rows'=>25]) !!}
                                @if($errors->has('product_detail'))
                                    <label for="" class="error">{{$errors->first('product_detail')}}</label>
                                @endif
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 mt-3 text-right">
                            <button class="btn btn-sm btn-primary">Save</button>
                        </div>
                    </div>
                </div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>
    </div>
@stop

@section('scripts')

@stop