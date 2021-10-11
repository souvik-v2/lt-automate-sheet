<?php
function tep_db_connect($server = DB_SERVER, $username = DB_SERVER_USERNAME, $password = DB_SERVER_PASSWORD, $database = DB_DATABASE, $link = 'db_link')
{
  global $$link;

  $$link = mysqli_connect($server, $username, $password, $database);

  if (!mysqli_connect_errno()) {
    mysqli_set_charset($$link, 'utf8');
  }

  @mysqli_query($$link, 'set session sql_mode=""');

  return $$link;
}

function tep_db_input($string, $link = 'db_link')
{
  global $$link;

  return mysqli_real_escape_string($$link, $string);
}

function tep_sanitize_string($string)
{
  $patterns = array('/ +/', '/[<>]/');
  $replace = array(' ', '_');
  return preg_replace($patterns, $replace, trim($string));
}

function tep_db_prepare_input($string)
{
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
  if (strpos($url, '&amp;') !== false) {
    $url = str_replace('&amp;', '&', $url);
  }

  header('Location: ' . $url);
  exit;
}
function tep_session_register($variable)
{
  if (!isset($GLOBALS[$variable])) {
    $GLOBALS[$variable] = null;
  }
  $_SESSION[$variable] = &$GLOBALS[$variable];
  return false;
}
function tep_validate_password($plain, $encrypted)
{
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

function tep_db_perform($table, $data = array(), $action = 'insert', $parameters = '', $link = 'db_link')
{
  reset($data);
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
          break;
        case 'null':
          $query .= 'null, ';
          break;
        default:
          $query .= '\'' . tep_db_input($value) . '\', ';
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
          break;
        case 'null':
          $query .= $columns .= ' = null, ';
          break;
        default:
          $query .= $columns . ' = \'' . tep_db_input($value) . '\', ';
          break;
      }
    }
    $query = substr($query, 0, -2) . ' where ' . $parameters;
  }

  return tep_db_query($query, $link);
}

function tep_db_fetch_array($db_query)
{
  return mysqli_fetch_array($db_query, MYSQLI_ASSOC);
}

function tep_db_num_rows($db_query)
{
  return mysqli_num_rows($db_query);
}
function tep_db_query($query, $link = 'db_link')
{
  global $$link;
  $result = mysqli_query($$link, $query) or tep_db_error($query, mysqli_errno($$link), mysqli_error($$link));

  return $result;
}
function tep_db_error($query, $errno, $error)
{
  die('<font color="#000000"><strong>' . $errno . ' - ' . $error . '<br /><br />' . $query . '<br /><br /><small><font color="#ff0000">[TEP STOP]</font></small><br /><br /></strong></font>');
}
