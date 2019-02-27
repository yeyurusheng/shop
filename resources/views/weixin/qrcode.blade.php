{{--二维码--}}
@extends('layouts.bst')
@section('content')
    <input type="hidden" id="qr" value="{{$code_url}}">
    <div id="qrcode"></div>
@endsection
@section('footer')
    @parent
    <script type="text/javascript" src="/js/jquery-1.12.4.min.js"></script>
    <script type="text/javascript" src="/js/weixin/qrcode.min.js"></script>
    <script>
        var code_url = $('#qr').val();
        console.log(code_url)
        var qrcode = new QRCode('qrcode', {
            text: code_url,
            width: 256,
            height: 256,
            colorDark : '#000000',
            colorLight : '#ffffff',
            correctLevel : QRCode.CorrectLevel.H
        });
    </script>
@endsection