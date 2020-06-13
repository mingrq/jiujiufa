var layer;
// var upload;
$(function () {
    layui.use(['layer', 'upload'], function () {
        layer = layui.layer;
        var upload = layui.upload;

        //淘宝京东表格
        var uploadInstimportTbjd = upload.render({
            elem: '#importTbjd'
            , url: '/member/order/importTbjd'
            , field: 'uploadkdodfile'
            , accept: 'file'
            , exts: 'xls|xlsx'
            , size: 5120
            , done: function (res) {
                //上传完毕回调
                // console.log(res);
                layer.closeAll();
                if (res.code == 10000) {
                    let result = res.result;
                    let contentStr = '';
                    for (let i = 0; i < result.length; i++) {
                        contentStr = contentStr + result[i]['kdid'] + '，' + result[i]['name'] + '，' + result[i]['mobile'] + '，' + result[i]['address'] + '，' + result[i]['postal'] + '\r\n';
                    }
                    $("#content").val(contentStr);
                } else {
                    layer.msg(res.message, {
                        icon: 2,
                        time: 3000,
                        btnAlign: "c",
                        offset: "300px"
                    });
                }
            }
            , error: function () {
                layer.closeAll();
                //请求异常回调
                layer.msg("导入文件异常", {
                    icon: 2,
                    time: 3000,
                    btnAlign: "c",
                    offset: "300px"
                });
            }
        });

        //拼多多专用表格
        var uploadInstimportPindd = upload.render({
            elem: '#importPindd'
            , url: '/member/order/importPindd'
            , field: 'uploadkdodfile'
            , accept: 'file'
            , exts: 'xls|xlsx'
            , size: 5120
            , done: function (res) {
                //上传完毕回调
                // console.log(res);
                layer.closeAll();
                if (res.code == 10000) {
                    let result = res.result;
                    let contentStr = '';
                    for (let i = 0; i < result.length; i++) {
                        contentStr = contentStr + result[i]['kdid'] + '，' + result[i]['name'] + '，' + result[i]['mobile'] + '，' + result[i]['address'] + '，' + result[i]['postal'] + '\r\n';
                    }
                    $("#content").val(contentStr);
                } else {
                    layer.msg(res.message, {
                        icon: 2,
                        time: 3000,
                        btnAlign: "c",
                        offset: "300px"
                    });
                }
            }
            , error: function () {
                layer.closeAll();
                //请求异常回调
                layer.msg("导入文件异常", {
                    icon: 2,
                    time: 3000,
                    btnAlign: "c",
                    offset: "300px"
                });
            }
        });

        //淘宝后台表格
        var uploadInstimportTaobao = upload.render({
            elem: '#importTaobao'
            , url: '/member/order/importTaobao'
            , field: 'uploadkdodfile'
            , accept: 'file'
            , exts: 'xls|xlsx'
            , size: 5120
            , done: function (res) {
                //上传完毕回调
                // console.log(res);
                layer.closeAll();
                if (res.code == 10000) {
                    let result = res.result;
                    let contentStr = '';
                    for (let i = 0; i < result.length; i++) {
                        contentStr = contentStr + result[i]['kdid'] + '，' + result[i]['name'] + '，' + result[i]['mobile'] + '，' + result[i]['address'] + '，' + result[i]['postal'] + '\r\n';
                    }
                    $("#content").val(contentStr);
                } else {
                    layer.msg(res.message, {
                        icon: 2,
                        time: 3000,
                        btnAlign: "c",
                        offset: "300px"
                    });
                }
            }
            , error: function () {
                layer.closeAll();
                //请求异常回调
                layer.msg("导入文件异常", {
                    icon: 2,
                    time: 3000,
                    btnAlign: "c",
                    offset: "300px"
                });
            }
        });

        //京东后台表格
        var uploadInstimportJingd = upload.render({
            elem: '#importJingd'
            , url: '/member/order/importJingd'
            , field: 'uploadkdodfile'
            , accept: 'file'
            , exts: 'xls|xlsx'
            , size: 5120
            , done: function (res) {
                //上传完毕回调
                // console.log(res);
                layer.closeAll();
                if (res.code == 10000) {
                    let result = res.result;
                    let contentStr = '';
                    for (let i = 0; i < result.length; i++) {
                        contentStr = contentStr + result[i]['kdid'] + '，' + result[i]['name'] + '，' + result[i]['mobile'] + '，' + result[i]['address'] + '，' + result[i]['postal'] + '\r\n';
                    }
                    $("#content").val(contentStr);
                } else {
                    layer.msg(res.message, {
                        icon: 2,
                        time: 3000,
                        btnAlign: "c",
                        offset: "300px"
                    });
                }
            }
            , error: function () {
                layer.closeAll();
                //请求异常回调
                layer.msg("导入文件异常", {
                    icon: 2,
                    time: 3000,
                    btnAlign: "c",
                    offset: "300px"
                });
            }
        });

        //拼多多订单
        var uploadInstimportPingddOrder = upload.render({
            elem: '#importPingddOrder'
            , url: '/member/order/importPingddOrder'
            , field: 'uploadkdodfile'
            , accept: 'file'
            , exts: 'xls|xlsx'
            , size: 5120
            , done: function (res) {
                //上传完毕回调
                // console.log(res);
                layer.closeAll();
                if (res.code == 10000) {
                    let result = res.result;
                    let contentStr = '';
                    for (let i = 0; i < result.length; i++) {
                        contentStr = contentStr + result[i]['kdid'] + '，' + result[i]['name'] + '，' + result[i]['mobile'] + '，' + result[i]['address'] + '，' + result[i]['postal'] + '\r\n';
                    }
                    $("#content").val(contentStr);
                } else {
                    layer.msg(res.message, {
                        icon: 2,
                        time: 3000,
                        btnAlign: "c",
                        offset: "300px"
                    });
                }
            }
            , error: function () {
                layer.closeAll();
                //请求异常回调
                layer.msg("导入文件异常", {
                    icon: 2,
                    time: 3000,
                    btnAlign: "c",
                    offset: "300px"
                });
            }
        });

    });

    $("#uploadKdModel").click(function () {
        //通过这种方式弹出的层，每当它被选择，就会置顶。
        layer.open({
            title: '选择导入文件',
            type: 1,
            shade: [0.3, '#000000'],
            area: '860px',
            maxmin: false,
            btnAlign: "c",
            offset: "260px",
            content: $('#importOrderWin')
        });
    });


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
                            if ($goods[i].good_special_price) {
                                $("#selproduct").append($("<option>").val($goods[i].kdId).attr("data-myprice", toDecimal(Number($goods[i].cost_price) + Number($goods[i].good_special_price))).text($goods[i].kdName + "普通会员价:" + toDecimal(Number($goods[i].cost_price) + Number($goods[i].good_price)) + " / 代理会员价:" + toDecimal(Number($goods[i].cost_price) + Number($goods[i].good_vip_price)) + " / 我的价格:" + toDecimal(Number($goods[i].cost_price) + Number($goods[i].good_special_price))));
                            }else{
                                $("#selproduct").append($("<option>").val($goods[i].kdId).attr("data-myprice", toDecimal(Number($goods[i].cost_price) + Number($goods[i].good_price))).text($goods[i].kdName + "普通会员价:" + toDecimal(Number($goods[i].cost_price) + Number($goods[i].good_price)) + " / 代理会员价:" + toDecimal(Number($goods[i].cost_price) + Number($goods[i].good_vip_price)) + " / 我的价格:" + toDecimal(Number($goods[i].cost_price) + Number($goods[i].good_price))));
                            }
                        }
                    } else if ($mrank == 2) {
                        for (let i = 0; i < $goods.length; i++) {
                            if ($goods[i].good_special_vip_price) {
                                $("#selproduct").append($("<option>").val($goods[i].kdId).attr("data-myprice", toDecimal(Number($goods[i].cost_price) + Number($goods[i].good_special_vip_price))).text($goods[i].kdName + "普通会员价:" + toDecimal(Number($goods[i].cost_price) + Number($goods[i].good_price)) + " / 代理会员价:" + toDecimal(Number($goods[i].cost_price) + Number($goods[i].good_vip_price)) + " / 我的价格:" + toDecimal(Number($goods[i].cost_price) + Number($goods[i].good_special_vip_price))));
                            }else{
                                $("#selproduct").append($("<option>").val($goods[i].kdId).attr("data-myprice", toDecimal(Number($goods[i].cost_price) + Number($goods[i].good_vip_price))).text($goods[i].kdName + "普通会员价:" + toDecimal(Number($goods[i].cost_price) + Number($goods[i].good_price)) + " / 代理会员价:" + toDecimal(Number($goods[i].cost_price) + Number($goods[i].good_vip_price)) + " / 我的价格:" + toDecimal(Number($goods[i].cost_price) + Number($goods[i].good_vip_price))));
                            }
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
        // contentArr = content.split(/[(\r\n)\r\n]+/);
        contentArr = content.split(/[\r\n]+/);
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
        if (one_cnotent_arr.length == 5) {
            // 按逗号分割
            $tr = $("<tr>" +
                "<td>" + i + "</td>" +
                "<td>" + one_cnotent_arr[0] + "</td>" +
                "<td>" + one_cnotent_arr[1] + "</td>" +
                "<td>" + one_cnotent_arr[2] + "</td>" +
                "<td>" + one_cnotent_arr[3] + "</td>" +
                "<td>" + one_cnotent_arr[4] + "</td>" +
                "</tr>");
        } else {
            // 按逗号分割
            $tr = $("<tr style=\"background-color: #EA5252;\">" +
                "<td>" + i + "</td>" +
                "<td colspan=\"5\" >第" + i + "行收货地址格式错误，请修改后重新检查无误再购买</td>" +
                "</tr>");
        }
        $("#tb_addrs").append($tr);
    }
    var price = $('#selproduct').find("option:selected").attr("data-myprice");
    var accont = toDecimal(contentArr.length * Number(price));
    var $tr_bottom = $("<tr>" +
        "<td colspan=\"6\" style=\"text-align: center; color: red;\">" + contentArr.length + " X " + price + " = " + accont + "元</td>" +
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

/**
 * 确认购买
 */
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
        contentArr = content.split(/[\r\n]+/);
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
        if (one_cnotent_arr.length == 5) {
            // 按逗号分割
            $tr = $("<tr>" +
                "<td>" + i + "</td>" +
                "<td>" + one_cnotent_arr[0] + "</td>" +
                "<td>" + one_cnotent_arr[1] + "</td>" +
                "<td>" + one_cnotent_arr[2] + "</td>" +
                "<td>" + one_cnotent_arr[3] + "</td>" +
                "<td>" + one_cnotent_arr[4] + "</td>" +
                "</tr>");
        } else {
            // 按逗号分割
            $tr = $("<tr style=\"background-color: #EA5252;\">" +
                "<td>" + i + "</td>" +
                "<td colspan=\"5\" >第" + i + "行收货地址格式错误，请修改后重新检查无误再购买</td>" +
                "</tr>");
            errorNum++;
        }
        $("#tb_addrs").append($tr);
    }
    var price = $('#selproduct').find("option:selected").attr("data-myprice");
    var accont = toDecimal(contentArr.length * Number(price));
    var $tr_bottom = $("<tr>" +
        "<td colspan=\"6\" style=\"text-align: center; color: red;\">" + contentArr.length + " X " + price + " = " + accont + "元</td>" +
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
                    window.location.href = "/member/order/orderList";
                } else {
                    layer.open({
                        content: data.message,
                        btnAlign: "c",
                        offset: "300px"
                    });
                    return false;
                }
            },
            error: function () {
                console.log('error');
            },
            complete: function () {
                // 关闭
                layer.close(load_index);
            }
        });
    }
}

/**
 * 置空数据 隐藏所有弹框
 */
function closedData() {
    $("#realOrderDisWin textarea").val('');
    // $(':radio[name="selreal"]').eq(0).attr("checked",true);
    // $("input[name='selreal'][value='1']").attr("checked",true);
    // $("input[name='selreal']:checked").val(1);
    // $("#realOrderDisWin,#createUserWin,#updateSysUserWin").hide();
    // $("#realOrderDisWin").hide();
    layer.closeAll();
}

/**
 * 真实订单区分窗口
 */
function realOrderDisWin() {
    //通过这种方式弹出的层，每当它被选择，就会置顶。
    layer.open({
        title: '订单在线区分器',
        type: 1,
        shade: [0.3, '#000000'],
        area: '580px',
        maxmin: false,
        btnAlign: "c",
        offset: "260px",
        content: $('#realOrderDisWin')
    });
}

/**
 * 确认区分
 */
function distinguish() {
    var realContent = $("#realcontent").val().trim();
    // 先换行
    var realContentArr = [];
    if (realContent != '') {
        realContentArr = realContent.split(/[\r\n]+/);
    } else {
        layer.open({
            content: "请输入订单号",
            btnAlign: "c",
            offset: "300px"
        });
        return false;
    }

    var content = $("#content").val().trim();
    var contentArr = [];
    if (content != '') {
        contentArr = content.split(/[\r\n]+/);
    } else {
        layer.open({
            content: "请先导入购买信息",
            btnAlign: "c",
            offset: "300px"
        });
        return false;
    }

    var load_index = layer.load(2, {shade: [0.5, '#000000']});
    var selreal = $("input[name='selreal']:checked").val();
    //var tempRealArr = [];
    var tempRealStr = "";
    var tempRealNum = 0;
    if (selreal == 1) {
        // 保留输入框中的订单号
        for (let i = 0; i < realContentArr.length; i++) {
            if (realContentArr[i].trim() != '') {
                for (let j = 0; j < contentArr.length; j++) {
                    if (contentArr[j].trim() != '') {
                        let addressTemp = contentArr[j].split('，');
                        if (addressTemp.length == 5) {
                            if (realContentArr[i].trim() == addressTemp[0].trim() || realContentArr[i].trim() == addressTemp[1].trim() || realContentArr[i].trim() == addressTemp[3].trim()) {
                                // tempRealArr.push(contentArr[j]);
                                if (tempRealStr != "") {
                                    tempRealStr = tempRealStr + "\r\n" + contentArr[j];
                                } else {
                                    tempRealStr = contentArr[j];
                                }
                                tempRealNum++;
                            }
                        } else {
                            continue;
                        }
                    } else {
                        continue;
                    }
                }
            } else {
                continue;
            }
        }
        // 将数据填充
        // $("#content").val(tempRealArr.join());
        if (tempRealNum == 0) {
            layer.open({
                content: "未识别到订单，请确认输入的订单号是否正确",
                btnAlign: "c",
                offset: "300px"
            });
        } else {
            $("#content").val(tempRealStr);
            closedData();
            layer.open({
                content: "共识别到" + tempRealNum + "单订单，其它订单已自动为您过滤掉",
                btnAlign: "c",
                offset: "300px"
            });
        }
    } else if (selreal == 2) {
        // 过滤输入框中的订单号
        for (let i = 0; i < realContentArr.length; i++) {
            if (realContentArr[i].trim() != '') {
                for (let j = 0; j < contentArr.length; j++) {
                    if (contentArr[j].trim() != '') {
                        let addressTemp = contentArr[j].split('，');
                        if (addressTemp.length == 5) {
                            if (realContentArr[i].trim() != addressTemp[0].trim() && realContentArr[i].trim() != addressTemp[1].trim() && realContentArr[i].trim() != addressTemp[3].trim()) {
                                // tempRealArr.push(contentArr[j]);
                                if (tempRealStr != "") {
                                    tempRealStr = tempRealStr + "\r\n" + contentArr[j];
                                } else {
                                    tempRealStr = contentArr[j];
                                }
                                tempRealNum++;
                            }
                        } else {
                            continue;
                        }
                    } else {
                        continue;
                    }
                }
            } else {
                continue;
            }
        }
        // 将数据填充
        // $("#content").val(tempRealArr.join());
        if (tempRealNum == 0) {
            layer.open({
                content: "未识别到订单，请确认输入的订单号是否正确",
                btnAlign: "c",
                offset: "300px"
            });
        } else {
            $("#content").val(tempRealStr);
            closedData();
            layer.open({
                content: "共识别到" + tempRealNum + "单订单，其它订单已自动为您过滤掉",
                btnAlign: "c",
                offset: "300px"
            });
        }
    } else {
        layer.open({
            content: "请选择保留还是过滤",
            btnAlign: "c",
            offset: "300px"
        });
        return false;
    }
    layer.close(load_index);
}

/**
 * 点击下载快递上传模板
 */
function downKdModelWin() {
    layer.open({
        content: "<p>淘宝京东表格模板</p>" +
            "<p>拼多多专用表格模板</p>"
        , btnAlign: "c"
        , area: '500px'
        , offset: "300px"
        , btn: ['淘宝京东表格模板', '拼多多专用表格模板']
        , yes: function (index, layero) {
            //淘宝京东表格模板 的回调
            try {
                window.open("/static/common/file/淘宝京东表格.xls", '_blank')
            } catch (e) {
                layer.msg("下载异常", {
                    icon: 2,
                    time: 3000
                });
            }
            layer.close(index)
        }
        , btn2: function (index, layero) {
            //拼多多专用表格模板 的回调
            try {
                window.open("/static/common/file/拼多多专用表格.xls", '_blank')
            } catch (e) {
                layer.msg("下载异常", {
                    icon: 2,
                    time: 3000
                });
            }
            layer.close(index)
            //return false 开启该代码可禁止点击该按钮关闭
        }
        , cancel: function () {
            //右上角关闭回调
            // console.log('cancel');
            //return false 开启该代码可禁止点击该按钮关闭
        }
    });
}
/*

// 导入淘宝京东表格
function importTbjd() {
    //执行实例
    var uploadInstimportTbjd = upload.render({
            elem: '#importTbjd'
            , url: '/member/order/importTbjd'
            , field: 'uploadkdodfile'
            , accept: 'file'
            , exts: 'xls|xlsx'
            , size: 5120
            , done: function (res) {
                //上传完毕回调
                // console.log(res);
                if (res.code == 10000) {
                    let result = res.result;
                    let contentStr = '';
                    for (let i = 0; i < result.length; i++) {
                        contentStr = contentStr + result[i]['kdid'] + '，' + result[i]['name'] + '，' + result[i]['mobile'] + '，' + result[i]['address'] + '，' + result[i]['postal'] + '\r\n';
                    }
                    $("#content").val(contentStr);
                } else {
                    layer.msg(res.message, {
                        icon: 2,
                        time: 3000,
                        btnAlign: "c",
                        offset: "300px"
                    });
                }
            }
            , error: function () {
                //请求异常回调
                layer.msg("导入文件异常", {
                    icon: 2,
                    time: 3000,
                    btnAlign: "c",
                    offset: "300px"
                });
            }
        });
}

// 导入拼多多专用表格
function importPindd() {
    //执行实例
    var uploadInstimportPindd = upload.render({
        elem: '#importPindd'
        , url: '/member/order/importPindd'
        , field: 'uploadkdodfile'
        , accept: 'file'
        , exts: 'xls|xlsx'
        , size: 5120
        , done: function (res) {
            //上传完毕回调
            // console.log(res);
            if (res.code == 10000) {
                let result = res.result;
                let contentStr = '';
                for (let i = 0; i < result.length; i++) {
                    contentStr = contentStr + result[i]['kdid'] + '，' + result[i]['name'] + '，' + result[i]['mobile'] + '，' + result[i]['address'] + '，' + result[i]['postal'] + '\r\n';
                }
                $("#content").val(contentStr);
            } else {
                layer.msg(res.message, {
                    icon: 2,
                    time: 3000,
                    btnAlign: "c",
                    offset: "300px"
                });
            }
        }
        , error: function () {
            //请求异常回调
            layer.msg("导入文件异常", {
                icon: 2,
                time: 3000,
                btnAlign: "c",
                offset: "300px"
            });
        }
    });
}

// 导入拼多多专用表格
function importTaobao() {
    //执行实例
    var uploadInstimportTaobao = upload.render({
        elem: '#importTaobao'
        , url: '/member/order/importTaobao'
        , field: 'uploadkdodfile'
        , accept: 'file'
        , exts: 'xls|xlsx'
        , size: 5120
        , done: function (res) {
            //上传完毕回调
            // console.log(res);
            if (res.code == 10000) {
                let result = res.result;
                let contentStr = '';
                for (let i = 0; i < result.length; i++) {
                    contentStr = contentStr + result[i]['kdid'] + '，' + result[i]['name'] + '，' + result[i]['mobile'] + '，' + result[i]['address'] + '，' + result[i]['postal'] + '\r\n';
                }
                $("#content").val(contentStr);
            } else {
                layer.msg(res.message, {
                    icon: 2,
                    time: 3000,
                    btnAlign: "c",
                    offset: "300px"
                });
            }
        }
        , error: function () {
            //请求异常回调
            layer.msg("导入文件异常", {
                icon: 2,
                time: 3000,
                btnAlign: "c",
                offset: "300px"
            });
        }
    });
}

// 导入京东后台表格
function importJingd() {
    //执行实例
    var uploadInstimportJingd = upload.render({
        elem: '#importJingd'
        , url: '/member/order/importJingd'
        , field: 'uploadkdodfile'
        , accept: 'file'
        , exts: 'xls|xlsx'
        , size: 5120
        , done: function (res) {
            //上传完毕回调
            // console.log(res);
            if (res.code == 10000) {
                let result = res.result;
                let contentStr = '';
                for (let i = 0; i < result.length; i++) {
                    contentStr = contentStr + result[i]['kdid'] + '，' + result[i]['name'] + '，' + result[i]['mobile'] + '，' + result[i]['address'] + '，' + result[i]['postal'] + '\r\n';
                }
                $("#content").val(contentStr);
            } else {
                layer.msg(res.message, {
                    icon: 2,
                    time: 3000,
                    btnAlign: "c",
                    offset: "300px"
                });
            }
        }
        , error: function () {
            //请求异常回调
            layer.msg("导入文件异常", {
                icon: 2,
                time: 3000,
                btnAlign: "c",
                offset: "300px"
            });
        }
    });
}

// 导入京东后台表格
function importPingddOrder() {
    //执行实例
    var uploadInstimportPingddOrder = upload.render({
        elem: '#importPingddOrder'
        , url: '/member/order/importPingddOrder'
        , field: 'uploadkdodfile'
        , accept: 'file'
        , exts: 'xls|xlsx'
        , size: 5120
        , done: function (res) {
            //上传完毕回调
            // console.log(res);
            if (res.code == 10000) {
                let result = res.result;
                let contentStr = '';
                for (let i = 0; i < result.length; i++) {
                    contentStr = contentStr + result[i]['kdid'] + '，' + result[i]['name'] + '，' + result[i]['mobile'] + '，' + result[i]['address'] + '，' + result[i]['postal'] + '\r\n';
                }
                $("#content").val(contentStr);
            } else {
                layer.msg(res.message, {
                    icon: 2,
                    time: 3000,
                    btnAlign: "c",
                    offset: "300px"
                });
            }
        }
        , error: function () {
            //请求异常回调
            layer.msg("导入文件异常", {
                icon: 2,
                time: 3000,
                btnAlign: "c",
                offset: "300px"
            });
        }
    });
}
*/

