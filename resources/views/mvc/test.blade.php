@extends('layouts.bst')
@section('content')
    <form action="/login" method="post">
        <div class="form-group" style="width:400px;">
            <label for="exampleInputEmail1">姓名</label>
            <input type="text" class="form-control" id="exampleInputEmail1" placeholder="姓名">
        </div>
        <div class="form-group" style="width:400px;">
            <label for="exampleInputPassword1">密码</label>
            <input type="password" class="form-control" id="exampleInputPassword1" placeholder="密码">
        </div>

        <button type="submit" class="btn btn-default">登录</button>
    </form>
@endsection