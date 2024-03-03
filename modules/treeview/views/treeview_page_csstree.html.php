<?php defined("SYSPATH") or die("No direct script access.") ?>
  <style type="text/css">
/* hide Sidebar Block "albumtree" */
div#g-albumtree.g-block {
    display: none!important;
}
  </style>
<div id="g-treeview-header" class="g-page-block">
  <h1><?= html::clean($title) ?></h1>
</div>
<div id="g-treeview-body" class="g-page-block-content">
  <div class="g-treeview-root">
<?
function maketree($album, $level){
//print out the list item
?>

  <? if ($album->viewable()->children_count(array(array("type", "=", "album")))) { ?>
  <div class="g-treeview-album g-treeview-haschildren g-treeview-lv<?= $level ?>" id="g-album<?= $album->id ?>">
  <? }else {?>
  <div class="g-treeview-album g-treeview-item" id="g-album<?= $album->id ?>">
  <? }  ?>

    <a href="<?= item::root()->url() ?><?= $album->relative_url() ?>"><?= html::purify($album->title) ?>
    <? if (module::get_var("treeview", "showdetails")): ?>
      <ul class="g-treeview-details">
      <? if ($album->has_thumb()): ?>
      <li><?= $album->thumb_img(array("class" => "g-thumbnail")) ?></li>
      <? endif ?>
      <li><h2><?= html::purify($album->title) ?></h2></li>
      <li class="g-metadata">
        <strong class="caption"><?= t("Contained photos:")?></strong>
        <?= " ".$album->viewable()->children_count(array(array("type", "=", "photo"))); ?>
      </li>
      <li class="g-metadata"><?= str_replace("\n", "<br>", html::purify($album->description)) ?></li>
      </ul>
    <? endif ?>
    </a>
    <div class="g-treeview-album g-treeview-lv<?= $level ?>-children" id="g-album<?= $album->id ?>children">
<?
  //recurse over the children, and print their list items as well
  foreach ($album->viewable()->children(null, null, array(array("type", "=", "album"))) as $child){
    maketree($child, $level+1);
  }
?>
    </div>
  </div>
<?
}
maketree($root,0);
?>
  </div>

<? // List dynamic albums provided by "dynamic", "tag_cloud_page"
$show_dynamic = ((module::get_var("treeview", "showdynamic")) && (module::is_active("dynamic"))) ? true : false;
$show_tagcloudpage = ((module::get_var("treeview", "showtagcloudpage")) && (module::is_active("tag_cloud_page"))) ? true : false;
    if ($show_dynamic || $show_tagcloudpage) {
?>
  <div id="g-treeview-header" class="g-page-block">
    <h1><?= t("More Contents") ?></h1>
  </div>
  <div class="g-treeview-albumnav g-treeview-root">
    <div class="g-treeview-album">
        <? if ($show_dynamic) { ?>
          <div class="dTreeNode">
              <img src="<?= url::base(false) ?>modules/treeview/images/join.gif">
              <a href="<?= url::site("dynamic/updates/") ?>"><?= t("Recent changes") ?></a>
          </div>
          <div class="dTreeNode">
              <? if ($show_tagcloudpage) { ?>
                <img src="<?= url::base(false) ?>modules/treeview/images/join.gif">
              <? } else { ?>
                  <img src="<?= url::base(false) ?>modules/treeview/images/joinbottom.gif">
              <? } ?>
              <a href="<?= url::site("dynamic/popular/") ?>"><?= t("Most viewed") ?></a>
          </div>
        <? } ?>
        <? if ($show_tagcloudpage) { ?>
          <div class="dTreeNode">
              <img src="<?= url::base(false) ?>modules/treeview/images/joinbottom.gif">
              <a href="<?= url::site("tag_cloud_page") ?>"><?= t("Tag Cloud") ?></a>
          </div>
        <? } ?>
    </div>
  </div>
<? } ?>

<?
// List Static Pages provided by "pages" module
    if ( (module::get_var("treeview", "showpages")) && (module::is_active("pages")) ):
?>
  <div id="g-treeview-header" class="g-page-block">
    <h1><?= t("Pages Links") ?></h1>
  </div>
  <div class="g-treeview-albumnav g-treeview-root">
<?
      // Create a new list of all Pages and their links.
      // Query the database for all existing pages.
      //  If at least one page exists, display the sidebar block.
      $query = ORM::factory("static_page");
      $pages = $query->order_by("title", "ASC")->find_all();
      $pages_count = count($pages);
      if ($pages_count > 0) {
        // Loop through each page and generate an HTML list of their links and titles.
      foreach ($pages as $one_page) {
?>
        <div class="g-treeview-album">
          <div class="dTreeNode">
          <?php
            $pages_counter++;
            if( $pages_counter < $pages_count ) { ?>
              <img src="<?= url::base(false) ?>modules/treeview/images/join.gif">
            <? } else { ?>
              <img src="<?= url::base(false) ?>modules/treeview/images/joinbottom.gif">
            <? } ?>
          <a href="<?= url::site("pages/show/". $one_page->name) ?>"><?= t($one_page->title) ?>
        <? if (module::get_var("treeview", "showdetails")): ?>
          <ul class="g-treeview-details">
          <li><h2><?= html::purify($one_page->title) ?></h2></li>
          <li class="g-metadata"><?= substr(strip_tags($one_page->html_code, '<br><b><strong><h1><h2><h3><h4><h5><h6><p><div>'), 0, 300) ?> ...</li>
          </ul>
        <? endif ?>
          </a>
          </div>
        </div>
<?
        }
      }
?>
  </div>
<? endif ?>

</div>
