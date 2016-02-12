<html>
<head>
	<?php include(dirname(__DIR__).'/commonUtil/header.html'); ?>
	<title>t-music</title>
	<script src="js/audiojs/audio.min.js"></script>
	<script>
		audiojs.events.ready(function() {
		  audiojs.createAll();
		});
	</script>
</head>
<body>
	<h1>頂戴したjs【<a href="http://kolber.github.io/audiojs/">audio.js</a>】</h1></br>
	inst
	<audio src="./music/inst.wav" preload="auto"></audio>
</body>
</html>