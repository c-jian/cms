<?php
namespace app\api\controller;

use think\Db;
use think\Request;
use PHPExcel_IOFactory;
use PHPExcel;
use think\Env;


session_start();
class Admin
{
    public function __construct(){

        // if(!isset($_SESSION['username'])){
        //     $this->redirect('/admin/login.html',302);
        // }

        header('Access-Control-Allow-Origin: * ');
        header('Access-Control-Allow-Methods:OPTIONS, GET, POST, PUT, DELETE');
        header('Access-Control-Allow-Headers:x-requested-with');

    }

    /**
     * $url:重定向地址
     * $permanent:是否301重定向，否则302重定向
     */
    private function redirect($url,$permanent=false){
        if(headers_sent()===false){
            header('Location: ' . $url, true, ($permanent === true) ? 301 : 302);
            exit;
        }
    }

    //处理Excel
    public function excel(){

        header("content-type:text/html;charset=utf-8");
        //上传excel文件
        $file = request()->file('file');

        //将文件保存到public/uploads目录下面
        $info = $file->validate(['size'=>1048576,'ext'=>'xls,xlsx'])->move( './uploads');
        if($info){
            //获取上传到后台的文件名
            $fileName = $info->getSaveName();
            //获取文件路径
            $filePath = Env::get('root_path').'public'.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.$fileName;
            //获取文件后缀
            $suffix = $info->getExtension();
            //判断哪种类型
            if($suffix=="xlsx"){
                $reader = \PHPExcel_IOFactory::createReader('Excel2007');
            }else{
                $reader = PHPExcel_IOFactory::createReader('Excel5');
            }
        }else{
            $this->error('文件过大或格式不正确导致上传失败-_-!');
        }
        //载入excel文件
        $excel = $reader->load("E:/myproject/blog/public/uploads/20191223/84009346a88b9776d2839c697ecd3bbc.xls",$encode = 'utf-8');
        //读取第一张表
        $sheet = $excel->getSheet(0);
        //获取总行数
        $row_num = $sheet->getHighestRow();
        //获取总列数
        $col_num = $sheet->getHighestColumn();
        $data = []; //数组形式获取表格数据
        for ($i = 2; $i <= $row_num; $i ++) {
            $data[$i]['b']  = $sheet->getCell("B".$i)->getValue();
            $data[$i]['c']  = $sheet->getCell("C".$i)->getValue();
            $data[$i]['d']  = $sheet->getCell("D".$i)->getValue();
            //$time = date('Y-m-d H:i',\PHPExcel_Shared_Date::ExcelToPHP($sheet->getCell("B".$i)->getValue()));
            //$data[$i]['time'] = strtotime($time);
            //将数据保存到数据库
        }
        //var_dump($data);
        $res = Db::name('excel')->insertAll($data);
        if($res){
            return redirect('/');
        }else{
            $return = [
                'code'  =>  0,
                'msg'   =>  '提交失败，请刷新重试'
            ];
            return json($return);
        }

    }

	/**
	 * 添加分类栏目
	 */
    public function addColumn()
    {
        
    	$pid=input('pid');
    	$cname=input('cname');
    	$sort=input('sort') || 0;
        $model_id=input('model_id');

    	if($pid==''){
    		return json([
    			'errormsg'=>'请选择栏目分类',
    			'errorcode'=>'10001',
    			'data'=>null
    		]);
    	}

    	if(empty($cname)){
    		return json([
    			'errormsg'=>'栏目名称不能为空',
    			'errorcode'=>'10002',
    			'data'=>null
    		]);
    	}

    	$res=Db::table('category')->insert(['cname'=>$cname,'pid'=>$pid,'sort'=>$sort,'model_id'=>$model_id]);

    	if($res){
    		return json([
    			'errormsg'=>'添加成功',
    			'errorcode'=>'0',
    			'data'=>$res
    		]);
    	}else{
    		return json([
    			'errormsg'=>'添加失败',
    			'errorcode'=>'10003',
    			'data'=>$res
    		]);
    	}

    }

    function DeleteColumn(){

        $id=input('id');

        if($id==''){
            return json([
                'errormsg'=>'唯一标识符不能为空',
                'errorcode'=>'1',
                'data'=>null
            ]);
        }

        $res=Db::table('category')->where(['id'=>$id])->delete();

        if($res){
            return json([
                'errormsg'=>'操作成功',
                'errorcode'=>'0',
                'data'=>$res
            ]);
        }else{
            return json([
                'errormsg'=>'操作失败',
                'errorcode'=>'10003',
                'data'=>$res
            ]);
        }

    }

    /**
     * 查询分类栏目
     */
    function GetColumn(){

        $mid=input('mid');

        $where = !empty($mid) ? ['model_id'=>$mid] : [];

    	$res=Db::table('category')->where($where)->select();

    	return json(generateTree($res));

    }

    /**
     * 获取父级栏目
     */
    function GetParentColumn(){

        $pid=input('pid');

        $res=Db::table('category')->where(['pid'=>$pid])->select();

        foreach ($res as $key => $value) {
            $res[$key]['icon']=Db::table('column_module')->where(['id'=>$value['model_id']])->find()['icon'];
        }

        return json($res);
    }

    //@获取子栏目ID号
    function sonCategoryIds($categoryID=1){
        //@初始化栏目数组
        $array[] = $categoryID;

        do{

            $ids = '';
            $temp = Db::table('category')->where(['pid'=>['in',$categoryID]])->select();
                foreach ($temp as $v){
                    $array[] = $v['id'];
                    $ids .= ',' . $v['id'];
                }
            $ids = substr($ids, 1, strlen($ids));
            $categoryID = $ids;

        }while (!empty($temp));

        $ids = implode(',', $array);
        return $ids;
    }


    /**
     * 添加/更新文章
     */
    function Article(){

        if(!Request::instance()->isPost()){
            die('错误的请求类型');
        }

        $data=$_POST;

        /*//表单参数
        $configArr=['ArticleAuthor','ArticleContent','ArticleDescription','ArticleImage','ArticlePositionRecommend','ArticlePositionIndex','ArticlePositionTop','ArticleSource','ArticleState','ArticleTitle','SEODescription','SEOKeyword','SEOTitle','category_id','aid'];

        //检验表单参数
        foreach ($data as $key => $value) {
            if(!in_array($key, $configArr))die('不合法的表单参数: '.$key);
        }

        if(!isset($data['ArticlePositionIndex']))$data['ArticlePositionIndex']='';
        if(!isset($data['ArticlePositionRecommend']))$data['ArticlePositionRecommend']='';
        if(!isset($data['ArticlePositionTop']))$data['ArticlePositionTop']='';*/

        $model_id=$data['model_id'];

        if(empty($model_id)){
            return json([
                'errormsg'=>'缺少模型标识',
                'errorcode'=>'1',
                'data'=>$model_id
            ]);
        }

        $ModelName=Db::table('column_module')->where(['id'=>$model_id])->find()['ModuleTableName'];

        $id=$data['id'];
        unset($data['model_id']);
        unset($data['id']);

        //$data['AddTime']=time();

        if(empty($id)){
           //添加文章
            $res=Db::table($ModelName)->insertGetId($data);
        }else{
            //更新文章
            $res=Db::table($ModelName)->where(['id'=>$id])->update($data);
        }

    	if($res){
    		//返回信息
    		return json([
    			'errormsg'=>'操作成功',
    			'errorcode'=>'0',
    			'data'=>$res
    		]);
    	}else{
    		return json([
    			'errormsg'=>'操作失败',
    			'errorcode'=>'10003',
    			'data'=>$res
    		]);
    	}
    }

    /**
     * 删除文章
     */
    function DeleteArticle(){

        $aid=input('aid');
        $mid=input('mid');

        $ModelData=Db::table('column_module')->where(['id'=>$mid])->find();

        if(strpos($aid,',')){
            $res=Db::table($ModelData['ModuleTableName'])->where(['id'=>['in',$aid]])->delete();//批量删除
        }else{
            $res=Db::table($ModelData['ModuleTableName'])->where(['id'=>$aid])->delete();//单条删除
        }

        if($res){
            return json([
                'errormsg'=>'文章删除成功',
                'errorcode'=>'0',
                'data'=>$res
            ]);
        }else{
            return json([
                'errormsg'=>'文章删除失败',
                'errorcode'=>'10003',
                'data'=>$res
            ]);
        }
    }

    /**
     * 文章列表
     */
    function GetArticle(){

        //模型id
        $mid=input('mid');

        //栏目id
        $cid=input('cid');


        //根据栏目id获取表名称
        $ModelData=Db::table('column_module')->where(['id'=>$mid])->find();

        if(!$ModelData){
            return json([
                'errormsg'=>'操作失败',
                'errorcode'=>'10003',
                'data'=>$ModelData
            ]);
        }

        $tableName=$ModelData['ModuleTableName'];

        $limit=input('limit');
        if(!$limit)$limit=50;
        $page=input('page');
        if(!$page)$page=1;

    	$count=Db::table($tableName)->count();
    	$res=Db::table($tableName)->limit($page-1,$limit)->select();

        // for($i=0,$len=count($res);$i<$len;$i++){
        //     $res[$i]['AddTime']=date('Y-m-d H:m:s',$res[$i]['AddTime']);
        // }

    	return json([
    		'msg'=>'',
    		'code'=>'0',
            'count'=>$count,
    		'data'=>$res
    	]);

    }

    /**
     * 获取单篇文章
     */
    function GetArticleById(){

        $aid=input('aid');
        $model_id=input('model_id');

        if(empty($aid) || empty($model_id)){
            return json([
                'errormsg'=>'文章id或模型id为空',
                'errorcode'=>'10005',
                'data'=>null
            ]);
        }

        $ModelData=Db::table('column_module')->where(['id'=>$model_id])->find();

        //文章
        $res=Db::table($ModelData['ModuleTableName'])->where(['id'=>$aid])->find();

        if($res){
            return json([
                'errormsg'=>'文章获取成功',
                'errorcode'=>'0',
                'data'=>$res
            ]);
        }else{
            return json([
                'errormsg'=>'文章获取失败',
                'errorcode'=>'10003',
                'data'=>$res
            ]);
        }
    }


    /**
     * 根据模型id获取字段信息
     */
    function GetFieldInfoByMid(){

        $mid=input('mid');

        if(empty($mid)){
            return json([
                'errormsg'=>'模型id不能为空',
                'errorcode'=>'10005',
                'data'=>null
            ]);
        }

        $FieldData=Db::table('field_management')->where(['model_id'=>$mid,'ShowInTableList'=>1])->order('sort asc')->select();

        return json([
            'errormsg'=>'模型字段信息',
            'errorcode'=>'0',
            'data'=>$FieldData
        ]);

    }


    /**
     * 上传图片处理
     */
    function uploadImages2(){

    	// 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('file');
        // 移动到框架应用根目录/public/uploads/ 目录下
        $info = $file->validate(['ext'=>'jpg,png,gif'])->move(ROOT_PATH . 'public' . DS . 'uploads');
        if($info){
           //成功上传后 获取上传信息
           //输出 jpg
           //echo $info->getExtension();
           //输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
           //echo $info->getSaveName();
           //输出 42a79759f284b767dfcb2a0197904287.jpg
           //echo $info->getFilename();
           //echo $info->pathName;
           //获取图片的存放相对路径
            $filePath = DS . 'uploads'.DS.$info->getSaveName();
            $getInfo = $info->getInfo();
            //获取图片的原名称
            $name = $getInfo['name'];
            //整理数据,写入数据库
            $data = [
                'url' => $filePath,
                'name' => $name,
                'type' => 1
            ];
            $affected = Db::table('images')->insertGetId($data);
            if($affected){
            	return json([
	    			'errormsg'=>'上传成功',
	    			'errorcode'=>'0',
	    			'data'=>$affected
	    		]);
            }
            
        }else{
            // 上传失败获取错误信息
            return json([
    			'errormsg'=>$file->getError(),
    			'errorcode'=>'10004',
    			'data'=>null
    		]);
        }

    }

    /**
     * 删除图片
     */
    function delImage(){

        $imgid=input('imgid');
        if(empty($imgid)){
            return json([
                'errormsg'=>'关键字段不能为空',
                'errorcode'=>'10005',
                'data'=>null
            ]);
        }

        $info=Db::table('images')->where(['imgid'=>$imgid])->find();

        $filepath=ROOT_PATH . 'public' .$info['url'];

        if(file_exists($filepath)){
            unlink($filepath);
        }

        $res=Db::table('images')->where(['imgid'=>$imgid])->delete();

        if($res){
            return json([
                'errormsg'=>'文章获取成功',
                'errorcode'=>'0',
                'data'=>$res
            ]);
        }else{
            return json([
                'errormsg'=>'文章获取失败',
                'errorcode'=>'10003',
                'data'=>$res
            ]);
        }

    }


    /**
     * 上传图片，并返回图片路径
     */
    function UploadImages(){

        //上传类型，用于决定保存到哪个目录
        $type=input('type');
        $dir='files';
        switch ($type) {
            case 'logo':
                $dir='logo'; //logo
            break;
            case 'ico':
                $dir='ico'; //网站图标
            break;
            case 'watermark':
                $dir='watermark'; //水印
            break;
            case 'FriendLink':
                $dir='FriendLink'; // 友情链接
            break;
            case 'Article':
                $dir='article'; //文章
            break;
        }

        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('file');
        // 移动到框架应用根目录/public/uploads/files 目录下
        $info = $file->validate(['ext'=>'jpg,png,gif,ico'])->move(ROOT_PATH . 'public' . DS . 'uploads'.DS.$dir);

        if($info){
           //获取图片的存放相对路径
            $filePath = DS.'uploads'.DS.$dir.DS.$info->getSaveName();
            return json([
                'errormsg'=>'上传成功',
                'errorcode'=>'0',
                'data'=>$filePath
            ]);
            
        }else{
            // 上传失败获取错误信息
            return json([
                'errormsg'=>$file->getError(),
                'errorcode'=>'10004',
                'data'=>null
            ]);
        }

    }

    /**
     * 更新网站信息
     */
    function updateWebsiteInfo(){


        if(!Request::instance()->isPost()){
            die('错误的请求类型');
        }

        $data=$_POST;

        //合法的表单名称
        $configArr=['WebsiteName','WebsiteKeyword','WebsiteDesc','WebsiteCopy','WebsiteICP','WebsiteAddress','WebsiteEmail','WebsitePhone','WebsiteContent','WebsiteLogo','WebsiteFavicon','newFavicon'];

        //判断上传的表单是否合法
        foreach ($data as $key => $value) {
            if(!in_array($key, $configArr))die('不合法的表单参数: '.$key);
        }


        //$favicon=$data['WebsiteFavicon']?'/favicon.ico':'';

        //更新网站信息
        $res=Db::table('config')->where(['config_name'=>'website_config'])->update(['config_value'=>json_encode($data,JSON_FORCE_OBJECT)]);

        // $operate=false;
        // if(empty($favicon)){
        //     $icopath=ROOT_PATH . 'public' . DS .'favicon.ico';
        //     if(file_exists($icopath)){
        //         $operate=unlink($icopath);
        //     }
        // }

        //将新上传的favicon文件移动到根目录
        if($data['newFavicon']){
            $operate=copy(ROOT_PATH . 'public' . $data['WebsiteFavicon'], ROOT_PATH . 'public' .DS. 'favicon.ico');
        }

        if($res || $operate){
            return json([
                'errormsg'=>'更新成功',
                'errorcode'=>'0',
                'data'=>$res
            ]);
        }else{
            return json([
                'errormsg'=>'更新失败',
                'errorcode'=>'10003',
                'data'=>$res
            ]);
        }

    }

    /**
     * 获取网站信息
     */
    function getWebsiteInfo(){

        $res=Db::table('config')->field('config_value')->where(['config_name'=>'website_config'])->find();
        if($res){
            return json([
                'errormsg'=>'获取成功',
                'errorcode'=>'0',
                'data'=>$res['config_value']
            ]);
        }else{
            return json([
                'errormsg'=>'获取失败',
                'errorcode'=>'10003',
                'data'=>$res
            ]);
        }

    }

    /**
     * 更新配置
     */
    function UpdateConfig(){

        if(!Request::instance()->isPost()){
            die('错误的请求类型');
        }

        $data=$_POST;

        if(!isset($data['type'])){
            return json([
                'errormsg'=>'缺少配置标识(type)',
                'errorcode'=>'10003',
                'data'=>$res
            ]);
        }

        switch ($data['type']) {

            case 'upload':
                //合法的表单名称
                $configArr=['UploadAudioMax','UploadAudioSuffix','UploadImageSuffix','UploadImagesMax','UploadOtherMax','UploadOtherSuffix','UploadVideoMax','UploadVideoSuffix'];
                $ColumnValue='upload_config';
            break;
            case 'thumbnail':
                //合法的表单名称
                $configArr=['ThumbnailType','ThumbnailProductWidth','ThumbnailProductHeight','ThumbnailArticleWidth','ThumbnailArticleHeight','ThumbnailImagesWidth','ThumbnailImagesHeight'];
                $ColumnValue='thumbnail_config';
            break;
            case 'watermark':
                //合法的表单名称
                $configArr=['WatermarkType','WatermarkPosition','WatermarkContent','WatermarkTransparency','WatermarkFontSize','WatermarkAngle','watermarkColor','WatermarkImage','WatermarkFontFamily','WatermarkSwitch'];
                $ColumnValue='watermark_config';
            break;
            case 'system':
                //合法的表单名称
                $configArr=['Editor','WebsiteCopy'];
                $ColumnValue='system_config';
            break;
            case 'email':
                $configArr=['Sender','SenderEmailAccount','SenderEmailPassword','SMTPServerAddress','ServerPort','WatermarkType','SendMode'];
                $ColumnValue='email_config';
            break;
            default:
                return json([
                    'errormsg'=>'配置标识(type)不匹配',
                    'errorcode'=>'10003',
                    'data'=>null
                ]);
            break;
        }

        $type=$data['type'];
        unset($data['type']);

        //判断上传的表单是否合法
        foreach ($data as $key => $value) {
            if(!in_array($key, $configArr))die('不合法的表单参数: '.$key);
        }

        
        //更新配置信息
        $res=Db::table('config')->where(['config_name'=>$ColumnValue])->update(['config_value'=>json_encode($data,JSON_FORCE_OBJECT)]);

        if($res){
            file_put_contents($_SERVER['DOCUMENT_ROOT'].'/config/'.$type.'.config', json_encode($data,JSON_FORCE_OBJECT));
            return json([
                'errormsg'=>'保存成功',
                'errorcode'=>'0',
                'data'=>$res
            ]);
        }else{
            return json([
                'errormsg'=>'保存失败',
                'errorcode'=>'10003',
                'data'=>$res
            ]);
        }

    }

    /**
     * 获取配置
     */
    public function GetConfig(){

        $type=input('type');

        if(empty($type)){
            return json([
                'errormsg'=>'缺少配置标识(type)',
                'errorcode'=>'10003',
                'data'=>$type
            ]);
        }

        switch ($type) {
            case 'upload':
                
            break;
            case 'thumbnail':
                
            break;
            case 'watermark':
                
            break;
            case 'system':
                
            break;
            case 'email':
                
            break;
            default:
                return json([
                    'errormsg'=>'配置标识(type)不匹配',
                    'errorcode'=>'1',
                    'data'=>null
                ]);
            break;
        }

        $res=getConfig($type);

        return json([
            'errormsg'=>'获取成功',
            'errorcode'=>'0',
            'data'=>$res
        ]);


    }

    /**
     * 添加/编辑友情链接
     */
    public function FriendLink(){

        if(!Request::instance()->isPost()){
            die('错误的请求类型');
        }

        $data=$_POST;

        //表单参数
        $configArr=['FriendLinkTitle','FriendType','URL','FriendNofollow','FriendSort','FriendState','FriendLinkIcon','id'];

        //检验表单参数
        foreach ($data as $key => $value) {
            if(!in_array($key, $configArr))die('不合法的表单参数: '.$key);
        }

        if(empty(input('id'))){
           //添加友情链接
            $res=Db::table('friend_link')->insert($data); 
        }else{
            unset($data['id']);
            //更新友情链接
            $res=Db::table('friend_link')->where(['id'=>input('id')])->update($data);
        }


        if($res){
            return json([
                'errormsg'=>'操作成功',
                'errorcode'=>'0',
                'data'=>$res
            ]);
        }else{
            return json([
                'errormsg'=>'操作失败',
                'errorcode'=>'10003',
                'data'=>$res
            ]);
        }

    }

    /**
     * 获取友链数据
     */
    public function GetFriendLink(){

        $count=Db::table('friend_link')->count();

        $data=Db::table('friend_link')->select();

        return json([
            'errormsg'=>'',
            'errorcode'=>'0',
            'total'=>$count,
            'data'=>$data
        ]);
    }


    /**
     * 获取一条友链数据
     */
    public function GetFriendLinkById(){

        $data=Db::table('friend_link')->where(['id'=>input('id')])->find();

        return json([
            'errormsg'=>'',
            'errorcode'=>'0',
            'data'=>$data
        ]);

    }

    /**
     * 删除友情链接
     */
    public function DeleteFriendLink(){

        $id=input('id');
        if(empty($id)){
            return json([
                'errormsg'=>'唯一标识符不能为空',
                'errorcode'=>'1',
                'data'=>$id
            ]);
        }

        if(strpos($id,',')){
            $data=Db::table('friend_link')->where(['id'=>['in',$id]])->delete();//批量删除
        }else{
            $data=Db::table('friend_link')->where(['id'=>$id])->delete();//单条删除
        }

        if($data){
            return json([
                'errormsg'=>'删除成功',
                'errorcode'=>'0',
                'data'=>$data
            ]);
        }else{
            return json([
                'errormsg'=>'删除失败',
                'errorcode'=>'1',
                'data'=>$data
            ]);
        }
        

    }

    /**
     * 添加/修改系统栏目
     */
    public function SystemColumn(){


        if(!Request::instance()->isPost()){
            die('错误的请求类型');
        }

        $data=$_POST;

        //表单参数
        $configArr=['SystemColumnName','SystemColumnURL','SystemState','SystemColumnSort','id','pid','SystemColumnIcon'];

        //检验表单参数
        foreach ($data as $key => $value) {
            if(!in_array($key, $configArr))die('不合法的表单参数: '.$key);
        }

        if(empty(input('id'))){
           //添加系统栏目
            $res=Db::table('sys_column')->insert($data);
        }else{
            unset($data['id']);
            //更新系统栏目
            $res=Db::table('sys_column')->where(['id'=>input('id')])->update($data);
        }


        if($res){
            return json([
                'errormsg'=>'操作成功',
                'errorcode'=>'0',
                'data'=>$res
            ]);
        }else{
            return json([
                'errormsg'=>'操作失败',
                'errorcode'=>'10003',
                'data'=>$res
            ]);
        }

    }

    /**
     * 获取系统栏目列表
     */
    public function GetSystemColumnList(){

        $data=getTree(Db::table('sys_column')->order('SystemColumnSort asc')->select());

        return json([
            'errormsg'=>'',
            'errorcode'=>'0',
            'data'=>$data
        ]);

    }

    /**
     * 查询系统栏目分类
     */
    function GetSystemColumn(){

        $res=Db::table('sys_column')->where(['SystemState'=>1])->select();

        return json(generateTree($res));

    }

    /**
     * 获取一条系统栏目
     */
    public function GetSystemColumnById(){

        $data=Db::table('sys_column')->where(['id'=>input('id')])->find();

        return json([
            'errormsg'=>'',
            'errorcode'=>'0',
            'data'=>$data
        ]);

    }


    /**
     * 删除系统栏目
     */
    public function DeleteSystemColumn(){

        $id=input('id');
        if(empty($id)){
            return json([
                'errormsg'=>'唯一标识符不能为空',
                'errorcode'=>'1',
                'data'=>$id
            ]);
        }

        if(strpos($id,',')){
            $data=Db::table('sys_column')->where(['id'=>['in',$id]])->delete();//批量删除
        }else{
            $data=Db::table('sys_column')->where(['id'=>$id])->delete();//单条删除
        }

        if($data){
            return json([
                'errormsg'=>'删除成功',
                'errorcode'=>'0',
                'data'=>$data
            ]);
        }else{
            return json([
                'errormsg'=>'删除失败',
                'errorcode'=>'1',
                'data'=>$data
            ]);
        }
        

    }

    
    /**
     * 添加/修改栏目类型
     */
    public function ColumnType(){


        if(!Request::instance()->isPost()){
            die('错误的请求类型');
        }

        $data=$_POST;

        if(empty(input('id'))){
           //添加栏目类型
            $res=Db::table('column_type')->insert($data);
        }else{
            unset($data['id']);
            //更新栏目类型
            $res=Db::table('column_type')->where(['id'=>input('id')])->update($data);
        }


        if($res){
            return json([
                'errormsg'=>'操作成功',
                'errorcode'=>'0',
                'data'=>$res
            ]);
        }else{
            return json([
                'errormsg'=>'操作失败',
                'errorcode'=>'10003',
                'data'=>$res
            ]);
        }

    }

    /**
     * 获取栏目类型列表
     */
    public function GetColumnTypeList(){

        $data=Db::table('column_type')->select();

        return json([
            'errormsg'=>'',
            'errorcode'=>'0',
            'data'=>$data
        ]);

    }


    /**
     * 获取一条栏目类型
     */
    public function GetColumnTypeById(){

        $id=input('id');
        if(empty($id)){
            return json([
                'errormsg'=>'唯一标识符不能为空',
                'errorcode'=>'1',
                'data'=>$id
            ]);
        }

        $data=Db::table('column_type')->where(['id'=>$id])->find();

        return json([
            'errormsg'=>'',
            'errorcode'=>'0',
            'data'=>$data
        ]);

    }


    /**
     * 删除栏目类型
     */
    public function DeleteColumnType(){

        $id=input('id');
        if(empty($id)){
            return json([
                'errormsg'=>'唯一标识符不能为空',
                'errorcode'=>'1',
                'data'=>$id
            ]);
        }

        if(strpos($id,',')){
            $data=Db::table('column_type')->where(['id'=>['in',$id]])->delete();//批量删除
        }else{
            $data=Db::table('column_type')->where(['id'=>$id])->delete();//单条删除
        }

        if($data){
            return json([
                'errormsg'=>'删除成功',
                'errorcode'=>'0',
                'data'=>$data
            ]);
        }else{
            return json([
                'errormsg'=>'删除失败',
                'errorcode'=>'1',
                'data'=>$data
            ]);
        }
        

    }


    /**
     * 添加/修改栏目模型
     */
    public function ColumnModule(){


        if(!Request::instance()->isPost()){
            die('错误的请求类型');
        }

        $data=$_POST;

        $id=$data['id'];
        if(isset($data['initValue']))$initValue=$data['initValue'];
        unset($data['id']);
        if(isset($data['initValue']))unset($data['initValue']);

        if(empty($id)){

            //创建表
            $sql="CREATE TABLE IF NOT EXISTS `$data[ModuleTableName]` (
                `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                `SEOTitle` VARCHAR (120) DEFAULT '' COMMENT 'SEO标题',
                `SEOKeyword` VARCHAR (160) DEFAULT '' COMMENT 'SEO关键词',
                `SEODescription` VARCHAR (180) DEFAULT '' COMMENT 'SEO描述',
                  PRIMARY KEY (`id`)
            );";
            try{
                //这里需要做错误处理（记录日志）
                Db::execute($sql);
            }catch(\Exception $e){

            }
            

            //添加栏目模型
            $res=Db::table('column_module')->insert($data);
            
        }else{
            
            //更新表
            if(isset($initValue) && $initValue!=$data['ModuleTableName']){
                $sql="ALTER TABLE `$initValue` RENAME `$data[ModuleTableName]`;";
                //这里需要做错误处理（记录日志）
                Db::execute($sql);
            }

            //更新栏目模型
            $res=Db::table('column_module')->where(['id'=>$id])->update($data);
        }


        if($res){
            return json([
                'errormsg'=>'操作成功',
                'errorcode'=>'0',
                'data'=>$res
            ]);
        }else{
            return json([
                'errormsg'=>'操作失败',
                'errorcode'=>'10003',
                'data'=>$res
            ]);
        }

    }

    /**
     * 获取栏目模型列表
     */
    public function GetColumnModuleList(){

        $data=Db::table('column_module')->order('sort asc')->select();

        return json([
            'errormsg'=>'',
            'errorcode'=>'0',
            'data'=>$data
        ]);

    }


    /**
     * 获取一条栏目模型
     */
    public function GetColumnModuleById(){

        $id=input('id');
        if(empty($id)){
            return json([
                'errormsg'=>'唯一标识符不能为空',
                'errorcode'=>'1',
                'data'=>$id
            ]);
        }

        $data=Db::table('column_module')->where(['id'=>$id])->find();

        return json([
            'errormsg'=>'',
            'errorcode'=>'0',
            'data'=>$data
        ]);

    }


    /**
     * 删除栏目模型
     */
    public function DeleteColumnModule(){

        $id=input('id');
        $ModuleTableName=input('ModuleTableName');
        if(empty($id)){
            return json([
                'errormsg'=>'唯一标识符不能为空',
                'errorcode'=>'1',
                'data'=>$id
            ]);
        }
        if(empty($ModuleTableName)){
            return json([
                'errormsg'=>'模型名称为空',
                'errorcode'=>'1',
                'data'=>$ModuleTableName
            ]);
        }

        $category=Db::table('category')->where(['id'=>$id])->find();
        if($category){
            return json([
                'errormsg'=>'当前模型还有栏目引用！',
                'errorcode'=>'1',
                'data'=>$category
            ]);
        }

        if(strpos($id,',')){
            //$data=Db::table('column_module')->where(['id'=>['in',$id]])->delete();//批量删除
        }else{
            //删除该模型表
            $sql="DROP TABLE $ModuleTableName;";
            $res=Db::execute($sql);

            //删除模型字段
            Db::table('field_management')->where(['model_id'=>$id])->delete();
            //删除模型数据
            $res=Db::table('column_module')->where(['id'=>$id])->delete();

        }

        if($res){
            return json([
                'errormsg'=>'删除成功',
                'errorcode'=>'0',
                'data'=>$res
            ]);
        }else{
            return json([
                'errormsg'=>'删除失败',
                'errorcode'=>'1',
                'data'=>$res
            ]);
        }
        

    }


    /**
     * 添加/修改模型字段
     */
    public function FieldManagement(){


        if(!Request::instance()->isPost()){
            die('错误的请求类型');
        }

        $data=$_POST;

        $FormType=$data['FormType'];//layui对应的表单类型
        $ModuleTableName=$data['ModuleTableName'];//表名称
        $initName=$data['initName'];//原始字段名称

        unset($data['ModuleTableName']);
        unset($data['initName']);

        if(empty(input('id'))){


            //添加字段管理表
            $res=Db::table('field_management')->insert($data);

            if($res){

                if($FormType==5){//复选框

                    var_dump($data['FormConfig']);
                    return false;
                }else{

                    //添加字段
                    $FieldType="$data[FieldType]";//字段类型
                    $DefaultValue="DEFAULT NULL";//字段默认值
                    $Comment='';//字段注释



                    if($data['FieldLength']){
                        $FieldType="$data[FieldType]($data[FieldLength])";
                    }
                    if($data['DefaultValue']){
                        $DefaultValue="DEFAULT '$data[DefaultValue]'";
                    }
                    if($data['FieldComment']){
                        $Comment="COMMENT '$data[FieldComment]'";
                    }

                    $sql="ALTER TABLE `$ModuleTableName` add `$data[FieldName]` $FieldType $DefaultValue $Comment;";

                    Db::execute($sql);

                }

            }

            
        }else{
            unset($data['id']);
            //更新字段
            $res=Db::table('field_management')->where(['id'=>input('id')])->update($data);

            if($res){

                //更改字段
                $FieldType="$data[FieldType]";//字段类型
                $DefaultValue="DEFAULT NULL";//字段默认值
                $Comment='';//字段注释


                if($data['FieldLength']){
                    $FieldType="$data[FieldType]($data[FieldLength])";
                }
                if($data['DefaultValue']){
                    $DefaultValue="DEFAULT '$data[DefaultValue]'";
                }
                if($data['FieldComment']){
                    $Comment="COMMENT '$data[FieldComment]'";
                }
                
                $sql="ALTER TABLE `$ModuleTableName` CHANGE  `$initName` `$data[FieldName]` $FieldType $DefaultValue $Comment;";

                Db::execute($sql);
            }
            
        }


        if($res){
            return json([
                'errormsg'=>'操作成功',
                'errorcode'=>'0',
                'data'=>$res
            ]);
        }else{
            return json([
                'errormsg'=>'操作失败',
                'errorcode'=>'10003',
                'data'=>$res
            ]);
        }

    }

    /**
     * 修改字段信息
     */
    function EditField(){

        if(!Request::instance()->isPost()){
            die('错误的请求类型');
        }
        $id=input('id');
        if(empty($id)){
            return json([
                'errormsg'=>'唯一标识符不能为空',
                'errorcode'=>'1',
                'data'=>$id
            ]);
        }

        $res=Db::table('field_management')->where(['id'=>$id])->update($_POST);

        if($res){
            return json([
                'errormsg'=>'操作成功',
                'errorcode'=>'0',
                'data'=>$res
            ]);
        }else{
            return json([
                'errormsg'=>'操作失败',
                'errorcode'=>'10003',
                'data'=>$res
            ]);
        }
    }

    /**
     * 获取模型字段列表
     */
    public function GetFieldManagementList(){

        $mid=input('model_id');

        $data=Db::table('field_management')->where(['model_id'=>$mid])->order('sort asc')->select();

        return json([
            'errormsg'=>'',
            'errorcode'=>'0',
            'data'=>$data
        ]);

    }


    /**
     * 获取一条模型字段
     */
    public function GetFieldManagementById(){

        $id=input('id');
        if(empty($id)){
            return json([
                'errormsg'=>'唯一标识符不能为空',
                'errorcode'=>'1',
                'data'=>$id
            ]);
        }

        $data=Db::table('field_management')->where(['id'=>$id])->find();

        return json([
            'errormsg'=>'',
            'errorcode'=>'0',
            'data'=>$data
        ]);

    }


    /**
     * 删除模型字段
     */
    public function DeleteFieldManagement(){

        $id=input('id');
        $ModuleTableName=input('ModuleTableName');
        $FieldName=input('FieldName');
        if(empty($id) || empty($ModuleTableName) || empty($FieldName)){
            return json([
                'errormsg'=>'标识符id、模型名称、字段名称为空',
                'errorcode'=>'1',
                'data'=>$id
            ]);
        }

        // $ColumnData=Db::table($ModuleTableName)->field($FieldName)->find();
        // if($ColumnData){
        //     return json([
        //         'errormsg'=>'字段还有数据引用，无法删除！',
        //         'errorcode'=>'1',
        //         'data'=>$ColumnData
        //     ]);
        // }

        if(strpos($id,',')){
            //$data=Db::table('field_management')->where(['id'=>['in',$id]])->delete();//批量删除
        }else{

            $sql="ALTER TABLE `$ModuleTableName` DROP COLUMN `$FieldName`;";
            Db::execute($sql);

            $data=Db::table('field_management')->where(['id'=>$id])->delete();//单条删除
        }

        if($data){
            return json([
                'errormsg'=>'删除成功',
                'errorcode'=>'0',
                'data'=>$data
            ]);
        }else{
            return json([
                'errormsg'=>'删除失败',
                'errorcode'=>'1',
                'data'=>$data
            ]);
        }
        

    }

    /**
     * 根据栏目id获取模型字段
     */
    public function GetFieldByCID(){

        $cid=input('cid');
        if(empty($cid)){
            return json([
                'errormsg'=>'cid为空',
                'errorcode'=>'1',
                'data'=>null
            ]);
        }

        $data=Db::table('category')->where(['id'=>$cid])->find();

        $res=Db::table('field_management')->where(['model_id'=>$data['model_id']])->order('sort asc')->select();

        return json([
            'errormsg'=>'栏目模型',
            'errorcode'=>'0',
            'data'=>$res
        ]);

    }

    /**
     * 添加/修改  待办事项  分类
     */
    public function TodoListCategory(){

        if(!Request::instance()->isPost()){
            die('错误的请求类型');
        }

        $id=input('id');


        if(empty($id)){
            //添加
            $res=Db::table('todo_list_category')->insert($_POST);
        }else{
            //修改
            $res=Db::table('todo_list_category')->where(['id'=>$id])->update($_POST);
        }

        if($res){
            return json([
                'errormsg'=>'操作成功',
                'errorcode'=>'0',
                'data'=>$res
            ]);
        }else{
            return json([
                'errormsg'=>'操作失败',
                'errorcode'=>'1',
                'data'=>$res
            ]);
        }


    }

    /**
     * 删除  待办事项  分类
     */
    public function DeleteTodoListCategory(){

        $id=input('id');

        if(empty($id)){
            return json([
                'errormsg'=>'id不能为空',
                'errorcode'=>'1',
                'data'=>null
            ]);
        }

        $res=Db::table('todo_list_category')->where(['id'=>$id])->delete($_POST);

        if($res){

            //删除该分类下的待办事项
            Db::table('todo_list')->where(['todo_list_category_id'=>$id])->delete();

            return json([
                'errormsg'=>'删除成功',
                'errorcode'=>'0',
                'data'=>$res
            ]);

        }else{
            return json([
                'errormsg'=>'删除失败',
                'errorcode'=>'1',
                'data'=>$res
            ]);
        }

    }

    /**
     * 检索 待办事项 分类
     */
    public function GetTodoListCategory(){

        $id=input('id');

        if(empty($id)){
            $res=Db::table('todo_list_category')->select();
            foreach ($res as $key => $value) {
                $res[$key]['todolist']=Db::table('todo_list')->where(['todo_list_category_id'=>$value['id'],'finished'=>0])->select();
            }
        }else{
            $res=Db::table('todo_list_category')->where(['id'=>$id])->find();
            $res['todolist']=Db::table('todo_list')->where(['todo_list_category_id'=>$id,'finished'=>0])->select();
        }

        return json($res);

    }


    /**
     * 添加/修改待办事项
     */
    public function TodoList(){

        if(!Request::instance()->isPost()){
            die('错误的请求类型');
        }

        $id=input('id');


        if(empty($id)){
            //添加
            $res=Db::table('todo_list')->insert($_POST);
        }else{
            //修改
            $res=Db::table('todo_list')->where(['id'=>$id])->update($_POST);
        }

        if($res){
            return json([
                'errormsg'=>'操作成功',
                'errorcode'=>'0',
                'data'=>$res
            ]);
        }else{
            return json([
                'errormsg'=>'操作失败',
                'errorcode'=>'1',
                'data'=>$res
            ]);
        }

    }


    /**
     * 删除待办事项
     */
    public function DeleteTodoList(){

        $id=input('id');

        if(empty($id)){
            return json([
                'errormsg'=>'id不能为空',
                'errorcode'=>'0',
                'data'=>null
            ]);
        }

        $res=Db::table('todo_list')->where(['id'=>$id])->delete($_POST);

        if($res){
            return json([
                'errormsg'=>'删除成功',
                'errorcode'=>'0',
                'data'=>$res
            ]);
        }else{
            return json([
                'errormsg'=>'删除失败',
                'errorcode'=>'1',
                'data'=>$res
            ]);
        }

    }

    /**
     * 检索待办事项
     */
    public function GetTodoList(){

        $id=input('id');

        if(empty($id)){
            $res=Db::table('todo_list')->select();
        }else{
            $res=Db::table('todo_list')->where(['id'=>$id])->find();
        }

        return json($res);

    }

    /**
     * 添加/修改 类型数据
     */
    public function Type(){

        if(!Request::instance()->isPost()){
            die('错误的请求类型');
        }

        $id=input('id');

        $data=[];
        $fields=['name','availability','sort'];
        foreach ($fields as $key => $value) {
            $iptval=input($value);
            if($iptval || $iptval==0){
                $data[$value]=$iptval;
            }
        }

        if(empty($id)){
            //添加
            $res=Db::table('type')->insert($data);
        }else{
            //更新
            $res=Db::table('type')->where(['id'=>$id])->update($data);
        }

        if($res){
            return json([
                'errormsg'=>'操作成功',
                'errorcode'=>'0',
                'data'=>$res
            ]);
        }else{
            return json([
                'errormsg'=>'操作失败',
                'errorcode'=>'1',
                'data'=>$res
            ]);
        }
    }

    /**
     * 获取 类型数据
     */
    public function GetType(){

        $id=input('id');

        if(empty($id)){
            $TypeData=Db::table('type')->select();
        }else{
            $TypeData=Db::table('type')->where(['id'=>$id])->find();
        }


        return json([
            'errormsg'=>'',
            'errorcode'=>'0',
            'data'=>$TypeData
        ]);


    }

    /**
     * 删除 类型数据
     */
    public function DeleteType(){

        $id=input('id');

        $res=null;
        if(empty($id)){
            return json([
                'errormsg'=>'id不能为空',
                'errorcode'=>'1',
                'data'=>$res
            ]);
        }

        if(strpos($id,',')){
            $res=Db::table('type')->where(['id'=>['in',$id]])->delete();
        }else{
            $res=Db::table('type')->where(['id'=>$id])->delete();
        }
        

        if($res){
            return json([
                'errormsg'=>'删除成功',
                'errorcode'=>'0',
                'data'=>$res
            ]);
        }else{
            return json([
                'errormsg'=>'删除失败',
                'errorcode'=>'1',
                'data'=>$res
            ]);
        }


    }


    public function Email(){
        
        SendEmail('project-12@sresky.com','title','content');

    }

}

/**

产品类型
产品参数
检索（分类、条件）

 */