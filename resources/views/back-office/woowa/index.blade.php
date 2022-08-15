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
                    {!! Form::model($current) !!}
                    @if($current->parent)
                        <h4>Parent Menu <a href="{{route('admin:bot.index',['id'=>$current->parent])}}"
                                           class="btn btn-sm btn-secondary">Go to Parent</a></h4>
                        <div class="bordered p-3 bg-secondary mb-3">

                            <div class="form-group">
                                {!! Form::label('slug') !!}
                                {!! Form::text('parent[slug]',null,['class'=>'form-control','readonly','style'=>'border: 1px solid #cecece']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('content') !!}
                                {!! Form::textarea('parent[content]',null,['class'=>'form-control','readonly','style'=>'border: 1px solid #cecece;resize:none;']) !!}
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
                        @forelse($current->keywords as $keywords)
                            @if ($loop->last)
                                <div class="row mb-2">
                                    <div class="col-10">
                                        {!! Form::text('keywords[]',$keywords->keyword,['class'=>'form-control']) !!}
                                    </div>
                                    <div class="col-1">
                                        <button type="button" class="btn btn-danger btn-delete"><i class="fa fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-10">
                                        {!! Form::text('keywords[]','',['class'=>'form-control']) !!}
                                    </div>
                                    <div class="col-1">
                                        <button type="button" class="btn btn-primary btn-add-keyword"><i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                </div>

                            @else
                                <div class="row mb-2">
                                    <div class="col-10">
                                        {!! Form::text('keywords[]',$keywords->keyword,['class'=>'form-control']) !!}
                                    </div>
                                    <div class="col-1">
                                        <button type="button" class="btn btn-danger btn-delete"><i class="fa fa-trash"></i>
                                        </button>
                                    </div>
                                </div>

                            @endif

                        @empty
                            <div class="row  mb-2">
                                <div class="col-10">
                                    {!! Form::text('keywords[]','',['class'=>'form-control']) !!}
                                </div>
                                <div class="col-1">
                                    <button type="button" class="btn btn-primary btn-add-keyword"><i class="fa fa-plus"></i></button>
                                </div>
                            </div>
                        @endforelse
                    </div>
                    <div class="form-group text-right">
                        <button class="btn btn-success btn-sm"><i class="fa fa-save"></i> Simpan</button>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
            <div class="row">
                <div class="col-12 py-5">
                    <hr>
                </div>
            </div>
            <div class="row ">
                <div class="col-12">
                    <h4>Childs Menu</h4>
                    <div class="table-responsive mt-4">
                        <table class="table" id="dt">
                            <thead>
                            <tr>
                                <th>
                                    No
                                </th>
                                <th>
                                    Menu Slug
                                </th>
                                <th>
                                    Content
                                </th>
                                <th>Keywords</th>
                                <th>
                                    Action
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($childs as $child)
                                <tr>
                                    <td>{{$loop->index+1}}</td>
                                    <td>{{$child->slug}}</td>
                                    <td>{{Illuminate\Support\Str::limit($child->content)}}</td>
                                    <td>{{$child->keywords->pluck('keyword')->implode(', ')}}</td>
                                    <td>
                                        <a href="{{route('admin:bot.index',['id'=>$child->id])}}"
                                           class="btn btn-sm btn-warning"><i class="fa fa-edit"></i></a>
                                        @if($child->slug!='contact-cs')
                                            <button class="btn btn-sm btn-danger btn-delete-child" data-id="{{$child->id}}"><i class="fa fa-trash"></i></button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" align="center">No Child</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="text-right">
                        <a href="{{route('admin:bot.add',['parent'=>$current->id])}}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Tambah Child</a>
                    </div>
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
                url:"{{route('admin:bot.update',['id'=>$current->id??null])}}",
                type:"POST",
                data:f.serialize(),
                dataType:"json",
                success:function (data) {
                    location.reload();
                },
                error:function (e) {
                    console.log(e)
                }
            })
        })
        $(document).on('click','.btn-delete-child', function () {
            let t =$(this);
            $.ajax({
                url:"{{route('admin:bot.delete')}}"+"?id="+t.data('id'),
                type:"DELETE",
                dataType:"json",
                success:function (data) {
                    location.reload();
                },
                error:function (e) {
                    console.log(e)
                }
            })
        });
    </script>
@stop