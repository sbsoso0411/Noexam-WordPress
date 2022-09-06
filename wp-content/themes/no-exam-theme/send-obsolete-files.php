<?php
/**
 * Template Name: send-obsolete-files
 * Description: A Page Template that displays at full width
 *
 * @package Hellish Simplicity
 * @since Hellish Simplicity 1.4
 */
 
require("inc/helpers.php");
require("data/questions.php");
$pathToDir = getcwd()."/wp-content/themes/no-exam-theme/storage/";
ManagerOfObsoleteFiles($pathToDir,$Questions);
?>
