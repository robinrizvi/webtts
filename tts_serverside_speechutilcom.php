<!DOCTYPE HTML>
<html>
<body>
<h1>In this demonstration:<br />
>tts is done on server side (i.e by using SpeechUtil server)<br/>
>then the audio received from speechutil.com is saved on your server (local/production)<br />
>and then that saved audio is played through that saved file on this webpage.</h1>
<h3>
Tested with:
Chrome v21 [Working],
Firefox v14 [Working],
IE v9[Not Working, IE does not support ogg audio format playback]
</h3>
<hr />
<form method="POST" style="font-size:25px">
Text to convert : <input name="txt" type="text" /><br />
Filename to save (without the extension) : <input name="filename" type="text" /><br />
Convert text to speech : <input name="submit" type="submit" value="Convert" />
</form>

<?php
if (isset($_POST['txt']) && isset($_POST['filename']))
{
	$text=htmlentities($_POST['txt']);
	$filename=$_POST['filename'].'.ogg';
	
	$querystring = http_build_query(array(
		"text" => $text
	));
	
	//for wav file format use http://speechutil.com/convert/wav? below
	if ($soundfile = file_get_contents("http://speechutil.com/convert/ogg?".$querystring))
	{
		file_put_contents($filename,$soundfile);
		echo ('
			<audio autoplay="autoplay" controls="controls">
			<source src="'.$filename.'" type="audio/ogg" />
			</audio>
			<br />
			Saved mp3 location : '.dirname(__FILE__).'\\'.$filename.'
			<br />
			Saved mp3 uri : <a href="'.$filename.'">'.$_SERVER['SERVER_NAME'].'/webtts/'.$filename.'</a>'
		);
	}
	else echo("<br />Audio could not be saved");
}
?>
</body>
</html>