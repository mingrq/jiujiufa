<?php
/**
 * Created by PhpStorm.
 * User: ming
 * Date: 2020/3/17
 * Time: 20:08
 */

namespace malipay;

class malipay
{
    private $config;

    public function __construct($payment_info = array())
    {
        if (!empty($payment_info)) {
            $this->config = array(
                //应用ID,您的APPID。
                'app_id' => $payment_info['app_id'],
                //商户私钥
                'merchant_private_key' => $payment_info['merchant_private_key'],
                //异步通知地址
                'notify_url' => 'http://' . $_SERVER['HTTP_HOST'] . '/member/payment/alipaynotify.html', //通知URL,
                //同步跳转
                'return_url' => 'http://' . $_SERVER['HTTP_HOST'] . '/member/payment/alipayreturn', //返回URL,
                //编码格式
                'charset' => "UTF-8",
                //签名方式
                'sign_type' => "RSA2",
                //支付宝网关
                'gatewayUrl' => "https://openapi.alipaydev.com/gateway.do",
                //支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
                'alipay_public_key' => $payment_info['alipay_public_key']
            );
        }
    }

    /**
     * 支付的请求地址
     *
     * @return string
     */
    public function pay($order_info)
    {
        require_once dirname(__FILE__) . '/aop/AopClient.php';
        require_once dirname(__FILE__) . '/aop/request/AlipayTradePagePayRequest.php';
        $aop = new \AopClient();
        $aop->gatewayUrl = $this->config['gatewayUrl'];
        $aop->appId = $this->config['app_id'];
        $aop->rsaPrivateKey = $this->config['merchant_private_key'];
        $aop->alipayrsaPublicKey = $this->config['alipay_public_key'];
        $aop->apiVersion = '1.0';
        $aop->signType = 'RSA2';
        $aop->postCharset = 'utf-8';
        $aop->format = 'json';
        $request = new \AlipayTradePagePayRequest();
        $request->setNotifyUrl($this->config['notify_url']);
        $request->setReturnUrl($this->config['return_url']);
        $request->setBizContent("{" .
            "\"out_trade_no\":\"" . $order_info['out_trade_no'] . "\"," .
            "\"product_code\":\"FAST_INSTANT_TRADE_PAY\"," .
            "\"total_amount\":" . $order_info['api_pay_amount'] . "," .
            "\"subject\":\"" . $order_info['subject'] . "\"," .
            "\"body\":\"" . $order_info['order_type'] . "\"" .
            "  }");
        $result = $aop->pageExecute($request);
        echo $result;
        exit;
    }


    /**
     * 同步返回验证
     * @return array
     */
    public function return_verify()
    {
        require_once dirname(__FILE__) . '/aop/AopClient.php';
        $arr = $_GET;
        $return_result = array(
            'trade_status' => '0',
        );

        $temp = explode('-', input('param.out_trade_no'));
        $out_trade_no = $temp['1'];  //返回的支付单号
        $order_type = $temp['0'];

        $aop = new \AopClient();
        $aop->gatewayUrl = $this->config['gatewayUrl'];
        $aop->appId = $this->config['app_id'];
        $aop->rsaPrivateKey = $this->config['merchant_private_key'];
        $aop->alipayrsaPublicKey = $this->config['alipay_public_key'];
        $aop->apiVersion = '1.0';
        $aop->signType = 'RSA2';
        $aop->postCharset = 'utf-8';
        $aop->format = 'json';
        $result = $aop->rsaCheckV1($arr, $aop->alipayrsaPublicKey, $aop->signType);
        if ($result) {
            $return_result = array(
                'out_trade_no' => $out_trade_no, #商户订单号
                'trade_no' => input('param.trade_no'), #交易凭据单号
                'total_fee' => input('param.total_amount'), #涉及金额
                'order_type' => $order_type,
                'trade_status' => '1',
            );
        }

        return $return_result;
    }

    /**
     * 异步通知验证
     * @return array
     */
    public function verify_notify()
    {
        require_once dirname(__FILE__) . '/aop/AopClient.php';
        $arr = $_POST;
        $notify_result = array(
            'trade_status' => '0',
        );
        $aop = new \AopClient();

        $aop->gatewayUrl =$this->config['gatewayUrl'];
        $aop->appId = $this->config['app_id'];
        $aop->rsaPrivateKey = $this->config['merchant_private_key'];
        $aop->alipayrsaPublicKey = $this->config['alipay_public_key'];
        $aop->apiVersion = '1.0';
        $aop->signType = 'RSA2';
        $aop->postCharset = 'utf-8';
        $aop->format = 'json';
        $result = $aop->rsaCheckV1($arr, $aop->alipayrsaPublicKey, $aop->signType);
        if ($result) {
            if ($arr['trade_status'] == 'TRADE_SUCCESS') {
                $out_trade_no = explode('-', input('param.out_trade_no'));
                $out_trade_no = $out_trade_no['1'];
                $notify_result = array(
                    'out_trade_no' => $out_trade_no, #商户订单号
                    'trade_no' => input('param.trade_no'), #交易凭据单号
                    'total_fee' => input('param.total_amount'), #涉及金额
                    'order_type' => input('param.body'),
                    'trade_status' => '1',
                );
            }
        }
        return $notify_result;
    }

    /**
     * 原路退款
     */
    public function trade_refund($order_info, $refund_amount)
    {
        require_once dirname(__FILE__) . '/aop/AopClient.php';
        require_once dirname(__FILE__) . '/aop/request/AlipayTradeRefundRequest.php';
        $aop = new \AopClient();

        $aop->gatewayUrl = $this->config['gatewayUrl'];
        $aop->appId = $this->config['app_id'];
        $aop->rsaPrivateKey = $this->config['merchant_private_key'];
        $aop->alipayrsaPublicKey =$this->config['alipay_public_key'];
        $aop->apiVersion = '1.0';
        $aop->signType = 'RSA2';
        $aop->postCharset = 'utf-8';
        $aop->format = 'json';
        $request = new AlipayTradeRefundRequest ();
        $request->setBizContent("{" .
            "\"out_request_no\":\"" . $order_info['out_request_no'] . "\"," .
            "\"trade_no\":\"" . $order_info['trade_no'] . "\"," .
            "\"refund_amount\":" . $refund_amount . "," .
            "\"refund_reason\":\"" . '订单退款' . "\"" .
            "  }");
        $result = $aop->execute($request);

        $responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
        $resultCode = $result->$responseNode->code;
        if (!empty($resultCode) && $resultCode == 10000) {
            return ds_callback(TRUE, $result->$responseNode->msg);
        } else {
            return ds_callback(FALSE, $result->$responseNode->sub_msg);
        }
    }


    /**
     *
     * 交易关闭
     * @param $out_trade_no 商户订单号，商户网站订单系统中唯一订单号
     * @param $trade_no  支付宝交易号
     * @return mixed
     */
    public function close($out_trade_no, $trade_no)
    {
        require_once dirname(__FILE__) . '/aop/AopClient.php';
        require_once dirname(__FILE__) . '/aop/request/AlipayTradeCloseRequest.php';
        //构造参数
        $bizContentarr = array();
        $bizContentarr['out_trade_no'] = $out_trade_no;
        $bizContentarr['trade_no'] = $trade_no;
        if (!empty($bizContentarr)) {
            return;
        };
        $biz_content = json_encode($bizContentarr, JSON_UNESCAPED_UNICODE);
        $request = new AlipayTradeCloseRequest();
        $request->setBizContent($biz_content);
        $aop = new \AopClient();
        $aop->gatewayUrl =$this->config['gatewayUrl'];
        $aop->appId = $this->config['app_id'];
        $aop->rsaPrivateKey = $this->config['merchant_private_key'];
        $aop->alipayrsaPublicKey = $this->config['alipay_public_key'];
        $aop->apiVersion = '1.0';
        $aop->signType = 'RSA2';
        $aop->postCharset = 'utf-8';
        $aop->format = 'json';
        $result = $aop->Execute($request);
        $response = $result->alipay_trade_close_response;
        return $response;
    }
}