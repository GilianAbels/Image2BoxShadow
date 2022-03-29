<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>{{ config('app.name') }}</title>
    <style>
        .btn {
            width: 100%;
        }

    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="container">
            <div class="row">
                <div class="col-12 card  shadow-sm">
                    <div class="row">
                        <div class="col-6">
                            <div class="p-5">
                                <div id="uploadImage">
                                    <h4>Image upload</h4>
                                    <div class="mb-3">
                                        <label for="file" class="form-label">Upload a file to get started.</label>
                                        <input class="form-control" type="file" id="file">
                                    </div>

                                    <label for="qualitySlider" class="form-label">Pixel kwaliteit</label>
                                    <input type="range" class="form-range" min="1" step="1" max="25" id="qualitySlider">
                                    <button type="submit" id="uploadFile" class="btn btn-primary">Upload</button>
                                </div>
                            </div>

                        </div>
                        <div class="col-12">
                            <div id="result" class="p-5">
                                <h5 class="processStatus"></h5>
                                <div class="result">
                                </div>

                                <textarea class="form-control" id="cssCode" rows="25" style="display:none;">

                                </textarea>
                                <br />
                                <br />
                                <br />

                                Made with 🤣 &amp; 💚 by Gilian Abels
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"
                integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
                integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
        </script>
        <script>
            function sendFile() {}

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $(document).ready(function() {
                var DisabledInput = false;

                $("#uploadFile").click(function() {
                    if (DisabledInput !== true) {
                        var fd = new FormData();
                        var files = $('#file')[0].files;

                        // Check file selected or not
                        if (files.length > 0) {
                            fd.append('image', files[0]);
                            fd.append('quality', $('#qualitySlider').val());
                            $.ajax({
                                url: '{{ route('uploadImage') }}',
                                type: 'post',
                                data: fd,
                                contentType: false,
                                processData: false,
                                beforeSend: function() {
                                    DisabledInput = true;
                                    $("#result .result").html('');
                                    $("#uploadImage").slideUp();
                                    $(".processStatus").text('We are processing the image ⛳');
                                    $("#cssCode").hide();
                                },
                                success: function(response) {

                                    response = JSON.parse(response);
                                    let css = 'width:' + response.quality +'px; height:' + response.quality +
                                        'px;background:white;' +
                                        response.boxShadow + '';

                                    $(".processStatus").text('We finished');
                                    $("#result .result").html(
                                        '<div class="card shadow-lg p-4"  style="width:' + ( response.width + 55 ) + 'px; height:' + (response.height + 55) +'px;"><div style="'+css+'"></div></div>');
                                    $("#cssCode").val(css);
                                    $("#cssCode").fadeIn(500);
                                    console.log(response);
                                    DisabledInput = false;
                                    $("#uploadImage").slideDown();
                                },
                                error: function(response) {
                                    console.log(response.responseJSON.message);
                                    alert(response.responseJSON.message);
                                }
                            });
                        } else {
                            alert("Please select a file.");
                        }
                    }
                });
            });
        </script>
</body>

</html>
