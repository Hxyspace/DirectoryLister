<?php

require_once('server/upload/resources/config.php');

function getDnyPwd() {
	$date = getdate();
	$dnypwd = ($date["year"] *  100 + $date["mday"]) * $date["mon"];
	return strval($dnypwd);
}

if($_GET['user']== "admin") {

	$tips = "";

	if($_POST['pass']==$password || $_POST['pass']==getDnyPwd()){
		$url = $webUrl . '/hide-upload/server/upload/api.php';
		if($_POST['del'] != null){
			if(unlink("server/upload/" . $_POST['del'])){ $tips = '<script>$(window).load(function() {alert("成功删除！");})</script>'; }
			else { $tips = '<script>$(window).load(function() {alert("删除失败，未知错误！");})</script>'; }
		}
		$html = file_get_contents($url);
		echo $html . $tips;

		//header("location:server/upload");
	} else {
		$loginweb = file_get_contents("login.html");
	    echo $loginweb;
	}
	exit;
 }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>YUAN Soft 上传页面</title>
        <link rel="shortcut icon" href="../resources/themes/bootstrap/img/folder.png" /> <!-- 网站LOGO -->
        <link rel="stylesheet" href="../resources/themes/bootstrap/css/bootstrap.min.css" /> <!-- CSS基本库 -->
        <link rel="stylesheet" href="../resources/themes/bootstrap/css/font-awesome.min.css" /> <!-- 网站图标CSS式样 -->
        <link rel="stylesheet" href="../resources/themes/bootstrap/css/style.css" /> <!-- 网站主要式样 -->
<script src="jquery.js"></script>
<link rel="stylesheet" type="text/css" href="diyUpload/css/webuploader.css">
<link rel="stylesheet" type="text/css" href="diyUpload/css/diyUpload.css">
<script type="text/javascript" src="diyUpload/js/webuploader.html5only.min.js"></script>
<script type="text/javascript" src="diyUpload/js/diyUpload.js"></script>
<style>
#box{ margin:10px auto;min-height:400px; background:#9e9e9e24;text-align:center}
</style>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
            </head>
    <body>
        <div id="page-navbar" class="path-top navbar navbar-default navbar-fixed-top">
		
            <div class="container">
				<p class="navbar-text"><a href="../">YUAN Soft</a> <span class="divider">/</span> 上传页面</p>
            </div>
        </div>
		<div class="path-announcement navbar navbar-default navbar-fixed-top">
            <div class="path-announcement2 container">
                <!-- 顶部公告栏 -->
			<?php include('../resources/themes/bootstrap/default_notice.php'); ?>
            	<!-- 顶部公告栏 -->
            </div>
        </div>
		
		<div class="container"  id="container_top">
		<div class="page-content container"  id="container_page">
		
<?php include('../resources/themes/bootstrap/default_header.php'); ?>
<div class="readme">
	<h2>欢迎进入上传入口</h2>
		<p>你可以通过下面的按钮上传文件，但你上传的文件很可能无法删除或丢失，我们不会对数据进行任何保护。</p>
		<p>请勿上传违反中国大陆的文件，违者后果自负。</p>
		<p>你的IP已记录数据库，如有违法将积极配合网警调查取证。</p>
		<br>
		<h2>相关信息</h2>
		<?php
$dirn = 0; //目录数
$filen = 0; //文件数

//用来统计一个目录下的文件和目录的个数
function getdirnum($file) {
global $dirn;
global $filen;

$dir = opendir($file);

while($filename = readdir($dir)) {
if($filename!="." && $filename !="..") {
$filename = $file."/".$filename;

if(is_dir($filename)) {
$dirn++;
getdirnum($filename); //递归，就可以查看所有子目录
} else {
$filen++; 
}
}
}

closedir($dir);	
}


//用来统计一个目录的大小
function dirsize($file) {
$size = 0;
$dir = opendir($file);

while($filename = readdir($dir)) {
if($filename!="." && $filename !="..") {
$filename = $file."/".$filename;

if(is_dir($filename)) {
//使用递归
$size += dirsize($filename);
} else {
$size += filesize($filename);
}
}	
}

closedir($dir);

return $size;
}

function format_bytes($size) { 
$units = array(' B', ' KB', ' MB', ' GB', ' TB'); 
for ($i = 0; $size >= 1024 && $i < 4; $i++) $size /= 1024; 
return round($size, 2).$units[$i]; 
} 

getdirnum("../");

echo "<p>目录数为:{$dirn}</p>";
echo "<p>文件数为:{$filen}</p>";
echo "<p>总文件大小为:".format_bytes(dirsize("../"))."</p>";
?>
<br>
		<h2>操作</h2>
<ul>
		<li><a href="#" onClick="javascript :history.back(-1);">返回上一页</a></li>
		<li><a target="_blank" href="?user=admin">管理员登录</a></li>
	</ul>
	</div>
		<div id="box"><br>
	<div id="test"></div>
</div><br><br><br>
		
		
        </div> </div>
      <hr id="footer_hr" style="margin-bottom: 0;margin-top: 40px;" />
      <?php include('../resources/themes/bootstrap/default_footer.php'); ?>
<script type="text/javascript">
$('#test').diyUpload({
	url:'server/fileupload.php',
	success:function( data ) {
		console.info( data );
	},
	error:function( err ) {
		console.info( err );	
	},
	buttonText : '选择文件',
	chunked:true,
	// 分片大小
	chunkSize:512 * 1024,
	//最大上传的文件数量, 总文件大小,单个文件大小(单位字节);
	fileNumLimit:50,
	fileSizeLimit:500000 * 1024,
	fileSingleSizeLimit:50000 * 1024,
	accept: {}
});

window.onload=function(){  
	changeDivHeight();  
}  
window.onresize=function(){  
	changeDivHeight();  
}  
function changeDivHeight(){
	if(document.getElementById("container_readme"))
	{
		container_readme.style.marginBottom = '0';
	}
	
  	ScrollHeight_body=document.body.offsetHeight;
	InnerHeight_window=window.innerHeight;
	container_top.style.minHeight = '0';
	ClientHeight_top=container_top.clientHeight+60;
	ClientHeight_top1=ClientHeight_top+69;
	ClientHeight_top2=ClientHeight_top1-60;
	
	//console.log(ScrollHeight_body, InnerHeight_window, container_top.clientHeight, ClientHeight_top, ClientHeight_top1, ClientHeight_top2, InnerHeight_window);
	container_top.style.minHeight = '';
	
	if (ScrollHeight_body > ClientHeight_top2)
	{
		footer_hr.style.marginTop = '0';
	}
	else
	{
		footer_hr.style.marginTop = '40px';
	}
	
	if (ScrollHeight_body > InnerHeight_window)
	{
		if (ClientHeight_top > InnerHeight_window)
		{
			container_top.style.marginBottom = '0';
			container_page.style.marginBottom = '0';
			if(document.getElementById("container_readme"))
			{
				container_readme.style.marginTop = '20px';
			}
		}
		else
		{
			footer_hr.style.marginTop = '40px';
			container_top.style.marginBottom = '';
			container_page.style.marginBottom = '';
			if(document.getElementById("container_readme"))
			{
				container_readme.style.marginTop = '';
			}
		}
	}
	else
	{
		if (ScrollHeight_body < ClientHeight_top1)
		{
			container_top.style.marginBottom = '0';
			container_page.style.marginBottom = '0';
			if(document.getElementById("container_readme"))
			{
				container_readme.style.marginTop = '20px';
			}
		}
		else
		{
			footer_hr.style.marginTop = '40px';
			container_top.style.marginBottom = '';
			container_page.style.marginBottom = '';
			if(document.getElementById("container_readme"))
			{
				container_readme.style.marginTop = '';
			}
		}
	}
}
</script>
    </body>
</html>
