<?php if (!defined("SYSTEM")) die('Error 404');
include('templates/header.php');?>
<section class="section">
  <div class="container">
    <?php include('templates/navbar.php');?>
    <div class="columns">
      <div class="column">
        <div class="box">
          <h2 class="title">Search message</h2>
          <form action="/index/search" method="post">
            <div class="field">
              <label class="label">Etc: ddos&(литва|латвия) if you want to find<br>
              the message with words ddos and Литва or ddos and Латвия<br>
            Usually you need AND logic so please use & or space for that.<br>
            Use литва<1>латвия if you need these words in 1 step one form other.</label>
              <div class="control">
                <input class="input" name="message" type="text" placeholder="string" autocomplete="false" />
              </div>
            </div>
            <div class="control">
              <button class="button is-link">Submit</button>
            </div>
          </form>
        </div>
        <div class="box">
          <h2 class="title">Add channel for parsing</h2>
          <form action="/index/add" method="post">
            <div class="field">
              <label class="label">If telegram link is https://t.me/MySuperChannel <br>
              please add only MySuperChannel</label>
              <div class="control">
                <input class="input" name="channel" type="text" placeholder="Channel name without protocol and domain name" autocomplete="false" />
              </div>
            </div>
            <div class="control">
              <button class="button is-link">Submit</button>
            </div>
          </form>
        </div>
      </div>
      <div class="column">
        <div class="box">
          <h2 class="title">Search file history by sha1</h2>
          <form action="/index/sha1" method="post">
            <div class="field">
              <label class="label">Files are located in ./media folder and organized by extensions. File name = sha1.<br>
              You need to rename them to extract if you got splited archives<br> like some_name.7z.001 and some_name.7z.002<br>
            You can see the original file name in that DB.</label>
              <div class="control">
                <input class="input" name="sha1" type="text" placeholder="sha1 hash" autocomplete="false" />
              </div>
            </div>
            <div class="control">
              <button class="button is-link">Submit</button>
            </div>
          </form>
        </div>
        <div class="box">
        </div>
      </div>
    </div>
  </div>
</section>
<?php include('templates/footer.php');?>
