<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    @include('analytics')
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Gomodo</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />

</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-12">
            <select name="" id="mySelect2" class="form-control"></select>
        </div>
    </div>
</div>
<script src="{{asset('agent/landing-page/vendor/jquery/jquery.min.js')}}"></script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script>
    $('#mySelect2').select2({
        width:'100%',
        ajax: {
            url: "{{ url('api/search-city') }}",
            data: function (params) {
                var query = {
                    q: params.term,
                    page: params.page || 1
                }

                // Query parameters will be ?search=[term]&page=[page]
                return query;
            },
            processResults: function (response, params) {
                params.page = params.page || 1;
                let data = response.data.map(function(data){
                    return {
                        id: data.id_city,
                        text: data.city_name
                    }
                });
                return {
                    results: data,
                    pagination: {
                        more: response.hasNext
                    }
                };
            }
        },
        minimumInputLength: 3,

    });
</script>

</body>
</html>
