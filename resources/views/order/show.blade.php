@extends('layouts.bst')
@section('content')
    <h1 style="color:hotpink" align="center">欢迎来到订单页</h1>
    <table class="table table-striped">
        <tr>
            <th>订单号</th>
            <th>订单总价</th>
            <th>订单时间</th>
            <th>操作</th>
        </tr>
        @foreach($list as $v)
            @if($v['status']!=3)
            <tr>
                <td>{{$v['order_sn']}}</td>
                <td>{{$v['order_amount']/100}}</td>
                <td>{{date('Y-m-d H:i:s'),$v['add_time']}}</td>
                <td>
                    <a href="/order/cancel/{{$v["order_sn"]}}">取消订单</a>
                    <a href="/pay/show/{{$v["order_sn"]}}">订单支付</a>
                </td>
            </tr>
            @endif
        @endforeach
    </table>
@endsection