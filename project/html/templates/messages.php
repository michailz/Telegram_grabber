<?php if (!defined("SYSTEM")) die('Error 404');
include('header.php');?>
    <section class="section">
      <div class="container">
        <?php include('templates/navbar.php');?>
        <div class="columns">
          <div class="column">
            <?php include('pagination.php');?>
            <table class="table is-bordered">
              <thead>
                <tr>
                  <th>Data</th>
                  <th>Channel</th>
                  <th>Sender name</th>
                  <th>Text</th>
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($messages)):?>
                  <?php foreach($messages as $message):?>
                    <tr id="<?=$message['id']?>">
                        <td><?=$message['create_date'];?></td>
                        <td><?=html_entity_decode($message['title']);?></td>
                      <?php if(!empty($message['sender_type']) AND ($message['sender_type']==1)):?>
                        <td><a href="/user/<?=$message['sender_id']?>"><?=empty($message['sender_name'])?"":html_entity_decode($message['sender_name'])?></a></td>
                      <?php else:?>
                        <td><a href="/channel/<?=$message['channel_id']?>"><?=empty($message['sender_name'])?"":html_entity_decode($message['sender_name'])?></a></td>
                      <?php endif;?>
                        <td class="wrap">
                          <?=empty($message['text'])?"":html_entity_decode($message['text'])?>
                          <?=empty($message['filename'])?"":'<br>File: '.$message['filename'].$message['file_extension'].' sha1: '.$message['filehash_sha1'];?>
                        </td>
                    </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
            <?php include('pagination.php');?>
          </div>
        </div>
      </div>
    </section>
<?php include('footer.php');?>
