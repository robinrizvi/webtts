<!DOCTYPE HTML>
<html>
<body>
<h1>In this demonstration:<br />
>tts is done on server side (i.e by using text2speech.org server)<br/>
>then the audio received from text2speech.org is saved on your server (local/production)<br />
>and then that saved audio is played through that saved file on this webpage.</h1>
<h3>
Tested with:
Chrome v21 [Working],
Firefox v14 [Not Working, firefox does not support mp3 audio format playback],
IE v9[Working]
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
	$filename=$_POST['filename'].'.mp3';
		
	$postdata = http_build_query(
		array(
			"speech" => $text,
			//options for voice are:"nitech_us_rms_arctic_hts","nitech_us_bdl_arctic_hts","nitech_us_slt_arctic_hts","nitech_us_awb_arctic_hts"
			"voice"=>"nitech_us_rms_arctic_hts",
			//options for volume_scale are: 1 to 10
			"volume_scale"=>5,
			"make_audio"=>"Convert Text To Speech"
		)
	);
	
	$opts = array('http' =>
		array(
			'method'  => 'POST',
			'header'  => 'Content-type: application/x-www-form-urlencoded',
			'content' => $postdata
		)
	);
	
	$context  = stream_context_create($opts);
	
	if ($htmldocwithlink = file_get_contents("http://www.text2speech.org", false, $context))
	{
		$htmldoc = new DOMDocument();
		$htmldoc->loadHTML($htmldocwithlink);
		$soundfilelink=$htmldoc->getElementById('downloadlink')->getElementsByTagName('a')->item(0)->getAttribute('href');
		$soundfile=file_get_contents('http://www.text2speech.org/'.$soundfilelink);
		file_put_contents($filename,$soundfile);
		echo ('
				<audio autoplay="autoplay" controls="controls">
				<source src="'.$filename.'" type="audio/mp3" />
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