<!-- 后台登录 -->
<!DOCTYPE html>
<html lang="zh_CN">
<head>
    <meta charset="UTF-8">
    <meta name = "csrf-token" content = "{{csrf_token()}}}}">
    <title> 管理后台</title>
    <link rel="stylesheet" href="{{ mix('admin/css/app.css')}}">
</head>
<body>
<div id="app">
    <div class="pit-loading-box">
        <img src="{{ asset('admin/image/fly.gif') }}" class="pit-loading-img" alt=""/>
    </div>
</div>
<script>window.Laravel = {'csrfToken' : '{{csrf_token()}}' , 'apiUrl': '{{route('admin')}}' };</script>
<script src="{{ mix('admin/js/app.js') }}"></script>
</body>
</html>
