@extends('layouts.bst')
@section('content')
    <h1 style="color:hotpink" align="center">欢迎来到kongyi</h1>
    <table class="table table-striped">
        <tr>
            <th>订单号</th>
            <th>订单总价</th>
            <th>订单时间</th>
            <th>操作</th>
        </tr>
        @foreach($list as $v)
            <tr>
                <td>{{$v['order_sn']}}</td>
                <td>{{$v['order_amount']/100}}</td>
                <td>{{date('Y-m-d H:i:s'),$v['add_time']}}</td>
                <td>
                    <a href="/order/cancel/{{$v["o_id"]}}">取消订单</a>
                </td>
            </tr>
        @endforeach
    </table>
@endsection