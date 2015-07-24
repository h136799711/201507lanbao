<?php
/**
 * Created by PhpStorm.
 * User: hebidu
 * Date: 15/7/3
 * Time: 09:45
 */

namespace Test\Controller;

use Api\Service\OAuth2ClientService;
use Common\Api\AccountApi;
use Think\Controller\RestController;
use Uclient\Model\OAuth2TypeModel;

/**
 *
 *
 * @Test AccountApi
 * Class TestAccountApiController
 * @package Test\Controller
 *
 */
class TestFileApiController extends RestController {

    public function __construct(){
        parent::__construct();

        $client_id = C('CLIENT_ID');
        $client_secret = C('CLIENT_SECRET');
        $config = array(
            'client_id'=>$client_id,
            'client_secret'=>$client_secret,
        );
        $client = new OAuth2ClientService($config);
        $access_token = $client->getAccessToken();
        if($access_token['status']){
            $this->assign("access_token",$access_token['info']);
        }
        $this->assign("error",$access_token);
    }

    public function testUpload(){
        $this->display();
    }

}