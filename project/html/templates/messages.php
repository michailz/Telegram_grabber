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
                          <?php if (!empty($message['filename'])):?>
                            <?php if (!empty($message['file_extension']) and (in_array($message['file_extension'], array('.jpg', '.jpeg', '.png', '.gif', '.webp')))):?>
                              <br><img src='/media/extension_<?=ltrim($message['file_extension'], '.')?>/<?=$message['filehash_sha1'].$message['file_extension']?>' alt='<?=$message['filename'].$message['file_extension']?>'>
                            <?php endif?>
                            <?php if (!empty($message['file_extension']) and (in_array($message['file_extension'], array('.m4a', '.mp3', '.oga', '.ogg', '.opus', '.wav')))):?>
                              <figure>
                                <figcaption>Listen to <?=$message['filename'] . '<br>Saved as: /media/extension_' . ltrim($message['file_extension'], '.') . '/' .  $message['filehash_sha1'].$message['file_extension']?>:</figcaption>
                                <audio controls>
                                <source src="/media/extension_<?=ltrim($message['file_extension'], '.')?>/<?=$message['filehash_sha1'].$message['file_extension']?>"
                                type="audio/mpeg">
                                        Your browser does not support the <code>audio</code> element.
                                </audio>
                              </figure>
                            <?php endif?>
                            <?php if (!empty($message['file_extension']) and (in_array($message['file_extension'], array('.m4v', '.mkv', '.mov', '.mp4', '.webm')))):?>
                              <figure>
                                <figcaption>
                                  Listen to <?=$message['filename'] . '<br>Saved as: /media/extension_' . ltrim($message['file_extension'], '.') . '/' .  $message['filehash_sha1'].$message['file_extension']?>:
                                </figcaption>
                                <video width="320" height="240" controls>
                                  <source src="/media/extension_<?=ltrim($message['file_extension'], '.')?>/<?=$message['filehash_sha1'].$message['file_extension']?>">
                                    Your browser does not support the video tag.
                                </video>
                              </figure>
                            <?php endif?>
                            <br>
                            <a href="/media/extension_<?=ltrim($message['file_extension'], '.')?>/<?=$message['filehash_sha1'].$message['file_extension']?>" download>Download <?=$message['filename'].$message['file_extension']?></a>
                          <?php endif?>
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
