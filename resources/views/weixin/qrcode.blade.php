{{--二维码--}}
@extends('layouts.bst')
@section('content')
    <input type="hidden" id="qr" value="{{$code_url}}">
    <input type="hidden" id="order_sn" value="{{$order_sn}}">
    <div id="qrcode"></div>
@endsection
@section('footer')
    @parent
    <script type="text/javascript" src="/js/jquery-1.12.4.min.js"></script>
    <script type="text/javascript" src="/js/weixin/qrcode.min.js"></script>
    <script>
        var order_sn = $('#order_sn').val()
        setInterval(function(){
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url     :   '/weixin/pay/success',
                type    :   'get',
                data    :   {order_sn:order_sn},
                success :   function(res){
                    console.log(res)
                    if(res){
                        alert('支付成功')
                        location.href="/goods/list"
                    }
                }
            });
        },5000);

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