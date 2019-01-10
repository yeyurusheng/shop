{{-- 购物车 --}}
@extends('layouts.bst')

@section('content')
    <table class="table table-striped">
            <tr>
                <th>商品id</th>
                <th>商品名称</th>
                <th>商品价格</th>
                <th>添加时间</th>
                <th>操作</th>
            </tr>
            @foreach($list as $v)
                <tr>
                    <td>{{$v['g_id']}}</td>
                    <td>{{$v['g_name']}}</td>
                    <td>{{$v['g_price']/100}}</td>
                    <td>{{date('Y-m-d H:i:s'),$v['add_time']}}</td>
                    <td>

                        <a href="/goods/show/{{$v["g_id"]}}">商品详情</a>|
                        <a href="/cart/del2/{{$v['g_id']}}" class="del_goods">删除</a>
                    </td>
                </tr>
            @endforeach
        </table>
    <hr>
    <a href="/order/add" id="submit_order" class="btn btn-info "> 提交订单 </a>
@endsection

@section('footer')
    @parent
@endsection