var layer;
$(function () {
    layui.use('layer', function () {
        layer = layui.layer;
    })
})

/**
 * 删除订单
 */
function delOrder(id) {
    // console.log(id);
    $.ajax({
        url: "/member/order/delLpdh",
        type: "POST",
        dataType: "json",
        data: {
            oid: id
        },
        success: function (data) {
            console.log(data);
            if (data.code == 10000) {
                layer.open({
                    content: data.message,
                    btnAlign: "c",
                    offset: "300px",
                    yes: function(index, layero){
                        layer.close(index); //如果设定了yes回调，需进行手工关闭
                        window.location.href="/member/order/orderList";
                    }
                });
            }else {
                layer.open({
                    content: data.message,
                    btnAlign: "c",
                    offset: "300px"
                });
                return false;
            }
        },
        error: function(){
            console.log('error');
        }
    });
}
