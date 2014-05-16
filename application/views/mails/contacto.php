<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Paseo de la historieta</title>
</head>
<body>
<style>
	.c {background:#fff;width:100%;text-align:center;font:12pt Helvetiva,Arial,Sans-Serif;padding-top:10px;padding-bottom:10px;} 
	.p {margin:auto;width:800px;text-align:left;padding:10px;border-radius:5px;background: #8cc63f;}
	.h {height:125px;}
	.t {float:left;width:100px;color:#eee;}
	.f {float:left;}
	.l {height:23px;}
	.i {padding-left:20px;padding-top:35px;text-shadow: 1px 1px 1px #fff;font-size:18pt;}
	.m {background:#fff;width:780px;padding:10px;border-radius:5px;font-style:italic;margin-top:3px;}
	img {margin-top:-33px;}
</style>
<div class="c">
	<div class="p">
		<div class="h">
			<img class="f" src="<?= base_url('images/ba.png') ?>"/>
			<div class="f i">Notificación del paseo de la Historieta</div>
		</div>
		<div class="l"><div class="t">Nombre:</div> <div class="f"><?= $nombre ?></div></div>
		<div class="l"><div class="t">Mail:</div>   <div class="f"><?= $mail ?></div></div>
		<div><div class="t">Mensaje:</div><br/><div class="m"><?= $mensaje ?></div></div>
	</div>
</div>
</body>
</html>