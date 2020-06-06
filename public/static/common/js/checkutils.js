/**
 * 验证手机号
 * @param phone
 * @returns {boolean}
 */
function checkPhone(phone) {
    if ((/^1[3456789]\d{9}$/.test(phone))) {
        return true;
    } else {
        return false;
    }
}


/**
 * 判断输入的是否是金额
 * @param money
 * @returns {boolean}
 */
function isMoney(money) {
    var reg = /(^[1-9]([0-9]+)?(\.[0-9]{1,2})?$)|(^(0){1}$)|(^[0-9]\.[0-9]([0-9])?$)/;
    if (reg.test(money)) {
        return true;
    } else {
        return false;
    }
}

//验证QQ号码5-11位
function isQQ(qq) {
    var filter = /^\s*[.0-9]{5,11}\s*$/;
    if (!filter.test(qq)) {
        return false;
    } else {
        return true;
    }
}

//验证邮箱格式
function isEmail(str) {
    if (str.charAt(0) == "." || str.charAt(0) == "@" || str.indexOf('@', 0) == -1 ||
        str.indexOf('.', 0) == -1 || str.lastIndexOf("@") == str.length - 1 ||
        str.lastIndexOf(".") == str.length - 1 ||
        str.indexOf('@.') > -1)
        return false;
    else
        return true;
}

/**
 * 获取小写随机字母
 */
function lowerCaseRandLetter() {
    return chr(rand(97, 122));
}

/**
 * 获取大写随机字母
 */
function randLetter() {
    return chr(rand(65, 90));
}

/**
 * js打开QQ交流
 * @param ac_qq  QQ账号
 */
function openQQ(ac_qq) {
    location.href = "tencent://message/?uin=" + ac_qq;
}