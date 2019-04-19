@extends('layout')

@section('content')

        <!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="/layui/css/layui.css" media="all">
</head>
<body>
<table class="layui-hide" id="demo" lay-filter="test"></table>

<div style="padding: 15px;">
    <div class="layui-input-inline">
        <input type="text" placeholder="请输入订单号" id="order_sn" class="layui-input" autocomplete="off" style="width:300px;">
    </div>
    <div class="layui-input-inline">
        <input type="text" placeholder="请输入用户名" id="u_name" class="layui-input" autocomplete="off" style="width:300px;">
    </div>

    <button class="layui-btn" lay-filter="formDemo" id="btn">搜索</button>
    {{--style="cursor: pointer"--}}
    <table id="demo" lay-filter = "table_edit"></table>
</div>
<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="detail">查看</a>
</script>

<script src="/layui/layui.js"></script>
<script>
    $(function(){
        layui.use(['laydate', 'laypage', 'layer', 'table', 'carousel', 'upload', 'element', 'slider'], function(){
            var table = layui.table;
            //第一个实例
            table.render({
                elem: '#demo'
                ,width:1200
                ,height: 277
                ,url: '/admin/layui' //数据接口
                ,page: true //开启分页
                ,cols: [[ //表头
                    {field: 'd_id', title: 'ID', width:80, sort: true, fixed: 'left'}
                    ,{field: 'order_sn', title: '订单号', width:210}
                    ,{field: 'g_name', title: '商品名称', width:90}
                    ,{field: 'uid', title: '用户', width:80}
                    ,{field: 'g_price', title: '购买价格', width:180}
                    ,{field: 'status', title: '支付状态', width: 120}
                    ,{field: 'buy_number', title: '购买数量', width: 100}
                    ,{field: 'add_time', title: '添加时间', width: 180}
                    ,{fixed: 'right', width: 165, align:'center', toolbar: '#barDemo'}
                ]]
            });


            //监听行工具事件
            table.on('tool(test)', function(obj){ //注：tool 是工具条事件名，test 是 table 原始容器的属性 lay-filter="对应的值"
                var data = obj.data //获得当前行数据
                    ,layEvent = obj.event; //获得 lay-event 对应的值

                if(layEvent === 'detail'){
                    // console.log(data.d_id)
                    var order_sn = (data.order_sn)
                    location.href = "/admin/update/"+order_sn
                    // console.log(d_id)
                    // $.ajax({
                    //     url:"/admin/update/"+d_id,
                    //     data:{d_id:d_id},
                    //     method:'POST',
                    //     dataType:'json',
                    //     success:function(msg){
                    //         console.log(d_id)
                    //
                    //     },
                    // })
                }
            });



            //搜索
            $('#btn').click(function(){
                var order_sn = $('#order_sn').val();
                var u_name = $('#u_name').val();
                table.reload('demo',{
                    where:{order_sn:order_sn,u_name:u_name}
                });
            })


        });
    })
</script>
</body>
</html>


@endsection
