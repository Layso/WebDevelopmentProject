<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function get_root_path() {
  $path = (array_key_exists("REDIRECT_URL", $_SERVER)) ? $_SERVER["REDIRECT_URL"] : $_SERVER["SCRIPT_NAME"];
//echo $path;
  $elements = explode("/", $path);
  $debut = "$elements[1]";
  if (substr($elements[1], 0, 1) == "~" && count($elements) > 2) {
    $debut = "$debut/$elements[2]";
  }
//echo "<br>" . count($elements);
  return "$_SERVER[REQUEST_SCHEME]://$_SERVER[SERVER_NAME]:$_SERVER[SERVER_PORT]/$debut/";
}

echo count(explode("/", "/foo"));
echo get_root_path();
phpinfo();

