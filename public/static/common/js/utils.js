/**
 * 验证手机号
 * @param phone
 * @returns {boolean}
 */
function checkPhone(phone){
    if(!(/^1[3456789]\d{9}$/.test(phone))){
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