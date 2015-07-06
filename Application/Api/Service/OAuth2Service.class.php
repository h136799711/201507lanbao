<?php
/**
 * Created by PhpStorm.
 * User: hebidu
 * Date: 15/7/6
 * Time: 15:10
 */
namespace Test\Api;

//use OAuth2\Autoloader;
//use OAuth2\Server;
//use OAuth2\Storage\Pdo;

use OAuth2\Autoloader;
use OAuth2\GrantType\ClientCredentials;
use OAuth2\GrantType\UserCredentials;
use OAuth2\OpenID\GrantType\AuthorizationCode;
use OAuth2\Server;
use OAuth2\Storage\Pdo;

class ServerApi {

    private $dsn;
    private $uname;
    private $pwd ;

    private $server;

    public function __construct(){
        $dbname = C('DB_NAME');
        $host = C('DB_HOST');
        $this->dsn = 'mysql:dbname='.$dbname.';host='.$host;
        $this->uname = C('DB_USER');//"root";
        $this->pwd = C('DB_PWD');//"1";
    }

    public function init(){


        ini_set('display_errors',1);
        error_reporting(E_ALL);

        vendor("OAuth2.Autoloader");
        $autoloader = new Autoloader();
        $autoloader::register();

        //array(
//        'client_table' => 'oauth_clients',
//            'access_token_table' => 'oauth_access_tokens',
//            'refresh_token_table' => 'oauth_refresh_tokens',
//            'code_table' => 'oauth_authorization_codes',
//            'user_table' => 'oauth_users', //用户表
//            'jwt_table'  => 'oauth_jwt',
//            'jti_table'  => 'oauth_jti',
//            'scope_table'  => 'oauth_scopes',
//            'public_key_table'  => 'oauth_public_keys',
//        )
        //数据库存储
        $storage = new Pdo(array('dsn' => $this->dsn, 'username' => $this->uname, 'password' => $this->pwd),array(C('DB_PREFIX').'ucenter_member'));

        // Pass a storage object or array of storage objects to the OAuth2 server class
        $server = new Server($storage,array('allow_implicit' => true));

        // Add the "Client Credentials" grant type (it is the simplest of the grant types)
        $server->addGrantType(new ClientCredentials($storage,array(
            // this request will only allow authorization via the Authorize HTTP Header (Http Basic)
            //
            'allow_credentials_in_request_body => false'
        )));

        // Add the "Authorization Code" grant type (this is where the oauth magic happens)
        $server->addGrantType(new AuthorizationCode($storage));

        // add the grant type to your OAuth server
        $server->addGrantType(new UserCredentials($storage));



        return $server;
    }

//    public function addGrantTypeUserCredentials(){
//
//        $this->server->addGrantType(new UserCredentials($this->storage));
//    }


}