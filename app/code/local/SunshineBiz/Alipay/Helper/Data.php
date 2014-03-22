<?php

/**
 * SunshineBiz_Alipay Data Helper
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Alipay
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Alipay_Helper_Data extends Mage_Core_Helper_Abstract {

    const GATEWAY = 'https://mapi.alipay.com/gateway.do';

    public function getUserInfo() {

        $alipayCustomer = Mage::getModel('alipay/customer')
                ->getCollection()
                ->addFieldToFilter('customer_id', Mage::getSingleton('customer/session')->getCustomer()->getId())
                ->getFirstItem();

        if ($alipayCustomer->getExpiredTime() > time()) {
            return $alipayCustomer;
        }

        return NULL;
    }

    public function queryTimestamp($clientId) {
        $url = self::GATEWAY . "?service=query_timestamp&partner=" . $clientId . "&_input_charset=utf-8";

        $doc = new DOMDocument();
        $doc->load($url);
        $itemEncrypt_key = $doc->getElementsByTagName("encrypt_key");
        $encrypt_key = $itemEncrypt_key->item(0)->nodeValue;

        return $encrypt_key;
    }

    public function getRequestUrl($params) {
        $request_data = $this->createLinkstringUrlencode($params);

        return self::GATEWAY . '?' . $request_data;
    }    

    public function buildRequestUrl($params, $keys) {
        //待请求参数数组
        $para = $this->buildRequestParams($params, $keys);

        //把参数组中所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串，并对字符串做urlencode编码
        $request_data = $this->createLinkstringUrlencode($para);

        return self::GATEWAY . '?' . $request_data;
    }

    public function verifySign($params, $keys) {
        //除去待签名参数数组中的空值和签名参数
        $para_filter = $this->paramsFilter($params);

        //对待签名参数数组排序
        $para_sort = $this->paramsSort($para_filter);

        //把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
        $prestr = $this->createLinkstring($para_sort);

        $isSgin = false;
        switch ($params['sign_type']) {
            case "MD5" :
                $isSgin = $this->md5Verify($prestr, $params['sign'], $keys);
                break;
            default :
                $isSgin = false;
        }

        return $isSgin;
    }

    public function verifyNotify($partner, $notifyId) {
        $veryfy_url = self::GATEWAY . '?service=notify_verify&partner=' . $partner . '&notify_id=' . $notifyId;
        return $this->getHttpGetResponse($veryfy_url);
    }

    /**
     * 生成要请求给支付宝的参数数组
     * @param $para_temp 请求前的参数数组
     * @return 要请求的参数数组
     */
    public function buildRequestParams($params, $keys) {
        //除去待签名参数数组中的空值和签名参数
        $para_filter = $this->paramsFilter($params);

        //对待签名参数数组排序
        $para_sort = $this->paramsSort($para_filter);

        //签名结果与签名方式加入请求提交参数组中
        $signType = (isset($params['sign_type']) && $params['sign_type']) ? $params['sign_type'] : 'MD5';
        $para_sort['sign'] = $this->paramsSign($para_sort, $signType, $keys);
        $para_sort['sign_type'] = $signType;

        return $para_sort;
    }

    /**
     * 除去数组中的空值和签名参数
     * @param $para 签名参数组
     * return 去掉空值与签名参数后的新签名参数组
     */
    protected function paramsFilter($params) {
        $para_filter = array();
        while (list ($key, $val) = each($params)) {
            if ($key == "sign" || $key == "sign_type" || $val == "")
                continue;
            else
                $para_filter[$key] = $params[$key];
        }

        return $para_filter;
    }

    /**
     * 对数组排序
     * @param $para 排序前的数组
     * return 排序后的数组
     */
    protected function paramsSort($params) {
        ksort($params);
        reset($params);
        return $params;
    }

    /**
     * 生成签名结果
     * @param $para_sort 已排序要签名的数组
     * return 签名结果字符串
     */
    protected function paramsSign($params, $signType, $keys) {
        //把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
        $prestr = $this->createLinkstring($params);
        switch ($signType) {
            case 'MD5' :
                return md5($prestr . $keys);
            default :
                return '';
        }
    }

    /**
     * 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
     * @param $para 需要拼接的数组
     * return 拼接完成以后的字符串
     */
    protected function createLinkstring($params) {
        $arg = "";
        while (list ($key, $val) = each($params)) {
            $arg.=$key . "=" . $val . "&";
        }
        //去掉最后一个&字符
        $arg = substr($arg, 0, count($arg) - 2);

        //如果存在转义字符，那么去掉转义
        if (get_magic_quotes_gpc()) {
            $arg = stripslashes($arg);
        }

        return $arg;
    }

    /**
     * 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串，并对字符串做urlencode编码
     * @param $para 需要拼接的数组
     * return 拼接完成以后的字符串
     */
    protected function createLinkstringUrlencode($para) {
        $arg = "";
        while (list ($key, $val) = each($para)) {
            $arg.=$key . "=" . urlencode($val) . "&";
        }
        //去掉最后一个&字符
        $arg = substr($arg, 0, count($arg) - 2);

        //如果存在转义字符，那么去掉转义
        if (get_magic_quotes_gpc()) {
            $arg = stripslashes($arg);
        }

        return $arg;
    }

    /**
     * 验证签名
     * @param $prestr 需要签名的字符串
     * @param $sign 签名结果
     * @param $key 私钥
     * return 签名结果
     */
    protected function md5Verify($prestr, $sign, $keys) {

        if (md5($prestr . $keys) == $sign) {
            return true;
        } else {
            return false;
        }
    }

    protected function getHttpGetResponse($url) {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, 0); // 过滤HTTP头
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 显示输出结果
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true); //SSL证书认证
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); //严格认证
        curl_setopt($curl, CURLOPT_CAINFO, getcwd() . '\\cacert.pem'); //证书地址
        $responseText = curl_exec($curl);
        //var_dump( curl_error($curl) );//如果执行curl过程中出现异常，可打开此开关，以便查看异常内容
        curl_close($curl);

        return $responseText;
    }
    
    public function getHttpPostResponse($url, $params) {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_CAINFO, getcwd() . '\\cacert.pem');
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
        $responseText = curl_exec($curl);
        //var_dump( curl_error($curl) );//如果执行curl过程中出现异常，可打开此开关，以便查看异常内容
        curl_close($curl);

        return $responseText;
    }

    public function connectByCreatingAccount($email, $realName) {

        $customer = Mage::getModel('customer/customer');

        $customer->setWebsiteId(Mage::app()->getWebsite()->getId())
                ->setEmail($email)
                ->setFirstname($realName)
                ->setLastname($realName)
                ->setPassword($customer->generatePassword(10))
                ->save();

        $customer->setConfirmation(null);
        $customer->save();

        $customer->sendNewAccountEmail('confirmed', '', Mage::app()->getStore()->getId());

        Mage::getSingleton('customer/session')->setCustomerAsLoggedIn($customer);

        return $customer;
    }

    public function saveCustomer($alipayCustomer, $userInfo) {
        $alipayCustomer->setName($userInfo['real_name']);
        $alipayCustomer->setEmail($userInfo['email']);
        $alipayCustomer->setUserGrade($userInfo['user_grade']);
        $alipayCustomer->setGradeType($userInfo['user_grade_type']);
        $alipayCustomer->setGmtDecay($userInfo['gmt_decay']);
        $alipayCustomer->setToken($userInfo['token']);
        $alipayCustomer->setExpiredTime($userInfo['expired_time']);

        if (!$alipayCustomer->getCreatedAt())
            $alipayCustomer->setCreatedAt(Mage::getSingleton('core/date')->gmtDate());

        $alipayCustomer->setUpdatedAt(Mage::getSingleton('core/date')->gmtDate());

        $alipayCustomer->save();
    }

}
