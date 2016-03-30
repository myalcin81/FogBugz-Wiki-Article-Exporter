<?php
include('functions.php');
$baseUrl=$_GET["url"];
$tUrl=$baseUrl.$_GET["tpage"];
$cookieData=$_GET["cookie"];
$fullContent=get_web_page($tUrl,$cookieData);
$fullContent_=$fullContent;
if(strpos($fullContent_,"This article does not exist.",0)==true || strpos($fullContent_,"This article has not been written.",0)==true){
	if(@$_GET["download"]==1){
		die('<script>window.close();</script>');
	}else{
		die('does not exist.');
	}
}else{
	$fullContentAr=explode('<div id="wiki-page-metadata">',$fullContent);
	$content_='<div id="wiki-page-metadata">'.$fullContentAr[1];
	$contentAr=explode('<script type="text/javascript">',$content_);
	$content=$contentAr[0];
	$stylefile="newstyle.css";
	$myfile = fopen($stylefile, "r") or die("Unable to open file!");
	$stylecss=fread($myfile,filesize($stylefile));
	$titleAr=explode('<div id="wiki-page-content">',$content);
	$titleAr_=explode('<h1>',$titleAr[1]);
	$titleAr2_=explode('</h1>',$titleAr_[1]);
	$title=$titleAr2_[0];
	$title=htmlCharFix($title);
	$content=explode('<div style="clear: both; line-height:0px; font-size: 0px;">&nbsp;</div>',$content)[0];
	if(@$_GET["download"]==1){
		$filename=$title." - Wiki(".$_GET["id"].").html";
		header('Content-disposition: attachment; filename=' . $filename);
		header('Content-type: text/html');
	}
	
	//find images and convert to base64
	$doc = new DOMDocument();
	@$doc->loadHTML($content);
	$tags = $doc->getElementsByTagName('img');
	foreach ($tags as $tag) {
		   $imgUrl=$tag->getAttribute('src');
		   $imgFullUrl=$baseUrl.$imgUrl;
		   $imgDataBase64Encoded=chunk_split(base64_encode(get_image($imgFullUrl,$cookieData)));
		   $content=str_replace($imgUrl,'data:image/png;base64,'.$imgDataBase64Encoded,$content);
		   $imgUrl=str_replace('&','&amp;',$imgUrl);
		   $content=str_replace($imgUrl,'data:image/png;base64,'.$imgDataBase64Encoded,$content);
	}


	?>
	<html>
	<head>
	<title><?php echo $title;?> - Wiki(<?php echo $_GET["id"];?>)</title>
	<style>
	<?php echo $stylecss;?>
	</style>
	</head>
	<body>
	<div>
	<?php echo $content;?>
	</div>
	</body>
	</html>
<?php
}
?>