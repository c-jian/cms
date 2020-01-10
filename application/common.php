<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

use think\Db;

use Nette\Mail\Message;
use Nette\Mail\SmtpMailer;
use Nette\Utils\AssertionException;


function getConfig($type){

    $ConfigPath=$_SERVER['DOCUMENT_ROOT'].'config/'.$type.'.config';
    $config=false;
    if(is_dir($ConfigPath)){
        $config=file_get_contents($ConfigPath);
    }
    
    if($config){
        $res=$config;
    }else{
        $res=Db::table('config')->field('config_value')->where(['config_name'=>$type.'_config'])->find()['config_value'];
    }
    return $res;

}


function SendEmail($to='',$title='',$con='',$attachment=null){

    if($to==='' || $title==='')return false;

    $config=getConfig('email');
    $EmailConfig=json_decode($config,true);
    
    $mail = new Message();
    try {
        $mail->setFrom('Kris '.'<'.$EmailConfig['SenderEmailAccount'].'>')
            ->addTo($to)
            ->setSubject($title)
            ->setHTMLBody($con);

        if($attachment){
          $mail->addAttachment($attachment);  
        }

        $mailer = new SmtpMailer([
            'host'     => $EmailConfig['SMTPServerAddress'],
            'username' => $EmailConfig['SenderEmailAccount'],
            'password' => $EmailConfig['SenderEmailPassword'],
            //'secure' => $EmailConfig['SendMode'],
            'port' => $EmailConfig['ServerPort']
        ]);
        $mailer->send($mail);

        return true;
    } catch (AssertionException $e) {
        return false;
    }

}

function getTree($arr,$pid=0,$level=0)
{
    static $list = [];
    foreach ($arr as $key => $value) {
        if ($value["pid"] == $pid) {
            $value["level"] = $level;
            $list[] = $value;
            unset($arr[$key]); //删除已经排好的数据为了减少遍历的次数，当然递归本身就很费神就是了
            getTree($arr,$value["id"],$level+1);
        }
    }
    return $list;
}


function generateTree($data){
    $items = array();
    foreach($data as $v){
        $items[$v['id']] = $v;
    }
    $tree = array();
    foreach($items as $k => $item){
        if(isset($items[$item['pid']])){
            $items[$item['pid']]['children'][] = &$items[$k];
        }else{
            $tree[] = &$items[$k];
        }
    }
    return $tree;
}

function ServerURL(){
    $http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
    return $http_type.$_SERVER['HTTP_HOST'];
}