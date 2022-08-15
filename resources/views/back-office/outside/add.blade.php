@extends('back-office.layout.index')
@section('subheader')
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title ">Add HHBK Distribution</h3>
        </div>

    </div>
@stop
@section('content')

    <div class="m-portlet">
        <div class="m-portlet__body  table-responsive">
            <div class="row ">
                <div class="col-12">
                    {!! Form::open(['url'=>route('admin:hhbk-distribution.save')]) !!}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('hhbk Id','HHBK Product') !!}
                                {!! Form::select('hhbk_id',\App\Models\Hhbk::all()->pluck('product_name','id'),null,['class'=>'form-control','rows'=>25]) !!}
                                @if($errors->has('id'))
                                    <label for="" class="error">{{$errors->first('id')}}</label>
                                @endif
                            </div>
                            <div class="form-group">
                                {!! Form::label('amount','Harga') !!}
                                {!! Form::number('amount',null,['class'=>'form-control']) !!}
                                @if($errors->has('amount'))
                                    <label for="" class="error">{{$errors->first('amount')}}</label>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">

                            <div class="form-group">
                                {!! Form::label('client','CLient (Channel Distribusi)') !!}
                                {!! Form::select('client',['tokopedia'=>'Tokopedia','shopee'=>'Shopee'],null,['class'=>'form-control','rows'=>25]) !!}
                                @if($errors->has('client'))
                                    <label for="" class="error">{{$errors->first('client')}}</label>
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