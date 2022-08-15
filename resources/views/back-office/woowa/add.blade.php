@extends('back-office.layout.index')
@section('subheader')
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title ">BOT Woowa</h3>
        </div>

    </div>
@stop
@section('content')
    <div class="m-portlet">
        <div class="m-portlet__body  ">
            <div class="row">
                <div class="col-12 mb-3">
                    {!! Form::open() !!}
                    @if($parent)
                        <h4>Parent Menu <a href="{{route('admin:bot.index',['id'=>$parent])}}"
                                           class="btn btn-sm btn-secondary">Go to Parent</a></h4>
                        <div class="bordered p-3 bg-secondary mb-3">

                            <div class="form-group">
                                {!! Form::label('slug') !!}
                                {!! Form::text('parent[slug]',$parent->slug,['class'=>'form-control','readonly','style'=>'border: 1px solid #cecece']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('content') !!}
                                {!! Form::textarea('parent[content]',$parent->content,['class'=>'form-control','readonly','style'=>'border: 1px solid #cecece;resize:none;']) !!}
                            </div>
                        </div>
                    @endif
                    <h4>Current Menu</h4>
                    <div class="form-group">
                        {!! Form::label('slug') !!}
                        {!! Form::text('slug',null,['class'=>'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('content') !!}
                        {!! Form::textarea('content',null,['class'=>'form-control','rows'=>20]) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('keywords') !!}
                            <div class="row  mb-2">
                                <div class="col-10">
                                    {!! Form::text('keywords[]',null,['class'=>'form-control']) !!}
                                </div>
                                <div class="col-1">
                                    <button type="button" class="btn btn-primary btn-add-keyword"><i class="fa fa-plus"></i></button>
                                </div>
                            </div>
                    </div>
                    <div class="form-group text-right">
                        <button class="btn btn-success btn-sm"><i class="fa fa-save"></i> Simpan</button>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>

        </div>
    </div>
@stop
@section('scripts')
    <script>
        $(document).on('click','.btn-delete', function () {
            let parent = $(this).closest('.row');
            parent.fadeOut('1000');
            setTimeout(function () {
                parent.remove();
            },1000)
        })
        $(document).on('click','.btn-add-keyword', function () {
            let parent = $(this).closest('.row');
            if (parent.find('input').val()===''){
                parent.find('label.error').remove();
                parent.find('input').parent().append('<label class="error">Field is required</label>')
            }else{
                let clone = parent.clone();
                clone.find('button').removeClass('btn-add-keyword btn-primary').addClass('btn-delete btn-danger').find('i').removeClass('fa-plus').addClass('fa-trash')
                clone.css('display','none');
                parent.find('input').val('');
                clone.insertBefore(parent);
                clone.fadeIn('slow')

            }
        })
        $(document).on('submit','form', function (e) {
            e.preventDefault();
            const f = $(this)
            $.ajax({
                url:"{{route('admin:bot.save',['parent'=>$parent->id??null])}}",
                type:"POST",
                data:f.serialize(),
                dataType:"json",
                success:function (data) {
                    window.location.href="{{route('admin:bot.index',['id'=>$parent->id])}}"
                },
                error:function (e) {
                    console.log(e)
                }
            })
        })
    </script>
@stop