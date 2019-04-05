@extends('layouts.bst')
@section('content')
    <h1 style="color:darkgreen" align="center">后台登录状态</h1>
    <table class="table table-striped">
        <tr>
            <th>用户名</th>
            <th>身份证号</th>
            <th>身份证照片</th>
            <th>用途</th>
            <th>审核状态</th>
        </tr>
        @foreach($list as $v)
            <tr>
                <td>{{$v['name']}}</td>
                <td>{{$v['card_id']}}</td>
                <td>{{$v['photos']}}</td>
                <td>{{$v['purpose']}}</td>
                <td>
                    <button>
                        @if($v['status']==1)
                            审核通过
                        @elseif($v['status']==2)
                            审核不通过
                        @elseif($v['status']==0)
                            待审核
                        @endif
                    </button>

                </td>
            </tr>
        @endforeach
    </table>
@endsection