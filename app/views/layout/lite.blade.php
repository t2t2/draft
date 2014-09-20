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

</body>
</html>