<?php if (!defined("SYSTEM")) die('Error 404');

Class Controller_Message Extends Controller_Base {


  function index() {

    $this->registry['template']->set('title', 'Telegram parser');
    $this->registry['template']->show('search');
  }

  function show() {
    if(!empty($this->registry->args[0])) {
      $db = $this->registry->get('db');
      $pstring = $this->registry->args[0];
      $pstring = str_replace(' ', '+', $pstring);
      $string = base64_decode(str_replace('_', '/',$pstring));
      $arr = json_decode($string, true);
      $current = ($arr['page']);
      $pagination = array();
      for($i=1; $i <= intval($arr['pages']); $i++) {
        $arr['page'] = $i;
        $pagination[] = str_replace('/', '_', base64_encode(json_encode($arr, JSON_UNESCAPED_UNICODE)));
      }
      if (!empty($arr['term'])) {
      $q = $db->prepare("SELECT m.id, m.message_id, m.channel_id, c.title, m.text,
        m.sender_name, m.sender_id, m.sender_type, m.reply_id, m.forward_id,
        m.forward_name, m.create_date, m.edit_date, m.filename, m.file_extension, m.filehash_sha1 FROM messages m
        left join channels c on c.channel_id = m.channel_id
        WHERE ts @@ to_tsquery('russian', ?) AND m.action IS NULL ORDER BY create_date ASC LIMIT 400 OFFSET ?;");
      $q->bindParam(1, $arr['term']);
      $offset = ($current - 1) * 400;
      $q->bindParam(2, $offset, PDO::PARAM_INT);
      $q->execute();
      $messages = $q->fetchAll();
    }
    if (!empty($arr['user'])) {
      $q = $db->prepare("SELECT m.id, m.message_id, m.channel_id, c.title, m.text,
        m.sender_name, m.sender_id, m.sender_type, m.reply_id, m.forward_id,
        m.forward_name, m.create_date, m.edit_date, m.filename, m.file_extension, m.filehash_sha1 FROM messages m
        left join channels c on c.channel_id = m.channel_id
        WHERE m.sender_id = ? AND m.action IS NULL
        ORDER BY create_date ASC LIMIT 400 OFFSET ?;");
      $q->bindParam(1, $arr['user'], PDO::PARAM_INT);
      $offset = ($current - 1) * 400;
      $q->bindParam(2, $offset, PDO::PARAM_INT);
      $q->execute();
      $messages = $q->fetchAll();
    }
    if (!empty($arr['channel'])) {
      $q = $db->prepare("SELECT m.id, m.message_id, m.channel_id, c.title, m.text,
        m.sender_name, m.sender_id, m.sender_type, m.reply_id, m.forward_id,
        m.forward_name, m.create_date, m.edit_date, m.filename, m.file_extension, m.filehash_sha1 FROM messages m
        left join channels c on c.channel_id = m.channel_id
        WHERE m.channel_id = ? AND m.action IS NULL
        ORDER BY create_date ASC LIMIT 400 OFFSET ?;");
      $q->bindParam(1, $arr['channel'], PDO::PARAM_INT);
      $offset = ($current - 1) * 400;
      $q->bindParam(2, $offset, PDO::PARAM_INT);
      $q->execute();
      $messages = $q->fetchAll();
    }
    } else { show404(); }

    $this->registry['template']->set('current', $pstring);
    $this->registry['template']->set('pagination', $pagination);
    $this->registry['template']->set('title', 'Telegram parser');
    $this->registry['template']->set('messages', $messages);
    $this->registry['template']->show('messages');
  }

}
