var layer;
$(function () {
    layui.use('layer', function () {
        layer = layui.layer;
    })
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
    if (contentArr.length > 2000) {
        layer.open({
            content: "您一次性最多只能提交2000条小礼品订单，超过2000单请分开多次下单",
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
    var $tr_bottom = $("<tr>" +
        "<td colspan=\"5\" style=\"text-align: center; color: red;\">" + contentArr.length + " X 2.20 = 4.4元</td>" +
        "</tr>");
    $("#tb_addrs").append($tr_bottom);
    // 关闭
    layer.close(load_index);
}
