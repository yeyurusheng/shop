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
    <title>用户登录</title>
</head>
<body>
<form action="/exam/login" method="post">
    {{csrf_field()}}
    <div class="form-group" style="width:400px;">
        <label for="exampleInputEmail1">姓名</label>
        <input type="text" name="name" class="form-control" id="exampleInputEmail1" placeholder="姓名">
    </div>
    <div class="form-group" style="width:400px;">
        <label for="exampleInputPassword1">密码</label>
        <input type="password" name="pwd" class="form-control" id="exampleInputPassword1" placeholder="密码">
    </div>
    <button type="submit" class="btn btn-default">登录</button>
</form>
</body>
</html>
@endsection