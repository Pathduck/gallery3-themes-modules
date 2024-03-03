<?php defined("SYSPATH") or die("No direct script access.") ?>
<div data-role="header">
    <? if ($theme->item()->parent_id > 0): ?>
        <a class="ui-btn ui-mini ui-icon-carat-l ui-btn-icon-left ui-corner-all ui-nodisc-icon" href="<?= ORM::factory("item", $theme->item()->parent_id)->url() ?>" data-ajax="false"><?= ORM::factory("item", $theme->item()->parent_id)->title ?></a>
    <? endif ?>
    <h1>
        <?= html::purify($item->title) ?>
    </h1>
</div>
<div role="main" class="ui-content">
    <video controls="controls" poster="<?= $item->thumb_url() ?>" src="<?= $item->file_url() ?>"></video>
</div>
