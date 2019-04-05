{{--用户申请--}}
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
<form action="/secret/doapply" method="post">
    {{csrf_field()}}
    <div class="form-group" style="width:400px;">
        姓名
        <input type="text" name="u_name"  >
    </div>
    <div class="form-group" style="width:400px;">
        身份证号
        <input type="text" name="card_id" >
    </div>
    <div class="form-group" style="width:400px;">
        身份证照片
        <input type="file" name="photos"  >
    </div><div class="form-group" style="width:400px;">
        接口用途
        <input type="text" name="purpose" >
    </div>

    <input type="hidden" name="status" value="2">
    <button type="submit">提交申请</button>

</form>
</body>
</html>
@endsection