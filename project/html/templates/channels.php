<?php if (!defined("SYSTEM")) die('Error 404');
include('templates/header.php');?>
<section class="section">
  <div class="container">
    <?php include('templates/navbar.php');?>
    <div class="columns">
      <div class="column">
        <div class="box">
          <h2 class="title">Channel</h2>
          <table class="table is-bordered">
            <thead>
              <tr>
                <th>Channel_id</th>
                <th>Title</th>
                <th>Date</th>
                <th>Name</th>
                <th>Stored message count</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($channels)):?>
                <?php foreach($channels as $channel):?>
                  <tr id="<?=$channel['channel_id']?>">
                      <td><?=$channel['channel_id']?></td>
                      <td><?=html_entity_decode($channel['title']);?></td>
                      <td><?=$channel['date']?></td>
                      <td><?=empty($channel['username'])?"":html_entity_decode($channel['username'])?></td>
                      <td><a href="<?=$url?>"><?=$channel['cnt']?></a></td>

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
