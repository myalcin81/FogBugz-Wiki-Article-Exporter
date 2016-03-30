<html>
<head>
<script src="jquery-1.12.2.min.js"></script>
</head>
<body>
<span>Fogbugz API</spaN>
<input type="text" name="api" value="***yourapitoken***">
<br/>
<span>Fogbugz URL</spaN>
<input type="text" name="url" value="https://samplesubdomain.fogbugz.com/">
<br/>
<span>Start Point</spaN>
<input type="text" name="start" value="1">
<br/>
<span>End Point</spaN>
<input type="text" name="end" value="1">
<br/>
<span>Cookie (sample1=cookie1;sample2=c2):</span>
<br/>
<textarea name="cookie" style="width:300px;height:80px;"></textarea>
<br/>
<input type="button" name="go" id="go" value="Fetch" />
<div class="info"></div>
</form>
<script>
$( document ).ready(function(){
	$("#go").click(function(){
		var startPoint=$("input[name='start']").val();
		var endPoint=$("input[name='end']").val();
		var api=$("input[name='api']").val();
		var url=$("input[name='url']").val();
		var cookie=$("textarea[name='cookie']").val();
		var delay=0;
		var delayExt=1500;
		var download=1;//0
		
		for(var i=startPoint;i<=endPoint;i++){
			delay=delay+delayExt;
			setInterval(fetchContent(i,url,api,cookie,download),delay);
		}
	});
	
	var fetchContent=function(id,fogbugzurl,api,cookie,download){
		var req="preview.php?url="+fogbugzurl+"&tpage=default.asp?W"+id+"&id="+id+"&download="+download+"&cookie="+encodeURI(cookie)
		if(download==0){
			var jqxhr = $.get(
			""+req,
			function() {
				printOutput( "success" );
			})
			.fail(function() {
				printOutput( "error" );
			})
		}else{
			window.open(req,'download_'+id,'');
		}
	}
	var printOutput=function(err){
		$(".info").append("<span>"+err+"</span>");
	}
})
</script>
</body>
</html>