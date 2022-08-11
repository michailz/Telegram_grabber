<?php if (!defined("SYSTEM")) die('Error 404'); $pg = 1;?>
<? if(count($pagination) > 1):?>
<nav class="pagination is-centered notprintable" role="navigation" aria-label="pagination">
<? if(count($pagination) < 6):?>
  <ul class="pagination-list">
<? foreach($pagination as $p): ?>
    <li><a href="<?=$p?>" class="pagination-link<?=$p==$current?' is-current':''?>" aria-label="Page <?=$pg?>" aria-current="page"><?=$pg?></a></li>
<?php $pg++; ?>
<? endforeach ?>
  </ul>
<? else: ?>
<?php $index = array_search($current, $pagination);
$last_link = $pagination[count($pagination)-1];
?>
<? if($index == 0): ?>
  <a class="pagination-previous" disabled>Previous</a>
<? else: ?>
  <a href="<?=$pagination[$index - 1]?>" class="pagination-previous">Previous</a>
<? endif ?>
<? if($index == count($pagination) - 1):?>
  <a class="pagination-next" disabled>Next page</a>
<? else: ?>
  <a href="<?=$pagination[$index+1]?>" class="pagination-next">Next page</a>
<? endif ?>
  <ul class="pagination-list">
<? if($index < 4): ?>
<?php for($i=0; $i < 5; $i++) {
$page = $i + 1;
echo "\n\t\t\t<li><a href=\"{$pagination[$i]}\" class=\"pagination-link";
if ($pagination[$i] == $current) {echo ' is-current'; }
echo "\" aria-label=\"Page {$page}\" aria-current=\"page\">{$page}</a></li>";
} ?>

    <li><span class="pagination-ellipsis">&hellip;</span></li>
    <li><a href="<?=$last_link?>" class="pagination-link" aria-label="Goto page <?=count($pagination)?>"><?=count($pagination)?></a></li>
  </ul>
<? elseif(count($pagination) - $index < 4):?>
    <li><a href="<?=$pagination[0]?>" class="pagination-link" aria-label="Goto page 1">1</a></li>
    <li><span class="pagination-ellipsis">&hellip;</span></li>
<?php for($i=count($pagination) - 5; $i < count($pagination); $i++) {
$page = $i + 1;
echo "<li><a href=\"{$pagination[$i]}\" class=\"pagination-link";
if ($pagination[$i] == $current) {echo ' is-current'; }
echo "\" aria-label=\"Page {$page}\" aria-current=\"page\">{$page}</a></li>";
} ?>
<? else: ?>
<li><a href="<?=$pagination[0]?>" class="pagination-link" aria-label="Goto page 1">1</a></li>
<li><span class="pagination-ellipsis">&hellip;</span></li>
<li><a href="<?=$pagination[$index-1]?>" class="pagination-link" aria-label="Goto page <?=$index?>"><?=$index?></a></li>
<li><a href="<?=$pagination[$index]?>" class="pagination-link is-current" aria-label="Goto page <?=$index+1?>"><?=$index+1?></a></li>
<li><a href="<?=$pagination[$index+1]?>" class="pagination-link" aria-label="Goto page <?=$index+2?>"><?=$index+2?></a></li>
<li><span class="pagination-ellipsis">&hellip;</span></li>
<li><a href="<?=$last_link?>" class="pagination-link" aria-label="Goto page <?=count($pagination)?>"><?=count($pagination)?></a></li>
<? endif ?>
<? endif ?>
</nav>
<? endif ?>
