var config=new Array();


/*********************************************************后台前端API配置*******************************************************/
//api基础地址
config['APIURL']='/index.php/api/admin/';



/******************公共配置API******************/
//更新配置
config['UpdateConfig_API']=config['APIURL']+'UpdateConfig';
//获取配置
config['GetConfig_API']=config['APIURL']+'GetConfig';
//上传图片地址，并返回图片路径
config['UploadImages_API']=config['APIURL']+'UploadImages';

/******************WebsiteInfo.html 网站配置******************/
//更新网站信息
config['WebsiteInfo_API']=config['APIURL']+'updateWebsiteInfo';
//获取网站信息URL
config['GetWebsiteInfo']=config['APIURL']+'getWebsiteInfo';


/******************FriendLink.html 友情链接******************/
//添加/编辑友情链接
config['FriendLink_API']=config['APIURL']+'FriendLink';
//获取友情链接
config['GetFriendLink_API']=config['APIURL']+'GetFriendLink';
//获取一条友情链接
config['GetFriendLinkById_API']=config['APIURL']+'GetFriendLinkById';
//删除友情链接
config['DeleteFriendLink_API']=config['APIURL']+'DeleteFriendLink';


/******************SystemColumn.html 系统栏目******************/
//获取系统栏目列表
config['GetSystemColumnList_API']=config['APIURL']+'GetSystemColumnList';
//获取系统栏目分类
config['GetSystemColumn_API']=config['APIURL']+'GetSystemColumn';
//添加/编辑系统栏目
config['SystemColumn_API']=config['APIURL']+'SystemColumn';
//获取一条系统栏目
config['GetSystemColumnById_API']=config['APIURL']+'GetSystemColumnById';
//删除系统栏目
config['DeleteSystemColumn_API']=config['APIURL']+'DeleteSystemColumn';


/******************ColumnType.html 栏目类型******************/
//获取栏目类型列表
config['GetColumnTypeList_API']=config['APIURL']+'GetColumnTypeList';
//添加/编辑栏目类型
config['ColumnType_API']=config['APIURL']+'ColumnType';
//获取一条栏目类型
config['GetColumnTypeById_API']=config['APIURL']+'GetColumnTypeById';
//删除栏目类型
config['DeleteColumnType_API']=config['APIURL']+'DeleteColumnType';
//获取父级栏目
config['GetParentColumn_API']=config['APIURL']+'GetParentColumn';


/******************ColumnModule.html 栏目模型******************/
//获取栏目模型列表
config['GetColumnModuleList_API']=config['APIURL']+'GetColumnModuleList';
//添加/编辑栏目模型
config['ColumnModule_API']=config['APIURL']+'ColumnModule';
//获取一条栏目模型
config['GetColumnModuleById_API']=config['APIURL']+'GetColumnModuleById';
//删除栏目类型
config['DeleteColumnModule_API']=config['APIURL']+'DeleteColumnModule';


/******************FieldManagement.html 字段管理******************/
//获取栏目字段列表
config['GetFieldManagementList_API']=config['APIURL']+'GetFieldManagementList';
//添加/编辑字段
config['FieldManagement_API']=config['APIURL']+'FieldManagement';
//获取一条字段
config['GetFieldManagementById_API']=config['APIURL']+'GetFieldManagementById';
//删除字段
config['DeleteFieldManagement_API']=config['APIURL']+'DeleteFieldManagement';
//修改字段信息
config['EditField_API']=config['APIURL']+'EditField';


/******************Article.html 添加文章******************/
//获取文章列表
config['GetArticle_API']=config['APIURL']+'GetArticle';
//添加/编辑文章
config['Article_API']=config['APIURL']+'Article';
//获取一篇文章
config['GetArticleById_API']=config['APIURL']+'GetArticleById';
//删除文章
config['DeleteArticle_API']=config['APIURL']+'DeleteArticle';
//查询栏目分类
config['GetColumn_API']=config['APIURL']+'GetColumn';
//根据模型id获取字段信息
config['GetFieldInfoByMid_API']=config['APIURL']+'GetFieldInfoByMid';


/******************AddContent.html 添加内容******************/
//根据栏目id获取模型字段，用于渲染添加内容页面
config['GetFieldByCID_API']=config['APIURL']+'GetFieldByCID';


/******************TodoList.html 待办事项******************/
//添加/修改 待办事项分类
config['TodoListCategory_API']=config['APIURL']+'TodoListCategory';
//删除待办事项分类
config['DeleteTodoListCategory_API']=config['APIURL']+'DeleteTodoListCategory';
//获取待办事项分类
config['GetTodoListCategory_API']=config['APIURL']+'GetTodoListCategory';
//添加/修改 待办事项
config['TodoList_API']=config['APIURL']+'TodoList';
//删除待办事项
config['DeleteTodoList_API']=config['APIURL']+'DeleteTodoList';
//获取待办事项
config['GetTodoList_API']=config['APIURL']+'GetTodoList';


/******************AddColumnType.html 添加类型******************/
//添加/修改 类型数据
config['Type_API']=config['APIURL']+'Type';
//获取类型数据
config['GetType_API']=config['APIURL']+'GetType';
//删除类型数据
config['DeleteType_API']=config['APIURL']+'DeleteType';