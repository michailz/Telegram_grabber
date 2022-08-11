<?php if (!defined("SYSTEM")) die('Error 404');

Class Controller_Index Extends Controller_Base {

  function index() {
    $this->registry['template']->set('title', 'Telegram parser');
    $this->registry['template']->show('search');
  }



  function search() {
    if(!empty($_POST['message'])) {
      $message = $_POST['message'];
      $message = str_replace(array('"', "'", ',', '\\', '/'), '', $message);
      $message = str_replace(' ', '&', $message);
      $message = preg_replace("/(&)\\1+/", "$1", $message);
      $message = str_replace('|&', '|', $message);
      $message = str_replace('&|', '|', $message);
      $message = preg_replace("/(\|)\\1+/", "$1", $message);
      $db = $this->registry->get('db');

      $q = $db->prepare("SELECT count(*) c FROM messages where ts @@ to_tsquery('russian', ?)");
      $q->execute(array($message));
      $count = $q->fetchAll();
      $count = $count[0]['c'];
      $pages = floor($count/400);
      $arr = array();
      $arr['term'] = $message;
      $arr['pages'] = $pages + 1;
      $arr['page'] = 1;
      header('Location: /message/show/' . base64_encode(json_encode($arr, JSON_UNESCAPED_UNICODE)));
    }

  }


  function channel() {
    if(!empty($this->registry->args[0])) {
      $db = $this->registry->get('db');
      $channel_id = intval($this->registry->args[0]);
        $q = $db->prepare("
            SELECT c.channel_id, c.title, c.date, c.username, count(*) cnt FROM channels c
            LEFT JOIN messages m ON c.channel_id = m.channel_id
            WHERE order_channel IN (SELECT order_channel FROM channels
            WHERE channel_id = ? AND m.action IS NULL )
            GROUP BY c.channel_id, c.title, c.date, c.username;");
        $q->bindParam(1, $channel_id, PDO::PARAM_INT);
        $q->execute();
        $channels = $q->fetchAll();
        $count = $channels[0]['cnt'];
        $pages = floor($count/400);
        $arr = array();
        $arr['channel'] = $channel_id;
        $arr['pages'] = $pages + 1;
        $arr['page'] = 1;
        $url = '/message/show/' . base64_encode(json_encode($arr, JSON_UNESCAPED_UNICODE));
        $this->registry['template']->set('title', 'Telegram parser');
        $this->registry['template']->set('url', $url);
        $this->registry['template']->set('channels', $channels);
        $this->registry['template']->show('channels');

    }

  }

  function user() {
    if(!empty($this->registry->args[0])) {
      $db = $this->registry->get('db');
      $user_id = intval($this->registry->args[0]);
      $q = $db->prepare("SELECT u.user_id, u.first_name, u.last_name, u.username, u.phone, count(*) cnt
      FROM users u
      LEFT JOIN messages m ON u.user_id = m.sender_id
      WHERE m.sender_type = 1 AND u.user_id = ? AND m.action IS NULL
      GROUP BY u.user_id, u.first_name, u.last_name, u.username, u.phone");
    $q->bindParam(1, $user_id, PDO::PARAM_INT);
    $q->execute();
    $users = $q->fetchAll();
    $count = $users[0]['cnt'];
    $pages = floor($count/400);
    $arr = array();
    $arr['user'] = $user_id;
    $arr['pages'] = $pages + 1;
    $arr['page'] = 1;
    $url = '/message/show/' . base64_encode(json_encode($arr, JSON_UNESCAPED_UNICODE));
    $this->registry['template']->set('title', 'Telegram parser');
    $this->registry['template']->set('url', $url);
    $this->registry['template']->set('users', $users);
    $this->registry['template']->show('users');
    } else {
      show404();
    }
  }

  function sha1() {
    if(!empty($_POST['sha1'])) {
      $db = $this->registry->get('db');
      $sha1 = $_POST['sha1'];
      $q = $db->prepare("SELECT m.id, m.message_id, m.channel_id, c.title, m.text,
        m.sender_name, m.sender_id, m.sender_type, m.reply_id, m.forward_id,
        m.forward_name, m.create_date, m.edit_date, m.filename, m.file_extension, m.filehash_sha1 FROM messages m
        left join channels c on c.channel_id = m.channel_id
        WHERE m.filehash_sha1 = ?
        ORDER BY create_date ASC;");
      $q->bindParam(1, $sha1);
      $q->execute();
      $messages = $q->fetchAll();
      $this->registry['template']->set('pagination', array());
      $this->registry['template']->set('title', 'Telegram parser');
      $this->registry['template']->set('messages', $messages);
      $this->registry['template']->show('messages');
    } else {
      show404();
    }
  }

  function add() {
    if(!empty($_POST['channel'])) {
      $channel = $_POST['channel'];
      $db = $this->registry->get('db');
      $q = $db->prepare("INSERT INTO orders (channel) VALUES (?);");
      $q->bindParam(1, $channel);
      $q->execute();
    } else { show404(); }
    header('Location: /');
    exit();
  }

}
