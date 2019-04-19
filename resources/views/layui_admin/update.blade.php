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



<script src="/layui/layui.js"></script>
<script>
    $(function(){
        layui.use(['laydate', 'laypage', 'layer', 'table', 'carousel', 'upload', 'element', 'slider'], function(){


        });
    })
</script>
</body>
</html>


@endsection
