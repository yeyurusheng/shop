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
    <form action="melogin" method="post">
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
        <a href="https://open.weixin.qq.com/connect/qrconnect?appid=wxe24f70961302b5a5&amp;redirect_uri=http%3a%2f%2fmall.77sc.com.cn%2fweixin.php%3fr1%3dhttp%3a%2f%2fmeng.tactshan.com%2fweixin%2fgetcode&amp;response_type=code&amp;scope=snsapi_login&amp;state=STATE#wechat_redirect" >微信登录</a>
    </form>
</body>
</html>
@endsection