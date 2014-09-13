<?php
define('IN_SYSTEM', true);


/* PLEASE CHANGE ALL THESE VARIABLES TO THE ACTUAL DATA */
$config = array(
  'db' => array(
    'host' => 'localhost',
    'name' => 'xiag',
    'user' => 'root',
    'password' => 'hEY69INAN',
    'charset' => 'utf8'
  ),
  'main' => array(
    'domain' => 'xiag.dev:8888'
  )
);

require_once('classes/db.php');

$Db = new Db;
$Db->dbConnect();

$get = trim($_SERVER['REQUEST_METHOD'] === 'GET');
$getPage = explode('/', $get);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  function generate_name($number) {
    $arr = array(
      'a','b','c','d','e','f',
      'g','h','i','j','k','l',
      'm','n','o','p','r','s',
      't','u','v','x','y','z',
      'A','B','C','D','E','F',
      'G','H','I','J','K','L',
      'M','N','O','P','R','S',
      'T','U','V','X','Y','Z',
      '1','2','3','4','5','6',
      '7','8','9','0'
    );
    
    $name = "";
    
    for($i = 0; $i < $number; $i++) {
      $index = rand(0, count($arr) - 1);
      $name .= $arr[$index];
    }

    return $name;
  }	

  if($_GET['get'] === 'url') {
    if($_GET['method'] === 'add') {
      $shorturl = generate_name(4);
      $url = htmlspecialchars(file_get_contents('php://input'));
      if($url) {
        if(!preg_match('@^(?:http://)@i', $url))
        $url = 'http://'.$url;

        $url = explode('//', $url);
        $url = $url[1];
      } else {
        $return = array(
          "status" => "error",
          "message" => "Empty URL",
          "shorturl" => ""
        );
        
        echo json_encode($return);
        exit();
      }
      
      $check = $Db->dbQuery('id, link, alias', 'link', 'link = "'.$url.'" OR alias = "'.$url.'"');
      $check = $Db->dbAssoc($check, true);
      if($check) {
        $return = array(
          "status" => "ok",
          "message" => "http://".$check['link']." already exists in database",
          "shorturl" => "http://".$check['alias']
        );
        
        echo json_encode($return);
      } else {
        $Db->dbInsert('link', 'link, alias', '"'.$url.'", "'.$shorturl.'"');
        
        $return = array(
          "status" => "ok",
          "message" => "http://'.$url.' added to database",
          "shorturl" => "http://".$config['main']['domain']."/".$shorturl
        );
        
        echo json_encode($return);
      }
    }
  }  
} 

if ($_SERVER['REQUEST_METHOD'] === 'GET')
{
  if($_SERVER['QUERY_STRING']) {
    $url = explode('=', $_SERVER['QUERY_STRING'])[1];

    $check = $Db->dbQuery('id, link, alias', 'link', 'alias = "'.$url.'"');
    $check = $Db->dbAssoc($check, true);
    if($check) {
      $Db->dbUpdate('link', 'clicks = clicks + 1', 'id = '.$check['id'].'');
      header("Location: http://{$check['link']}");
    }
  }
  ?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <link href="http://www.xiag.ch/files/css/testtask-styles.css?data=26.07.2012" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
    <script src="js/init.js"></script>
    <script src="js/utils.js"></script>
    <script src="js/controller.js"></script>
    <title>XIAG test task</title>
  </head>
  <body>
    <div class="content">
      <header>URL shortener</header>
      <form>
        <table>
          <tbody>
            <tr>
              <th>Long URL</th>
              <th>Short URL</th>
            </tr>
          <tr>
            <td>
              <input type="url" name="url">
              <input type="submit" value="Do!">
            </td>
            <td id="result">
            </td>
          </tr>
          </tbody>
        </table>
      </form>
    </div>
  </body>
</html>
<?
}
?>