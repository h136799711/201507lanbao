<?php
/**
 * Created by PhpStorm.
 * User: hebidu
 * Date: 15/7/29
 * Time: 20:14
 */

namespace Test\Controller;


use Api\Service\OAuth2ClientService;
use Test\Org\AES;
use Think\Controller;
use Test\Org\DES;

class TestBicyledataController extends Controller{

    public function __construct(){
        parent::__construct();
        $client_id = C('CLIENT_ID');
        $client_secret = C('CLIENT_SECRET');
        $config = array(
            'client_id'=>$client_id,
            'client_secret'=>$client_secret,
            'site_url'=>C("SITE_URL"),
        );
        $client = new OAuth2ClientService($config);
        $access_token = $client->getAccessToken();
//        dump($access_token);
//        exit();
        if($access_token['status']){
            $this->assign("access_token",$access_token['info']['access_token']);
        }
        $this->assign("error",$access_token);
    }

    public function index(){
        $rawText = I('post.rawtext','');
        $text = I('post.text','');
//        dump(base64_encode("hebidu"));
//        dump(base64_decode("aGViaWR1"));

//        $privateKey = "hebidu";
//        $iv     = "";
//        $string = "hebidu";
        $key = "136799711";
//        dump(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $privateKey, $string, MCRYPT_MODE_CBC, $iv));
        //IOS AES 加密
        //kCCOptionPKCS7Padding | kCCOptionECBMode,
//        $encodeStr = "uYjVy+FbJqXi/Ep+9OtaXA==";
        //kCCOptionPKCS7Padding | kCCOptionECBMode,
        //uYjVy+FbJqXi/Ep+9OtaXA==
        $encodeStr = "";
        if(IS_POST && (!empty($rawText) || !empty($text))) {
            if(!empty($rawText)){
                dump($rawText);
                $encodeStr=AES::encrypt($rawText,$key);
                $this->assign("text",$encodeStr);
                dump($encodeStr);
            }else{
                dump($text);
                $encodeStr=AES::decrypt($text,$key);
                dump($encodeStr);
                $this->assign("rawtext",$encodeStr);
            }
        }
//
//        $encode = AES::encrypt($string,$key);
//        dump($encode);
//        $encode = AES::decrypt($encode,$key);
//        dump($encode);
        $this->display();
//        $encode="o83EVYqKiopZVlReXUw=";
//        $encode = $crypt->AESDecryptCtr($encode,$key,128);
//        dump($encode);
//        $encode="o83EVYqKiopZVlReXUw=";
//        $crypt = new Des();
//        $encode = $crypt->encrypt($string,$key,true);
//        $decode = $crypt->decrypt($encode,$key,true);
//        echo $encode;
//        echo "<br />";
//        echo $decode;
//
//        $decode = $crypt->decrypt($encodeStr,$key,true);
//        echo "<br />";
//        echo $decode;

    }
    /*
    *功能：对字符串进行解密处理
    *参数一：需要解密的密文
    *参数二：密钥
    */
    function passport_decrypt($str,$key){ //解密函数
        $str=$this->passport_key(base64_decode($str),$key);
        $tmp='';
        for($i=0;$i<strlen($str);$i++){
            $md5=$str[$i];
            $tmp.=$str[++$i] ^ $md5;
        }
        return $tmp;
    }

    function passport_key($str,$encrypt_key){
        $encrypt_key=md5($encrypt_key);
        $ctr=0;
        $tmp='';
        for($i=0;$i<strlen($str);$i++){
            $ctr=$ctr==strlen($encrypt_key)?0:$ctr;
            $tmp.=$str[$i] ^ $encrypt_key[$ctr++];
        }
        return $tmp;
    }
    public function testAdd(){
        $this->display();
    }
    public function testDay(){
        $this->display();
    }

    public function testMonth(){
        $this->display();
    }

    public function testMax(){
        $this->display();
    }
    public function testTotal(){
        $this->display();
    }

}