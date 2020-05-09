<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <title>
        <?= $this->fetch("title") ?>
    </title>
    <?= $this->Html->meta("icon") ?>

    <?= $this->Html->css("base.css") ?>
    <?= $this->Html->css("cake.css") ?>

    <?= $this->fetch("meta") ?>
    <?= $this->fetch("css") ?>
    <?= $this->fetch("script") ?>
	
	<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
	<?= $this->Html->css("styling.css") ?>
	<?=$this->Html->script("navbar.js") ?>
</head>

<body>
	<?php
	echo $this->element("navbar");
	echo $this->fetch("navbar");
	?>

    <div id="container">
        <div id="header">
            <h1><?= __("Error") ?></h1>
        </div>
        <div id="content">
            <?= $this->Flash->render() ?>

            <?= $this->fetch("content") ?>
        </div>
        <div id="footer">
            <?= $this->Html->link(__("Back"), "javascript:history.back()") ?>
        </div>
    </div>
	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>