<?php if (!defined("SYSTEM")) die('Error 404');

Class Controller_Person Extends Controller_Base {


  function index() {
    $this->registry['template']->set ('title', 'Person');
    $db = $this->registry->get('db');
    $q = $db->prepare("SELECT p.id, p.memberid, p.name, p.realname, p.notes, p.projects, count(*) c
        FROM person p
        INNER JOIN permes pm ON p.id = pm.person_id
        INNER JOIN message m ON pm.message_id = m.id
        GROUP BY p.id
        UNION SELECT p.id, p.memberid, p.name, p.realname, p.notes, p.projects, 0 c
        FROM person p
	      WHERE p.id NOT IN (SELECT distinct person_id FROM permes)
        ORDER BY memberid");
    $q->execute();
    $result = $q->fetchAll();
    $this->registry['template']->set('persons', $result);
    $this->registry['template']->show('person/index');
  }

  function custom() {

    if(!empty($this->registry->args[0])) {
      $personid = intval($this->registry->args[0]);
      $db = $this->registry->get('db');
      $q = $db->prepare("SELECT * FROM person WHERE id = ?;");
      $q->execute(array($personid));
      $person = $q->fetchAll();

      // From and to alltogether
      $q = $db->prepare("SELECT count(*) allmessages
          FROM message
          WHERE addressee @> array[" . $personid . "]");
      $q->execute();
      $alltogether = $q->fetchAll();

      // Select by name
      $q = $db->prepare("SELECT id FROM person WHERE realname = ?;");
      $q->execute(array($person[0]['realname']));
      $allids = $q->fetchAll();


      $this->registry['template']->set('alltogether', $alltogether);
      $this->registry['template']->set('allids', $allids);
      $this->registry['template']->set('person_id', $personid);
      $this->registry['template']->set ('title', 'Person');
      $this->registry['template']->set ('person', $person);
      $this->registry['template']->show('person/custom');
    } else { show404(); }

  }



}
