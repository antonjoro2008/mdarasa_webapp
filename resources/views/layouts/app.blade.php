<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/svg+xml" href="/favicon.png" />
    <title>mDarasa - Revolutionalizing Learning</title>

    <!-- <link href="https://vjs.zencdn.net/8.6.1/video-js.css" rel="stylesheet" /> -->

    <link rel="stylesheet" href="{{ asset('/css/demo.css') }}" />
    <link href="{{ asset('/css/style.css?v=32') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.css">
    <link href="{{ asset('/css/jquery.datetimepicker.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css
">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.6/dist/sweetalert2.all.min.js"></script>
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css'>
</head>

<body class="antialiased">
    <div id="blurPage" class=""></div>
    <div id="app">
        <main>
            @yield('content')
        </main>
        @include('footer')

        <!-- Scripts -->

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous">
            </script>
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
        <script src="/js/jquery.datetimepicker.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js">
        </script>
        <script src="/js/ckeditor/ckeditor.js"></script>
        <script src="/js/mdarasa.js?v=16"></script>

        <script>
            toastr.options.closeButton = true;
            toastr.options.showMethod = 'slideDown';
            toastr.options.hideMethod = 'slideUp';
            toastr.options.closeMethod = 'slideUp';
            toastr.options.preventDuplicates = true;

            @if(Session::has('error'))
                toastr.error("{{ Session::get('error') }}", '', {
                    timeOut: 7000
                });
            @endif;
            @if(Session::has('success'))
                toastr.success("{{ Session::get('success') }}", '', {
                    timeOut: 4000,
                    iconClass: 'toast-success',
                });
                var audio = new Audio('/sounds/success.mp3');
                audio.play();
            @endif;

            CKEDITOR.replace('highlights');
            CKEDITOR.replace('description');
            CKEDITOR.replace('topicDescription');
            CKEDITOR.replace('subtopicDescription');
            CKEDITOR.replace('profileSummary');
            CKEDITOR.replace('institutionProfileSummary');
            CKEDITOR.replace('question');
        </script>
    </div>
    <style>
        #toast-container>.toast-error {
            background: #A20 !important;
            color: #FFF !important;
            background-image: none !important;
        }

        #toast-container>.toast-success {
            background: #198754 !important;
            color: #FFF !important;
            background-image: none !important;
        }
    </style>

    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js')
                .then(function (reg) {
                    console.log('ServiceWorker registration successful with scope: ', reg.scope);

                    if (reg.installing) {
                        console.log('SW installing');
                    } else if (reg.waiting) {
                        console.log('SW waiting');
                    } else if (reg.active) {
                        console.log('SW activated');
                    }
                }).catch(function (error) {
                    // registration failed
                    console.log('Registration failed with ' + error);
                });
        }
    </script>
    <!-- <script src="https://vjs.zencdn.net/8.6.1/video.min.js"></script> -->
    <script src="https://cdn.plyr.io/3.6.8/plyr.js"></script>
    <script src="https://cdn.plyr.io/3.7.8/demo.js" crossorigin="anonymous"></script>
    <script>
        // Initialize the Plyr for both video elements
        const players = Array.from(document.querySelectorAll('.plyr-video')).map(videoElement => new Plyr(videoElement));
    </script>

    <script>
        // var player = videojs('my-video')
    </script>
</body>

</html>