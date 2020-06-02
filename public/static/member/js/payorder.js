var layer;
$(function () {
    layui.use('layer', function () {
        layer = layui.layer;
    })


    /**
     * 选择仓库
     */
    $("#selcangku li").click(function () {
        var ckid = $(this).attr("data-ckid");
        $("#selproduct").empty();
        $("#selproduct").append($("<option>").val(0).attr("data-myprice", 0).text("请选择需要购买的快递公司物流"));
        $("#curgoodsprice").html($('#selproduct').find("option:selected").attr("data-myprice"));
        // 获取商品数据 并填充
        $.ajax({
            url: "/member/order/getGoodsByCkId",
            type: "POST",
            dataType: "json",
            data: {
                ckid: ckid
            },
            success: function (data) {
                if (data.code == 10000) {
                    $mrank = data.result.mrank;
                    $goods = data.result.goodsList;
                    if ($mrank == 1) {
                        for (let i = 0; i < $goods.length; i++) {
                            $("#selproduct").append($("<option>").val($goods[i].kdId).attr("data-myprice", toDecimal(Number($goods[i].cost_price) + Number($goods[i].good_price))).text($goods[i].kdName + "普通会员价:" + toDecimal(Number($goods[i].cost_price) + Number($goods[i].good_price)) + " / 代理会员价:" + toDecimal(Number($goods[i].cost_price) + Number($goods[i].good_vip_price)) + " / 我的价格:" + toDecimal(Number($goods[i].cost_price) + Number($goods[i].good_price))));
                        }
                    } else if ($mrank == 2) {
                        for (let i = 0; i < $goods.length; i++) {
                            $("#selproduct").append($("<option>").val($goods[i].kdId).attr("data-myprice", toDecimal(Number($goods[i].cost_price) + Number($goods[i].good_vip_price))).text($goods[i].kdName + "普通会员价:" + toDecimal(Number($goods[i].cost_price) + Number($goods[i].good_price)) + " / 代理会员价:" + toDecimal(Number($goods[i].cost_price) + Number($goods[i].good_vip_price)) + " / 我的价格:" + toDecimal(Number($goods[i].cost_price) + Number($goods[i].good_vip_price))));
                        }
                    }
                }
            },
            error: function () {
                console.log('error');
            }
            /*
            error: function(XMLHttpRequest, textStatus, errorThrown){
                console.log(XMLHttpRequest.readyState);
                console.log(XMLHttpRequest.status);
                console.log(XMLHttpRequest.responseText);
                console.log(textStatus)
            }
            */
        });
        // 仓库样式
        $("#selcangku li").removeClass('action');
        $(this).addClass('action');
    });

    // 选择商品改变价格
    $("#curgoodsprice").html($('#selproduct').find("option:selected").attr("data-myprice"));
    $("#selproduct").change(function () {
        $("#curgoodsprice").html($('#selproduct').find("option:selected").attr("data-myprice"));
    });
})

// 检查收获地址
function checkAddress() {
    // 查看是否选中商品
    var selproduct = $("#selproduct option:selected").val();
    if (selproduct == 0) {
        layer.open({
            content: "请选择商品",
            btnAlign: "c",
            offset: "300px"
        });
        return;
    }
    // 收货地址
    var content = $("#content").val().trim();
    // 格式 李四，15888888888，广东省 广州市 番禺区 岭南大道321号 ，330006
    // 先换行
    var contentArr = [];
    if (content != '') {
        contentArr = content.split(/[(\r\n)\r\n]+/);
    }
    // 清空table
    $("#tb_addrs tr:not(:first)").empty();
    if (contentArr.length > 1000) {
        layer.open({
            content: "您一次性最多只能提交2000条小礼品订单，超过1000单请分开多次下单",
            btnAlign: "c",
            offset: "300px"
        });
        return;
    }
    var load_index = layer.load(2, {shade: [0.5, '#000000']});
    for (let i = 1; i <= contentArr.length; i++) {
        let one_cnotent_arr = contentArr[i - 1].split("，");
        let $tr;
        if (one_cnotent_arr.length == 4) {
            // 按逗号分割
            $tr = $("<tr>" +
                "<td>" + i + "</td>" +
                "<td>" + one_cnotent_arr[0] + "</td>" +
                "<td>" + one_cnotent_arr[1] + "</td>" +
                "<td>" + one_cnotent_arr[2] + "</td>" +
                "<td>" + one_cnotent_arr[3] + "</td>" +
                "</tr>");
        } else {
            // 按逗号分割
            $tr = $("<tr style=\"background-color: #EA5252;\">" +
                "<td>" + i + "</td>" +
                "<td colspan=\"4\" >第" + i + "行收货地址格式错误，请修改后重新检查无误再购买</td>" +
                "</tr>");
        }
        $("#tb_addrs").append($tr);
    }
    var price = $('#selproduct').find("option:selected").attr("data-myprice");
    var accont = toDecimal( contentArr.length * Number(price));
    var $tr_bottom = $("<tr>" +
        "<td colspan=\"5\" style=\"text-align: center; color: red;\">" + contentArr.length + " X " + price + " = " + accont + "元</td>" +
        "</tr>");
    $("#tb_addrs").append($tr_bottom);
    // 关闭
    layer.close(load_index);
}

function toDecimal(x) {
    var val = Number(x)
    if (!isNaN(parseFloat(val))) {
        val = val.toFixed(2);
    }
    return val;
}


function buyGoods() {
    var errorNum = 0;
    // 判断单价
    // 判断数量
    // 判断总金额

    // 判断是否有格式错误的   您提交的收货地址有1处填写错误，请修改
    // 查看是否选中商品
    var selproduct = $("#selproduct option:selected").val();
    if (selproduct == 0) {
        layer.open({
            content: "请选择商品",
            btnAlign: "c",
            offset: "300px"
        });
        return;
    }
    // 收货地址
    var content = $("#content").val().trim();
    // 格式 李四，15888888888，广东省 广州市 番禺区 岭南大道321号 ，330006
    // 先换行
    var contentArr = [];
    if (content != '') {
        contentArr = content.split(/[(\r\n)\r\n]+/);
    } else {
        layer.open({
            content: "您未填写收货地址或收货地址大于1000个",
            btnAlign: "c",
            offset: "300px"
        });
        return;
    }
    // 清空table
    $("#tb_addrs tr:not(:first)").empty();
    if (contentArr.length > 1000) {
        layer.open({
            content: "您一次性最多只能提交2000条小礼品订单，超过1000单请分开多次下单",
            btnAlign: "c",
            offset: "300px"
        });
        return;
    }
    var load_index = layer.load(2, {shade: [0.5, '#000000']});
    for (let i = 1; i <= contentArr.length; i++) {
        let one_cnotent_arr = contentArr[i - 1].split("，");
        let $tr;
        if (one_cnotent_arr.length == 4) {
            // 按逗号分割
            $tr = $("<tr>" +
                "<td>" + i + "</td>" +
                "<td>" + one_cnotent_arr[0] + "</td>" +
                "<td>" + one_cnotent_arr[1] + "</td>" +
                "<td>" + one_cnotent_arr[2] + "</td>" +
                "<td>" + one_cnotent_arr[3] + "</td>" +
                "</tr>");
        } else {
            // 按逗号分割
            $tr = $("<tr style=\"background-color: #EA5252;\">" +
                "<td>" + i + "</td>" +
                "<td colspan=\"4\" >第" + i + "行收货地址格式错误，请修改后重新检查无误再购买</td>" +
                "</tr>");
            errorNum++;
        }
        $("#tb_addrs").append($tr);
    }
    var price = $('#selproduct').find("option:selected").attr("data-myprice");
    var accont = toDecimal( contentArr.length * Number(price));
    var $tr_bottom = $("<tr>" +
        "<td colspan=\"5\" style=\"text-align: center; color: red;\">" + contentArr.length + " X " + price + " = " + accont + "元</td>" +
        "</tr>");
    $("#tb_addrs").append($tr_bottom);

    if (errorNum != 0) {
        // 关闭
        layer.close(load_index);
        layer.open({
            content: "您提交的收货地址有" + errorNum + "处填写错误，请修改",
            btnAlign: "c",
            offset: "300px"
        });
        return false;
    } else {
        // 快递名称：广州圆通速递(菜鸟仓)+粉碎办公专用废纸 7单 X 2.20元 = 15.4元
        // 您的余额不足，请充值后再行购买
        var content = $("#content").val().trim();
        var ckid = $("#selcangku").find("li.action").attr("data-ckid") ? $("#selcangku").find("li.action").attr("data-ckid") : 0;
        $.ajax({
            url: "/member/order/buyOrder",
            type: "POST",
            dataType: "json",
            data: {
                kdid: $('#selproduct').val(),
                ckid: ckid,
                content: content
            },
            success: function (data) {
                // console.log(data);
                if (data.code == 10000) {
                    window.location.href="/member/order/orderList";
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
            },
            complete: function () {
                // 关闭
                layer.close(load_index);
            }
        });
    }
}

