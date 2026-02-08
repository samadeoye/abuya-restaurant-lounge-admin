<?php
function getJsonRow($status, $msg, $extraData=[])
{
  $response['status'] = $status;
  $response['msg'] = $msg;

  $returnType = 'json';
  if (count($extraData) > 0)
  {
    if (array_key_exists('returnType', $extraData))
    {
      $returnType = $extraData['returnType'];
    }
    foreach($extraData as $key => $value)
    {
      $response[$key] = $value;
    }
  }
  $response = getJsonList($response, $returnType);
  if ($returnType == 'array')
  {
    return $response;
  }
}
function getJsonList($row, $returnType='json')
{
  if (count($row) > 0)
  {
    if ($returnType == 'json')
    {
      echo json_encode($row, JSON_PRETTY_PRINT);
      exit;
    }
    else
    {
      //array
      return $row;
    }
  }
}

function stripTags($text)
{
	return strip_tags(trim($text));
}

function doTypeCastDouble($number)
{
  return doubleval($number);
}

function doNumberFormat($number)
{
  return number_format($number, 2);
}

function doTypeCastInt($number)
{
  return intval($number);
}

function getCurrencyAmount($amount)
{
  if (doTypeCastDouble($amount) > 0)
  {
    return DEF_CURRENCY_SYMBOL . doNumberFormat($amount);
  }
  return 'FREE';
}

function getUniqIdUpper()
{
  return strtoupper(uniqid());
}

function getNewId()
{
  mt_srand((int)microtime()*10000);
  $charId = strtoupper(md5(uniqid(rand(), true)));
  $hyphen = chr(45);
  $id = substr($charId, 0, 8).$hyphen
  .substr($charId, 8, 4).$hyphen
  .substr($charId, 12, 4).$hyphen
  .substr($charId, 16, 4).$hyphen
  .substr($charId, 20, 12);
  return $id;
}

function getFormattedDate($date, $format='')
{
  if ($date != '')
  {
    if (strlen($date) >= 10)
    {
      $format = !empty($format) ? $format : 'Y-m-d H:i';
      return date($format, strtotime($date));
    }
  }
  return '';
}

function regexReplace($text)
{
  $text = trim($text);
  return preg_replace( "/[^\.\-\_\@a-zA-Z0-9]/", "", $text );
}

function regexReplaceWithSpace($text)
{
  $text = trim($text);
  return preg_replace( "/[^\.\-\' a-zA-Z0-9]/", "", $text );
}

function regexReplaceMsg($text)
{
  $text = trim($text);
  return preg_replace( "/(From:|To:|BCC:|CC:|Subject:|Content-Type:)/", "", $text );
}
function stringToUpper($text)
{
  if ($text != '')
  {
    $text = trim($text);
    return strtoupper(strtolower($text));
  }
  return $text;
}
function stringToTitle($text)
{
  if ($text != '')
  {
    $text = trim($text);
    return ucwords(strtolower($text));
  }
  return $text;
}

function getDropdownValue($dropdown, $id)
{
  global $arGlobalDropdowns;

  return $arGlobalDropdowns[$dropdown][$id];
}

function getDropdownValuesFromArIds($dropdown, $arIds, $returnType='string')
{
  global $arGlobalDropdowns;

  $arDropdown = $arGlobalDropdowns[$dropdown];
  $arValues = [];
  foreach ($arIds as $id)
  {
    $arValues[] = $arDropdown[$id];
  }

  if ($returnType == 'string')
  {
    return implode(', ', $arValues);
  }
  return $arValues;
}

function getErrorMsgNReturnLink($errorMsg)
{
  if ($errorMsg != '')
  {
    $previousPage = $_SERVER['HTTP_REFERER'];
    echo <<<EOQ
    <span class="alert alert-danger">{$errorMsg}</span><br><a href="{$previousPage}">Click here to go back</a>
EOQ;
    exit;
  }
}

function returnToPreviousPage()
{
  header('location: '.$_SERVER['HTTP_REFERER']);
}

function getCurrentDate($format="Y-m-d H:i:s")
{
  return date($format);
}

function getCurrentFileName()
{
  $path = parse_url($_SERVER["SCRIPT_NAME"], PHP_URL_PATH);
  return pathinfo($path, PATHINFO_FILENAME);
}

function checkIfAdminPage()
{
  $currentPageName = getCurrentFileName();
  if (strpos($currentPageName, 'dashboard') !== false)
  {
    return true;
  }
  return false;
}

function blockOutToLoginPage()
{
  header('Location: '.DEF_ROOT_PATH.'/login');
  exit;
}

function blockOutToMainPage()
{
  header('Location: '.DEF_ROOT_PATH.'/dashboard');
  exit;
}

function getCurrentPageAdmin($pageTitle)
{
  $arCurrentPage = [
    'dashboard' => '',
    'products' => '',
    'product-categories' => '',
    'rooms' => '',
    'facilities' => '',
    'general-settings' => '',
    'ecommerce-settings' => '',
    'profile' => '',
  ];
  $lblActive = 'active';
  $pageTitle = str_replace(' ', '', $pageTitle);
  $pageTitle = strtolower($pageTitle);

  switch ($pageTitle)
  {
    case 'dashboard':
    case '':
      $arCurrentPage['dashboard'] = $lblActive;
    break;

    default:
      $arCurrentPage[$pageTitle] = $lblActive;
  }
  
  return $arCurrentPage;
}

function checkIsArray($data)
{
  if (!is_array($data))
  {
    return [];
  }
  return $data;
}

function debugData($data)
{
  if (!is_array($data))
  {
    $data = [$data];
  }
  print_r($data);
  exit;
}