<?php
require_once __DIR__ . '/../src/bootstrap.php';
$routesPath = __DIR__ . '/../routes/';
$routeFiles = scandir($routesPath);
foreach($routeFiles as $routeFile)
{
  if(!preg_match('~\.php$~', $routeFile)){ continue; }
  require_once($routesPath . $routeFile);
}

App::get()->route()->render();
