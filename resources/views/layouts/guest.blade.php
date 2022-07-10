<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Laravel') }}</title>

  <meta name="description" content="">
  <meta name="author" content="pixelcave">
  <meta name="robots" content="noindex, nofollow">

  <!-- Open Graph Meta -->
  <meta property="og:title" content="Ground Force Trucking">
  <meta property="og:site_name" content="Ground Force Trucking">
  <meta property="og:description" content="Ground Force Trucking">
  <meta property="og:type" content="website">
  <meta property="og:url" content="">
  <meta property="og:image" content="">

  <!-- Icons -->
  <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
  <link rel="shortcut icon" href="{{ asset('/media/favicons/favicon.jpg') }}">
  <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('/media/favicons/favicon-192x192.jpg') }}">
  <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('/media/favicons/apple-touch-icon-180x180.jpg') }}">
  <!-- END Icons -->

  <!-- Stylesheets -->
  <!-- Fonts and OneUI framework -->
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400italic,600,700%7COpen+Sans:300,400,400italic,600,700">

  <!-- Styles -->
  <link rel="stylesheet" href="{{ mix('css/app.css') }}">

  <!-- Scripts -->
  <script src="{{ mix('js/core.js') }}"></script>
  <script src="{{ mix('js/auth.js') }}"></script>
</head>

<body>
  <div id="page-container">
    {{ $slot }}
  </div>

  <!-- Terms Modal -->
  <div class="modal fade" id="one-signup-terms" tabindex="-1" role="dialog" aria-labelledby="one-signup-terms"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-popout" role="document">
      <div class="modal-content">
        <div class="block block-themed block-transparent mb-0">
          <div class="block-header bg-primary-dark">
            <h3 class="block-title">Terms &amp; Conditions</h3>
            <div class="block-options">
              <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                <i class="fa fa-fw fa-times"></i>
              </button>
            </div>
          </div>
          <div class="block-content">
            <p>Dolor posuere proin blandit accumsan senectus netus nullam curae, ornare laoreet adipiscing luctus mauris
              adipiscing pretium eget fermentum, tristique lobortis est ut metus lobortis tortor tincidunt himenaeos
              habitant quis dictumst proin odio sagittis purus mi, nec taciti vestibulum quis in sit varius lorem sit
              metus mi.</p>
            <p>Dolor posuere proin blandit accumsan senectus netus nullam curae, ornare laoreet adipiscing luctus mauris
              adipiscing pretium eget fermentum, tristique lobortis est ut metus lobortis tortor tincidunt himenaeos
              habitant quis dictumst proin odio sagittis purus mi, nec taciti vestibulum quis in sit varius lorem sit
              metus mi.</p>
            <p>Dolor posuere proin blandit accumsan senectus netus nullam curae, ornare laoreet adipiscing luctus mauris
              adipiscing pretium eget fermentum, tristique lobortis est ut metus lobortis tortor tincidunt himenaeos
              habitant quis dictumst proin odio sagittis purus mi, nec taciti vestibulum quis in sit varius lorem sit
              metus mi.</p>
            <p>Dolor posuere proin blandit accumsan senectus netus nullam curae, ornare laoreet adipiscing luctus mauris
              adipiscing pretium eget fermentum, tristique lobortis est ut metus lobortis tortor tincidunt himenaeos
              habitant quis dictumst proin odio sagittis purus mi, nec taciti vestibulum quis in sit varius lorem sit
              metus mi.</p>
            <p>Dolor posuere proin blandit accumsan senectus netus nullam curae, ornare laoreet adipiscing luctus mauris
              adipiscing pretium eget fermentum, tristique lobortis est ut metus lobortis tortor tincidunt himenaeos
              habitant quis dictumst proin odio sagittis purus mi, nec taciti vestibulum quis in sit varius lorem sit
              metus mi.</p>
          </div>
          <div class="block-content block-content-full text-right border-top">
            <button type="button" class="btn btn-sm btn-link mr-2" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-sm btn-primary" data-dismiss="modal">I Agree</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- END Terms Modal -->
</body>

</html>
