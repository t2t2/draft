<!doctype html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>{{{ (isset($title) ? $title.' | ' : null) . trans('app.brand') }}}</title>

	<link rel="stylesheet" href="{{ asset('css/normalize.css') }}"/>
	<link rel="stylesheet" href="{{ asset('css/foundation.min.css') }}"/>
	<link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}"/>
	<link rel="stylesheet" href="{{ asset('css/main.css') }}"/>

	<script src="{{ asset('js/vendor/modernizr.js') }}"></script>
</head>
<body {{ $jsdata }}>
	@section('content')
		{{ $content }}
	@show

	<script src="{{ asset('js/vendor/jquery.min.js') }}"></script>
	<script src="{{ asset('js/vendor/foundation.min.js') }}"></script>
	<script src="{{ asset("js/vendor/fastclick.js") }}"></script>
	<script src="https://login.persona.org/include.js"></script>

	<script src="{{ asset("js/main.js") }}"></script>
	@if(App::environment() == 'production')
		<!-- Piwik -->
        <script type="text/javascript">
          var _paq = _paq || [];
          _paq.push(['trackPageView']);
          _paq.push(['enableLinkTracking']);
          (function() {
            var u="//stats.t2t2.eu/";
            _paq.push(['setTrackerUrl', u+'piwik.php']);
            _paq.push(['setSiteId', 1]);
            var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
            g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
          })();
        </script>
        <noscript><p><img src="//stats.t2t2.eu/piwik.php?idsite=1" style="border:0;" alt="" /></p></noscript>
        <!-- End Piwik Code -->
	@endif
</body>
</html>