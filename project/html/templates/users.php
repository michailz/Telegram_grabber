<?php if (!defined("SYSTEM")) die('Error 404');
include('templates/header.php');?>
<section class="section">
  <div class="container">
    <?php include('templates/navbar.php');?>
    <div class="columns">
      <div class="column">
        <div class="box">
          <h2 class="title">Channel + related channel(s)</h2>
          <table class="table is-bordered">
            <thead>
              <tr>
                <th>User_id</th>
                <th>First name</th>
                <th>Last name</th>
                <th>Username</th>
                <th>Phone</th>
                <th>Message count</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($users)):?>
                <?php foreach($users as $user):?>
                  <tr id="<?=$user['user_id']?>">
                      <td><?=$user['user_id']?></td>
                      <td><?=empty($user['first_name'])?"":html_entity_decode($user['first_name'])?></td>
                      <td><?=empty($user['last_name'])?"":html_entity_decode($user['last_name'])?></td>
                      <td><?=empty($user['username'])?"":html_entity_decode($user['username'])?></td>
                      <td><?=empty($user['phone'])?"":html_entity_decode($user['phone'])?></td>
                      <td><a href="<?=$url?>"><?=$user['cnt']?></a></td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>
<?php include('templates/footer.php');?>
