{{--用户登录--}}
@extends('layouts.bst')
@section('content')
        <!doctype html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>考试用户登录</title>
</head>
<body>
<form action="/exam/dologin" method="post">
    {{csrf_field()}}
    <div class="form-group" style="width:400px;">
        姓名
        <input type="text" name="u_name"  placeholder="姓名">
    </div>
    <div class="form-group" style="width:400px;">
        密码
        <input type="password" name="u_pwd" placeholder="密码">
    </div>
    <button type="submit">登录</button>

</form>
</body>
</html>
@endsection