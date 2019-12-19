<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Upload</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <style>
        .url-result {
            padding: 10px;
            background: lightgray;
            opacity: .5;
            position: relative;
        }

        .url-result span {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 5px;
            background-color: #3a3aff;
        }

        .url-result span.text-status {
            top: 50%;
            transform: translateY(-50%);
            right: 15px;
            bottom: auto;
            width: auto;
            height: auto;
            background-color: transparent;
            left: auto;
            font-weight: bold;
            font-size: 12px;
            display: none;
            animation: blink 1s infinite;
        }

        .url-result.url-status-PENDING {
            opacity: 1;
            color: #fff;
            background-color: #214154;
        }


        .url-result.url-status-DONE {
            background-color: #19be1e;
            color: #fff;
        }


        .url-result.url-status-ERROR {
            background-color: red;
            color: #fff;
        }

        .url-result.url-status-UPLOADING {
            background-color: orange;
            color: #000;
        }

        .url-result.url-status-UPLOADING span.text-status {
            display: inline-block;
        }

        .url-result.url-status-DONE span, .url-result.url-status-ERROR span, .url-result.url-status-UPLOADING span {
            display: none;
        }

        @keyframes blink {
            from, to {
                opacity: 0;
            }

            50% {
                opacity: 1;
            }
        }

        @-webkit-keyframes blink {
            from, to {
                opacity: 0;
            }

            50% {
                opacity: 1;
            }
        }

        @-moz-keyframes blink {
            from, to {
                opacity: 0;
            }

            50% {
                opacity: 1;
            }

        }
    </style>
</head>
<body>
<div class="container" class="p-5">
    <form id="upload-form" action="/upload" method="POST">
        <div class="form-group">
            <textarea class="form-control" name="urls" id="urls" rows="5" placeholder="type urls here"></textarea>
        </div>
        <div class="form-group">
            <button class="btn btn-primary btn-lg" id="submit" type="submit">Send</button>
        </div>
        <p id="form-status"></p>
    </form>

    <div id="results">
        @foreach($urls as $url)
            <div id="url-{{ $url->id }}" class="url-result url-status-{{ str_replace(' ', '_', $url->status) }}">
                {{ $url->name }} <span class="status-bar" id="url-{{$url->id}}-status" style="width: {{ $url->progress }}%"></span>
                <span class="text-status">Uploading to Gdrive</span>
            </div>
        @endforeach
    </div>
</div>
<script src="/js/app.js"></script>
<script>
    // $.ajaxSetup({
    //     headers: {
    //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //     }
    // });
    //
    // const form = $('#upload-form');
    //
    // form.submit(function (e) {
    //     e.preventDefault();
    //     const urls = form.find('textarea').val().replace(/\r/gi, '').split("\n").map(url => url.trim()).filter(url => url.length > 0);
    //     const action = form.attr('action');
    //
    //     $.post(action, {urls});
    // });
    // $(function () {
    //     console.log('ok');
    // })
</script>
</body>
</html>
