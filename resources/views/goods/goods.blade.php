{{--商品列表展示--}}
@extends('layouts.bst')
@section('content')
    <h1 style="color:greenyellow" align="center">欢迎来到kongyi</h1>
    <table class="table table-striped">
        <tr>
            <th>商品名称</th>
            <th>商品价格</th>
            <th>商品库存</th>
            <th>操作</th>
        </tr>
        @foreach($list as $v)
            <tr>
                <td>{{$v['g_name']}}</td>
                <td>{{$v['g_price']/100}}</td>
                <td>{{$v['g_store']}}</td>
                <td>
                    <a href="/goods/show/{{$v["g_id"]}}">商品详情</a>
                </td>
            </tr>
        @endforeach
    </table>
@endsection