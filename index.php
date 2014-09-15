<?php
define('IN_SYSTEM', true);

require_once('system/config.php');

$db = new PDO("mysql:host=".$config['db']['host'].";dbname=".$config['db']['name'].";charset=".$config['db']['charset']."", $config['db']['user'], $config['db']['password']);

$get      = trim($_SERVER['REQUEST_METHOD'] === 'GET');
$getPage  = explode('/', $get);

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

      $check = $db->prepare("SELECT * FROM `link` WHERE link = :link OR alias = :link");
      $check->bindValue(':link', $url);
      $check->execute();
      $result = $check->fetch(PDO::FETCH_ASSOC);

      if($result) {
        $return = array(
          "status" => "ok",
          "message" => "http://".$result['link']." already exists in database",
          "shorturl" => "http://".$config['main']['domain']."/".$result['alias']
        );

        echo json_encode($return);
      } else {
        $q = $db->prepare("INSERT INTO `link` (link, alias) VALUES (:link, :alias)");
        $q->execute(array(
          ':link' => $url,
          ':alias' => $shorturl
        ));

        $return = array(
          "status" => "ok",
          "message" => "http://".$url." added to database",
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

    $check = $db->prepare("SELECT * FROM `link` WHERE alias = :link");
    $check->bindValue(':link', $url);
    $check->execute();
    $result = $check->fetch(PDO::FETCH_ASSOC);

    if($result) {
      $db->query("UPDATE `link` SET clicks = clicks + 1 WHERE id = ".$result['id']);
      header("Location: http://{$result['link']}");
    }
  }

  require_once('static/template.html');
}
