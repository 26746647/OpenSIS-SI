<?php


// Selector de Idioma
//----------------
include('translate/langpref.php');
include('translate/lang/en.php');
if ($langpref != 'en.php')
	include('translate/lang/'.$langpref);


?>
