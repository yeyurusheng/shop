{{-- 购物车 --}}
@extends('layouts.bst')

@section('content')
    <div class="container">
        <ul>
            @foreach($list as $k=>$v)
                <li>{{$v['g_id']}}    --  {{$v['g_name']}}  -  ¥ {{$v['g_price'] / 100}}   --  {{date('Y-m-d H:i:s'),$v['add_time']}}
                    <a href="/cart/del2/{{$v['g_id']}}" class="del_goods">删除</a></li>
            @endforeach
        </ul>
        <hr>
        <a href="/order/add" id="submit_order" class="btn btn-info "> 提交订单 </a>
    </div>

@endsection

@section('footer')
    @parent
@endsection