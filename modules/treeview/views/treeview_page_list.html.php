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
  <ul class="g-treeview-albumnav g-treeview-root">
<?
function maketree($album){
//print out the list item
?>
  <li class="g-treeview-album">
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

  </li>
<?
  //recurse over the children, and print their list items as well
  foreach ($album->viewable()->children(null, null, array(array("type", "=", "album"))) as $child){
?>
    <ul class="g-treeview-albumnav">
<?
    maketree($child);
?>
    </ul>
<?
  }
}
maketree($root,0);
?>
  </ul>


<? // List dynamic albums provided by "dynamic", "tag_cloud_page"
$show_dynamic = ((module::get_var("treeview", "showdynamic")) && (module::is_active("dynamic"))) ? true : false;
$show_tagcloudpage = ((module::get_var("treeview", "showtagcloudpage")) && (module::is_active("tag_cloud_page"))) ? true : false;
    if ($show_dynamic || $show_tagcloudpage) {
?>
  <div id="g-treeview-header" class="g-page-block">
    <h1><?= t("More Contents") ?></h1>
  </div>
  <ul class="g-treeview-albumnav g-treeview-root">
        <? if ($show_dynamic) { ?>
        <li class="g-treeview-album">
          <a href="<?= url::site("dynamic/updates/") ?>"><?= t("Recent changes") ?></a>
        </li>
        <li class="g-treeview-album">
          <a href="<?= url::site("dynamic/popular/") ?>"><?= t("Most viewed") ?></a>
        </li>
        <? } ?>
        <? if ($show_tagcloudpage) { ?>
        <li class="g-treeview-album">
          <a href="<?= url::site("tag_cloud_page") ?>"><?= t("Tag Cloud") ?></a>
        </li>
        <? } ?>
  </ul>
<? } ?>

<?
// List Static Pages provided by "pages" module
    if ( (module::get_var("treeview", "showpages")) && (module::is_active("pages")) ):
?>
  <div id="g-treeview-header" class="g-page-block">
    <h1><?= t("Pages Links") ?></h1>
  </div>
  <ul class="g-treeview-albumnav g-treeview-root">
<?
      // Create a new list of all Pages and their links.

      // Query the database for all existing pages.
      //  If at least one page exists, display the sidebar block.
      $query = ORM::factory("static_page");
      $pages = $query->order_by("title", "ASC")->find_all();
      if (count($pages) > 0) {

        // Loop through each page and generate an HTML list of their links and titles.
      foreach ($pages as $one_page) {
?>  
        <li class="g-treeview-album">
        <a href="<?= url::site("pages/show/". $one_page->name) ?>"><?= t($one_page->title) ?>
        <? if (module::get_var("treeview", "showdetails")): ?>
          <ul class="g-treeview-details">
          <li><h2><?= html::purify($one_page->title) ?></h2></li>
          <li class="g-metadata"><?= substr(strip_tags($one_page->html_code, '<br><b><strong><h1><h2><h3><h4><h5><h6><p><div>'), 0, 300) ?> ...</li>
          </ul>
        <? endif ?>
        </a>
        </li>
<?
        }
      }
?>
  </ul>
<? endif ?>

</div>
