<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

if (!$smarty->isCached('master.tpl', $smarty_cache_key)) {
  $debug->append('No cached version available, fetching from backend', 3);
  if ($user->isAuthenticated()) {
    $aHourlyHashRates = $statistics->getHashrateByAccount($_SESSION['USERDATA']['id'], 'json');
    $aPoolHourlyHashRates = $statistics->getHashrateForPool('json');
  }
  $smarty->assign("YOURHASHRATES", @$aHourlyHashRates);
  $smarty->assign("POOLHASHRATES", @$aPoolHourlyHashRates);
} else {
  $debug->append('Using cached page', 3);
}

switch($setting->getValue('acl_graphs_statistics', 1)) {
case '0':
  if ($user->isAuthenticated()) {
    $smarty->assign("CONTENT", "default.tpl");
  }
  break;
case '1':
  $smarty->assign("CONTENT", "default.tpl");
  break;
case '2':
  $_SESSION['POPUP'][] = array('CONTENT' => 'Page currently disabled. Please try again later.', 'TYPE' => 'alert alert-danger');
  $smarty->assign("CONTENT", "");
  break;
}
?>
