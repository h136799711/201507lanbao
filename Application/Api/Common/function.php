<?php
/**
 * Created by PhpStorm.
 * User: hebidu
 * Date: 15/7/7
 * Time: 16:23
 * @param $api_uri
 * @param $get
 * @param $post
 * @param $notes
 * @internal param $info
 */


function addLog($api_uri,$get,$post,$notes){
    $model = M('ApiCallHis');

    if(is_array($get)){
        $get = json_encode($get);
    }
    if(is_array($post)){
        $post = json_encode($post);
    }

    $result = $model->create(array(
        'api_uri'=>$api_uri,
        'call_get_args'=>$get,
        'call_post_args'=>$post,
        'notes'=>$notes,
        'call_time'=>NOW_TIME,
    ));

    if($result){
        $model->add();
    }
}

/**
 * 加密
 */
function encrypt($data,$key){

}


/**
 * 解密
 */
function decrypt($data,$key){

}