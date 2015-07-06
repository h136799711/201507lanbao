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
use OAuth2\OpenID\GrantType\AuthorizationCode;
use OAuth2\Server;
use OAuth2\Storage\Pdo;

class ServerApi {

    private $dsn;
    private $uname;
    private $pwd ;

    public function __construct(){
        $this->dsn = 'mysql:dbname=itboye_lanbao;host=localhost';
        $this->uname = "root";
        $this->pwd = "1";
    }

    public function init(){


        ini_set('display_errors',1);
        error_reporting(E_ALL);

        vendor("OAuth2.Autoloader");
//        import("Vendor.OAuth2.Autoloader",THINK_PATH."Library/Vendor/OAuth2/",".php");
        $autoloader = new Autoloader();
//        var_dump($autoloader);
        $autoloader::register();

        $storage = new Pdo(array('dsn' => $this->dsn, 'username' => $this->uname, 'password' => $this->pwd));

        // Pass a storage object or array of storage objects to the OAuth2 server class
        $server = new Server($storage);

        // Add the "Client Credentials" grant type (it is the simplest of the grant types)
        $server->addGrantType(new ClientCredentials($storage,array(
            // this request will only allow authorization via the Authorize HTTP Header (Http Basic)
            //
            'allow_credentials_in_request_body => false'
        )));

        // Add the "Authorization Code" grant type (this is where the oauth magic happens)
        $server->addGrantType(new AuthorizationCode($storage));

        return $server;
    }

}