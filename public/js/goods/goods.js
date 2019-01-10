$('#add_cart_btn').click(function(e){
    e.preventDefault();
    var buy_number=$('#buy_number').val();
    var g_id=$('#g_id').val();
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url:'/cart/add2',
        type:'post',
        data:{g_id:g_id,buy_number:buy_number},
        dataType:'json',
        success:function(d){
            if(d.error==301){
                window.location.href=d.url;
            }else{
                alert(d.msg);
            }
        }
    })
})