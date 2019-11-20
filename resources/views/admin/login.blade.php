<!-- 后台登录 -->
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name = "csrf-token" content = "{{csrf_token()}}}}">
    <title> 管理后台</title>
    <link rel="stylesheet" href="{{ mix('admin/css/app.css')}}">
    <link rel="icon" href="{{asset('/admin/image/logo.png')}}" type="image/x-icon" />
    <link rel="shortcut icon" href="{{asset('/admin/image/logo.png')}}" type="image/x-icon" />
</head>
<body>
<div id="app">

</div>
<script>window.Laravel = {'csrfToken' : '{{csrf_token()}}' , 'apiUrl': '{{route('admin')}}' };</script>
<script src="{{ mix('admin/js/app.js') }}"></script>
</body>
</html>
