@extends('layouts.bst')
@section('content')
    <h1 style="color:darkgreen" align="center">后台登录状态</h1>
    <table class="table table-striped">
        <tr>
            <th>用户名</th>
            <th>密码</th>
            <th>邮箱</th>
            <th>年龄</th>
            <th>登录状态</th>
        </tr>
        @foreach($list as $v)
            <tr>
                <td>{{$v['u_name']}}</td>
                <td>{{$v['u_pwd']}}</td>
                <td>{{$v['u_email']}}</td>
                <td>{{$v['u_age']}}</td>
                <td>
                    @if($v['status']==1)
                        APP登录
                    @elseif($v['status']==2)
                        电脑登录
                    @elseif($v['status']==3)
                        ios登录
                    @elseif($v['status']==0)
                        没有登录
                    @endif
                </td>
            </tr>
        @endforeach
    </table>
@endsection