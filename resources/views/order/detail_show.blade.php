@extends('layouts.bst')
@section('content')
    <h1 style="color:darkgreen" align="center">欢迎来到商品详情页</h1>
    <table class="table table-striped">
        <tr>
            <th>订单号</th>
            <th>商品名称</th>
            <th>商品价格</th>
            <th>购买数量</th>
            <th>小计</th>
            <th>订单添加时间</th>
            <th>购买状态</th>
            <th>操作</th>
        </tr>
        @foreach($list as $v)
            <tr>
                <td>{{$v['order_sn']}}</td>
                <td>{{$v['g_name']}}</td>
                <td>{{$v['g_price']/100}}</td>
                <td>{{$v['buy_number']}}</td>
                <td>{{$v['g_price']*$v['buy_number']/100}}</td>
                <td>{{date('Y-m-d H:i:s',$v['add_time'])}}</td>
                <td>
                    @if($v['status']==1)
                        待支付
                        @elseif($v['status']==2)
                        已支付
                        @elseif($v['status']==3)
                        已取消
                        @endif
                </td>
                <td>
                    <a href="/goods/show/{{$v['g_id']}}">商品详情</a>
                </td>
            </tr>
        @endforeach
    </table>
@endsection