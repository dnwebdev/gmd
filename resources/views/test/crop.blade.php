<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title></head>
<body>
{!! Form::open(['files'=>true]) !!}
<div class="container mt-5">
    <div class="row">
        <div class="col-12">
            <input type="file" class="form-control" name="image">
            {!! Form::hidden('left',0) !!}
            {!! Form::hidden('top',0) !!}
            {!! Form::hidden('width',200) !!}
            {!! Form::hidden('height',200) !!}
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <button class="btn btn-primary">Save</button>
        </div>
    </div>
</div>
{!! Form::close() !!}
</body>
</html>