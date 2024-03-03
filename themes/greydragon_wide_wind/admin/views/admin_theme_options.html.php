<?php defined("SYSPATH") or die("No direct script access.") ?>
<?
  $view = new View("admin_include.html");

  $view->admin_link_support  = "http://gallery.menalto.com/node/111353";
  $view->name = $name;
  $view->version = $version;
  $view->form = $form;
  $view->help = $help;                        
  print $view;
?>   

