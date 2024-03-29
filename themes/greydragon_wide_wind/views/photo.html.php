<?php defined("SYSPATH") or die("No direct script access.") ?>

<? if (access::can("view_full", $theme->item())): ?>
<!-- Use javascript to show the full size as an overlay on the current page -->
<script type="text/javascript">
  $(document).ready(function() {
    full_dims = [<?= $theme->item()->width ?>, <?= $theme->item()->height ?>];
    $(".g-fullsize-link").click(function() {
      $.gallery_show_full_size(<?= html::js_string($theme->item()->file_url()) ?>, full_dims[0], full_dims[1]);
      return false;
    });

    // After the image is rotated or replaced we have to reload the image dimensions
    // so that the full size view isn't distorted.
    $("#g-photo").on("gallery.change", function() {
      $.ajax({
        url: "<?= url::site("items/dimensions/" . $theme->item()->id) ?>",
        dataType: "json",
        success: function(data, textStatus) {
          full_dims = data.full;
        }
      });
    });
  });
</script>
<? endif ?>

<div id="g-item">
  <?= $theme->photo_top() ?>

  <div id="g-info">
    <h1><?= html::purify($item->title) ?></h1>
  </div>

  <?= $theme->add_paginator("top"); ?>

  <div id="g-photo">
    <?= $theme->resize_top($item) ?>
    <? if (access::can("view_full", $item)): ?>
    <a href="<?= $item->file_url() ?>" class="g-fullsize-link" title="<?= t("View full size")->for_html_attr() ?>">
      <? endif ?>
      <?= $item->resize_img(array("id" => "g-item-id-{$item->id}", "class" => "g-resize")) ?>
      <? if (access::can("view_full", $item)): ?>
    </a>
    <? endif ?>
    <?= $theme->resize_bottom($item) ?>
  </div>

  <div id="g-info-bottom">
    <div><?= nl2br(html::purify($item->description)) ?></div>
  </div>

  <?= $theme->photo_bottom() ?>
  <?= $theme->add_paginator("bottom"); ?>
</div>
