<?php
/*
Page: function.php
Author: Souvik Patra
*/
function tep_sanitize_string($string)
{
   //sanitize string
  $patterns = array('/ +/', '/[<>]/');
  $replace = array(' ', '_');
  return preg_replace($patterns, $replace, trim($string));
}

function tep_db_prepare_input($string)
{
  //sanitize input field
  if (is_string($string)) {
    return trim(tep_sanitize_string(stripslashes($string)));
  } elseif (is_array($string)) {
    reset($string);
    foreach ($string as $key => $value) {
      $string[$key] = tep_db_prepare_input($value);
    }
    return $string;
  } else {
    return $string;
  }
}

function tep_not_null($value)
{
  // check field for empty
  if (is_array($value)) {
    if (sizeof($value) > 0) {
      return true;
    } else {
      return false;
    }
  } else {
    if ((is_string($value) || is_int($value)) && ($value != '') && ($value != 'NULL') && (strlen(trim($value)) > 0)) {
      return true;
    } else {
      return false;
    }
  }
}
// Redirect to another page or site
function tep_redirect($url)
{
  // redirect to page url
  if (strpos($url, '&amp;') !== false) {
    $url = str_replace('&amp;', '&', $url);
  }

  header('Location: ' . $url);
  exit;
}
function tep_session_register($variable)
{
  // register session with session variable
  if (!isset($GLOBALS[$variable])) {
    $GLOBALS[$variable] = null;
  }
  $_SESSION[$variable] = &$GLOBALS[$variable];
  return false;
}
function tep_validate_password($plain, $encrypted)
{
  // validate password
  if (tep_not_null($plain) && tep_not_null($encrypted)) {
    // split apart the hash / salt
    $stack = explode(':', $encrypted);

    if (sizeof($stack) == 1) {
      if (md5($plain) == $stack[0]) return true;
    } elseif (sizeof($stack) == 2) {
      if (md5($stack[1] . $plain) == $stack[0]) return true;
    } else {
      return false;
    }
  }

  return false;
}

function tep_db_fetch_array($db_query)
{
  //fetching for while loop
  return $db_query->fetch(PDO::FETCH_ASSOC);
}

function tep_db_fetch_all($db_query)
{
  //fetching for foreach loop
  return $db_query->fetchAll(PDO::FETCH_ASSOC);
}

function tep_db_num_rows($db_query)
{
  //returning number of rows
  return $db_query->rowCount();
}

function tep_db_close($link = 'db_link')
{
  global $$link;
  $$link = null;
}

function tep_db_perform($con, $table, $data = array(), $action = 'insert', $parameters = array())
{
  reset($data);
  $myarr = array();
  if ($action == 'insert') {
    $query = 'insert into ' . $table . ' (';
    foreach ($data as $columns => $value) {
      $query .= $columns . ', ';
    }
    $query = substr($query, 0, -2) . ') values (';
    reset($data);
    foreach ($data as $key => $value) {
      switch ((string)$value) {
        case 'now()':
          $query .= 'now(), ';
          //array_push($myarr, 'now()');
          break;
        case 'null':
          $query .= '?, ';
          array_push($myarr, 'null()');
          break;
        default:
          $query .= '?, ';
          array_push($myarr, $value);
          break;
      }
    }
    $query = substr($query, 0, -2) . ')';
  } elseif ($action == 'update') {
    $query = 'update ' . $table . ' set ';
    foreach ($data as $columns => $value) {
      switch ((string)$value) {
        case 'now()':
          $query .= $columns . ' = now(), ';
          //array_push($myarr, 'now()');
          break;
        case 'null':
          $query .= $columns .= ' = ?, ';
          array_push($myarr, 'null()');
          break;
        default:
          $query .= $columns . ' = ?, ';
          array_push($myarr, $value);
          break;
      }
    }
    $query = substr($query, 0, -2) . ' where ' . $parameters[0] . '= ?';
    array_push($myarr, $parameters[1]);
  }
  $con->run($query, $myarr);
}