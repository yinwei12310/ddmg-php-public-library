<?php
namespace Ddmg;

/**
 * Created by PhpStorm.
 * User: Yinwei
 * Date: 2020/9/8
 * Time: 20:48
 */
class HttpCurlRequest{

    /**
     * @param $url
     * @param array $data
     * @param array $header
     * @param null $proxy
     * @param int $expire
     * @param bool $isBigdata
     * @return bool|string|null
     */
    public static function postRequest( $url, $data=[], $header=[], $proxy=null, $expire=36000 )
    {
        try {
            if ( !$url ) {
                return false;
            }

            $ch = curl_init();
            curl_setopt( $ch, CURLOPT_URL, $url );

            // 设置代理
            if ( !is_null($proxy) ){
                curl_setopt ( $ch, CURLOPT_PROXY, $proxy );
            }

            $isSSL = substr($url, 0, 8) == 'https://' ? true : false;
            if ( $isSSL ) {
                curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 );// 对认证证书来源的检查
                curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 1 );// 从证书中检查SSL加密算法是否存在
            }

            // 设置浏览器
            curl_setopt( $ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT'] );
            curl_setopt( $ch, CURLOPT_HEADER, 0 );

            // 设置请求header
            if ( !empty($header) ) {
                curl_setopt( $ch, CURLOPT_HTTPHEADER, $header );
            }

            // POST发送数据
            curl_setopt( $ch, CURLOPT_POST, true );//发送一个常规的Post请求
            curl_setopt( $ch, CURLOPT_POSTFIELDS, $data );//Post提交的数据包

            // 使用自动跳转
            curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1 );
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
            curl_setopt( $ch, CURLOPT_TIMEOUT, $expire ); // 设置cURL允许执行的最长秒数。

            // 执行发送CURL
            $response = curl_exec( $ch );

            if( curl_errno($ch) ) {
                $tips_curl_error = curl_error($ch);
            }

            $httpCode = curl_getinfo( $ch, CURLINFO_HTTP_CODE );

            if ( $httpCode != 200 ) {
                return false;
            }
            curl_close( $ch );

            return $response;
        } catch (\Exception $e) {
            return NULL;
        }
    }


}