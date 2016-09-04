<!DOCTYPE HTML>
<html>
<head>
<script type="text/javascript" src="speakGenerator.js"></script>
</head>
<body>
<?php
if (isset($_GET['filename']))
{
	$filename=$_GET['filename'].'.wav';
	
	if ($soundfile = file_get_contents('php://input'))
	{
		file_put_contents($filename,$soundfile);
		echo ('
		  <audio autoplay="autoplay" controls="controls">
		  <source src="'.$filename.'" type="audio/x-wav" />
		  </audio>
		  <br />
		  Saved mp3 location : '.dirname(__FILE__).'\\'.$filename.'
		  <br />
		  Saved mp3 uri : <a href="'.$filename.'">'.$_SERVER['SERVER_NAME'].'/webtts/'.$filename.'</a>'
		);
	}
	else echo("<br />Audio could not be saved");
	exit();
}
?>
<h1>In this demonstration:<br />
>tts is done on client-side/browser (i.e by using javascript)<br/>
>then the converted audio is played directly on this webpage<br />
>and/or the converted audio is saved as a file on my server and then that saved audio is played through that saved file on this webpage.</h1>
<h3>
Tested with:
Chrome v21 [Working],
Firefox v14 [Working],
IE v9[Not Working],
IE v10 [Not tested, but probably it will work]
</h3>
<hr />
<div style="font-size:25px">
Text to convert : <input name="txt" type="text" value="" /><br />
Filename to save (without the extension) : <input name="filename" type="text" /><br />
Save to server as (.wav) : <input type="checkbox" name="save" /><br/>
Convert text to speech : <input name="submit" type="submit" value="Convert" onClick="do_tts()" />
</div>
<div id="player"></div>
</body>
<script>
function do_tts()
{
	text=document.getElementsByName('txt').item(0).value;
	if (text!="")
	{
		//optional arguments can be supplied as generateSpeech(text,{ option1: value1, option2: value2 .. }}; the options are listed here ->> amplitude: How loud the voice will be (default: 100)__pitch: The voice pitch (default: 50)__speed: The speed at which to talk (words per minute) (default: 175)__voice: Which voice to use (for a non-default voice, requires you to build speakGenerator.js again from espeak to include the proper data.) (default: en/en-us)__wordgap: Additional gap between words in 10 ms units (default: 0)
		var bytearray = generateSpeech(text);
		if (document.getElementsByName('save').item(0).checked)
		{
		  var url='tts_clientside_espeak.php?filename='+document.getElementsByName('filename').item(0).value;
		  var xhr = new XMLHttpRequest();
		  xhr.open('POST', url, true);	  	
		  xhr.send(bytearray.buffer);	  
		  xhr.onload = function(e) {
			  if (this.status == 200) {
				  document.getElementById('player').innerHTML=this.response;
			  }
		  }
		}
		else
		{
			document.getElementById('player').innerHTML='<audio autoplay="autoplay" controls="controls" src="data:audio/x-wav;base64,'+encode64(bytearray)+'">';
		}
	}
}
</script>
</html>