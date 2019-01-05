@extends('layouts.bst')
@section('content')
<!doctype html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>用户注册</title>
</head>
<body>
<form action="/register" method="post">
    {{csrf_field()}}{{--关闭防护机制--}}
    <table>
        <tr>
            <td>用户名：</td>
            <td><input type="text" name="name" class="form-control" placeholder="用户名"></td>
        </tr>
        <tr>
            <td>密码：</td>
            <td><input type="password" name="pwd" class="form-control" placeholder="密码"></td>
        </tr>
        <tr>
            <td>确认密码：</td>
            <td><input type="password" name="pwd2" class="form-control" placeholder="确认密码"></td>
        </tr>
        <tr>
            <td>年龄：</td>
            <td><input type="text" name="age" class="form-control" placeholder="年龄"></td>
        </tr>
        <tr>
            <td>邮箱：</td>
            <td><input type="email" name="email" class="form-control" placeholder="邮箱"></td>
        </tr>
        <tr>
            <td> </td>
            <td><input type="submit" value="注册" class="btn btn-default">
            </td>
        </tr>
    </table>
</form>
</body>
</html>
@endsection