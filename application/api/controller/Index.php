<?php
namespace app\api\controller;

use think\Db;

class Index
{
	public function index(){
		var_dump(empty('fd'));
	}
	

    /**
     * 查询分类栏目
     */
    function getColumn(){

    	$res=Db::table('category')->select();

    	return json(generateTree($res));

    }

    /**
     * 文章列表
     */
    function getArticleList(){

        $where=[];
        if(!empty(input('id'))){
            $where['category_cid']=input('id');
        }
    	$res=Db::table('article')->where($where)->order('addtime desc')->field('aid,title,description,addtime')->select();
        $len=count($res);

        for($i=0;$i<$len;$i++){
            $res[$i]['addtime']=date('Y-m-d H:m:s',$res[$i]['addtime']);
            $img=Db::table('images')->where(['belongId'=>$res[$i]['aid']])->field('url')->find();
            $res[$i]['imgurl']=$img['url'];
        }

    	return json($res);

    }

    /**
     * 获取单篇文章
     */
    function getAnArticle(){

        $aid=input('aid');

        if(empty($aid)){
            return json([
                'errormsg'=>'关键字段不能为空',
                'errorcode'=>'10005',
                'data'=>null
            ]);
        }

        //文章
        $res=Db::table('article')->where(['aid'=>$aid])->field('title,content,addtime')->find();
        $res['addtime']=date('Y-m-d H:m:s');

        //文章图片
        //$imgres=Db::table('images')->where(['belongId'=>$aid])->field('url,name,imgid')->select();
        //$res['imglist']=$imgres;

        if($res){
            return json($res);
        }else{
            return json([
                'errormsg'=>'文章获取失败',
                'errorcode'=>'10003',
                'data'=>$res
            ]);
        }
    }


}
