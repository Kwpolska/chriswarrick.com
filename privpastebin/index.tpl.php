<!DOCTYPE html> 
<html> 
<head> 
<meta charset="UTF-8">
<link rel="stylesheet" href="./style.css">
<title><?php echo $this->eprint($this->title); ?></title>
<!-- KwPastebin
Copyright Kwpolska 2010. Licensed on GPLv3. -->
</head>
<body>
<div id="wrapper"><div id="menu">
<ul>
   <li><a href="index.php">Add</a></li>
   <li>Go to: <form action="index.php" method="get"><input name="id"></form></li>

</ul>
<h1><?php echo $this->eprint($this->title); ?></h1>
</div>
<div id="content">
<?php echo $this->eprint($this->content); ?>
</div>
</div>
<div id="footer">
KwPastebin - Copyright Kwpolska 2010. Licensed on GPLv3. Using <a href="http://phpsavant.com">Savant</a>. <!--don't edit it, put your copyright notice above-->
</div>
</body>
</html>
