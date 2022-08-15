<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropper/2.3.3/cropper.css">

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</head>

<body>
<div class="row mb-3">
    <div class="col-12">
        <div>
            <a class="gomodoEmbed" data-url="https://menclekadventure.mygomodo.com/product/detail/SKU618519015710631925148"></a>
            <script src="http://gomodo.test/gomodo-widget.js"></script>
        </div>

    </div>
</div>
<p>Initial</p>
<div class="row">
    <div class="col-12">
        <img src="{{asset('storage/uploads/test/test-file.jpeg')}}" alt="" class="img-fluid">
    </div>
</div>
<p>
    <!-- Below are a series of inputs which allow file selection and interaction with the cropper api -->
    <input type="file" id="fileInput" accept="image/*"/>

    <input type="button" id="btnRestore" value="Restore"/>
</p>

{!! Form::open() !!}
<div id="result">
    <textarea name="image"></textarea>
</div>


<div class="row">
    <div class="col-12 text-center">
        <button class="btn btn-primary">Save</button>
    </div>
</div>
{!! Form::close() !!}
<br/>

<div class="modal" id="myModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Modal Heading</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div>
                    <canvas id="canvas">
                        Your browser does not support the HTML5 canvas element.
                    </canvas>
                </div>
                <input type="button" id="btnCrop" value="Crop"/>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/cropper/2.3.3/cropper.js"></script>
<script>
    var canvas = $("#canvas"),
        context = canvas.get(0).getContext("2d"),
        $result = $('#result');

    $(document).on('change', '#fileInput', function (e) {
        let modal = $('.modal#myModal');
        modal.modal();
        if (this.files && this.files[0]) {
            if (this.files[0].type.match(/^image\//)) {
                var reader = new FileReader();
                reader.onload = function (evt) {
                    canvas.cropper('destroy')
                    var img = new Image();
                    img.onload = function () {
                        context.canvas.height = img.height;
                        context.canvas.width = img.width;
                        context.drawImage(img, 0, 0);
                        var cropper = canvas.cropper({
                            viewMode: 2,
                            aspectRatio: 16 / 9,
                            cropBoxResizable:false,
                            autoCropArea:1,

                        });
                        $('#btnCrop').click(function () {
                            let dataCropper = cropper.data('cropper');
                            console.log(dataCropper);
                            // Get a string base 64 data url
                            var croppedImageDataURL = canvas.cropper('getCroppedCanvas').toDataURL("image/png");
                            $result.append($('<img>').attr('src', croppedImageDataURL));
                            $result.find('textarea').val(croppedImageDataURL);
                        });
                        $('#btnRestore').click(function () {
                            canvas.cropper('reset');
                            $result.empty();
                        });
                    };
                    img.src = evt.target.result;
                };
                reader.readAsDataURL(this.files[0]);
            } else {
                alert("Invalid file type! Please select an image file.");
            }
        } else {
            alert('No file(s) selected.');
        }
    })
</script>
</body>
</html>