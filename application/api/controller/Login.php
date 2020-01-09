<?php
namespace app\api\controller;


use think\Db;
session_start();

class Login
{


	public function index(){

        $username=input('username');
        $password=input('password');

        $res=Db::table('admin')->where(['username'=>$username])->find();

        if($res['password']==md5($password)){
            $_SESSION['username']=$username;
            return json([
    			'errormsg'=>'登录成功',
    			'errorcode'=>'0',
    			'data'=>null
    		]);
        }else{
        	return json([
    			'errormsg'=>'密码不匹配',
    			'errorcode'=>'20001',
    			'data'=>null
    		]);
        }

	}

}


?>