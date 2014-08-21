<!doctype html>
<html>
<head>
	<meta charset="UTF-8">
	<title>{{{ (isset($title) ? $title.' | ' : null) . trans('app.brand') }}}</title>

	<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}"/>
	<link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}"/>
	<link rel="stylesheet" href="{{ asset('css/main.css') }}"/>
</head>
<body {{ $jsdata }}>
	@section('content')
		{{ $content }}
	@show

	<script src="{{ asset('js/vendor/jquery.min.js') }}"></script>
	<script src="{{ asset('js/vendor/bootstrap.min.js') }}"></script>
	<script src="https://login.persona.org/include.js"></script>

	<script src="{{ asset("js/main.js") }}"></script>

</body>
</html>