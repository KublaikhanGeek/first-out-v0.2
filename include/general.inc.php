<?
###################################
# FILE: general.inc
#
# Contains:
# - connection to a database object
# - some GLOBAL variables.
# - includes libraries
# - usefull functions
# 
###################################

#################
# Web Root Folder
$INCLUDE_PATH = "include";
$CLASSES_PATH = "classes";
# error reporting
error_reporting( E_ALL ^ E_NOTICE ^ E_WARNING );

# images folder
$IMG = "images";

#BASE OBJECT CLASS
include($CLASSES_PATH.'/baseObject.class.php');

#CLASS DB 
include($CLASSES_PATH.'/Db.class.php');

#Instantiate a new Db object for D'base transactions
$DB = new Db("therestlessdays","root","t35ting123");
?>