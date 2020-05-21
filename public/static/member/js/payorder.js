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
            content: '请选择商品',
            btnAlign: 'c',
            offset: '300px'
        });
        return;
    }
    // 收货地址
    var content = $("#content").val().trim();
    // 格式 李四，15888888888，广东省 广州市 番禺区 岭南大道321号 ，330006
    // 先换行
    var contentArr = content.split(/[(\r\n)\r\n]+/);
    // 按逗号分割
    var $tr = $("<tr>" +
        "<td>1</td>" +
        "<td>李四</td>" +
        "<td>15888888888</td>" +
        "<td>广东省 广州市 番禺区 岭南大道321号</td>" +
        "<td>330006</td>" +
        "</tr>");
    $("#tb_addrs").append($tr);
}
