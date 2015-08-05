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
class TestAccountApiController extends RestController {

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
        if($access_token['status']){
            $this->assign("access_token",$access_token['info']);
        }
        $this->assign("error",$access_token);
    }

    public function testLogin(){
        $this->display();
    }

    public function testRegister(){
        $this->display();
    }

    public function testUpdate(){
        $this->display();
    }


    /**
     *
     */
    public  function index(){
        import("Org.String");

        $username = \String::randString(9,0);

        $password = \String::randString(6);

        $entity = [
            'username'=>$username,
            'password'=>$password,
            'from'=>OAuth2TypeModel::OTHER_APP,
            'email'=>'',
            'phone'=>'',
        ];

//        $result =  AccountApi::REGISTER($entity);
        
//        $this->ajaxReturn($result,"xml");
    }

}