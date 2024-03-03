<?php defined("SYSPATH") or die("No direct script access.") ?>
<? $children_all = $item->viewable()->children(); ?>

<div data-role="panel" id="navpanel" data-theme="b" data-display="push" data-position="right">
    <ul data-role="listview" class="ui-listview-outer" data-inset="false">
        <!-- Close Menu -->
        <li data-icon="delete" class="ui-corner-all ui-nodisc-icon"><a href="#" data-rel="close"><?= t("Close menu") ?></a></li>

        <!-- Searchbar -->
        <? if (module::is_active("search")): ?>
            <form action="<?= url::site("search") ?>" method="get" data-ajax="false">
                <li data-icon="search" class="ui-corner-all ui-nodisc-icon"><input name="q" id="search" value="" placeholder="<?= t("Search") ?>" data-theme="a" type="search"></li>
            </form>
        <? endif ?>

        <? if ($user->admin || access::can("add", $item)): ?>
            <!-- Add Item -->
            <li><a href="#popupAddItem" data-role="button" data-rel="popup" data-position-to="window" data-icon="plus" class="ui-nodisc-icon"><?= t("Add item") ?></a></li>
            <div data-role="popup" id="popupAddItem" data-theme="a" class="ui-corner-all">
                <form action="<?= url::site("imobile_uploader/add/".$item->id) ?>" method="post" enctype="multipart/form-data" data-ajax="false">
                    <input type="hidden" name="csrf" value="<?= access::csrf_token() ?>" />
                    <input type="hidden" name="continue_url" value="<?= url::abs_current(true) ?>"  />
                    <div style="padding:10px 20px;">
                        <h3><?= t("Please select file") ?></h3>
                        <label for="item_title" class="ui-hidden-accessible">Title:</label>
                        <input name="title" id="item_title" value="" placeholder="<?= t("Title") ?>" data-theme="a" type="text">
                        <label for="item_description" class="ui-hidden-accessible">Description:</label>
                        <input name="description" id="item_description" value="" placeholder="<?= t("Description") ?>" data-theme="a" type="text">
                        <label for="item_file" class="ui-hidden-accessible">File:</label>
                        <input name="file" id="item_file" value="" placeholder="<?= t("File") ?>" data-theme="a" type="file">
                        <button type="submit" class="ui-btn ui-corner-all ui-shadow ui-btn-b ui-btn-icon-left ui-icon-check"><?= t("Upload") ?></button>
                    </div>
                </form>
            </div>
            <!-- Add Album -->
            <li><a href="#popupAddAlbum" data-role="button" data-rel="popup" data-position-to="window" data-icon="plus" class="ui-nodisc-icon"><?= t("Add album") ?></a></li>
            <div data-role="popup" id="popupAddAlbum" data-theme="a" class="ui-corner-all">
                <form action="<?= url::site("albums/create/".$item->id) ?>" method="post" enctype="multipart/form-data" data-ajax="false" id="g-add-album-form">
                    <input type="hidden" name="csrf" value="<?= access::csrf_token() ?>" />
                    <div style="padding:10px 20px;" id="g-add-album-form">
                        <h3><?= t("Add an album to %album_title", array("album_title" => $item->title)) ?></h3>
                        <label for="title" class="ui-hidden-accessible">Title:</label>
                        <input name="title" id="title" value="" placeholder="<?= t("Title") ?>" data-theme="a" type="text">
                        <label for="description" class="ui-hidden-accessible">Description:</label>
                        <textarea name="description" id="description" value="" placeholder="<?= t("Description") ?>" data-theme="a" type="textarea" cols="" rows=""></textarea>
                        <label for="name" class="ui-hidden-accessible">Directory name:</label>
                        <input name="name" id="name" value="" placeholder="<?= t("Directory name") ?>" data-theme="a" type="text">
                        <label for="slug" class="ui-hidden-accessible">Internet address:</label>
                        <input name="slug" id="slug" value="" placeholder="<?= t("Internet address") ?>" data-theme="a" type="text">
                        <button type="submit" class="ui-btn ui-corner-all ui-shadow ui-btn-b ui-btn-icon-left ui-icon-check"><?= t("Create") ?></button>
                    </div>
                </form>
            </div>
        <? endif; ?>

        <!-- Breadcrumbs -->
        <? if (!empty($breadcrumbs)): ?>
            <li data-role="collapsible" data-iconpos="left" data-collapsed-icon="bullets" data-expanded-icon="carat-u" class="ui-nodisc-icon">
                <h2><?= t("Home") ?></h2>
                <ul data-role="listview" data-inset="false" data-shadow="false" data-corners="false" data-theme="a">
                    <? foreach ($breadcrumbs as $breadcrumb): ?>
                        <li class="ui-alt-icon">
                            <? if (!$breadcrumb->last): ?>
                                <a href="<?= $breadcrumb->url ?>" data-ajax="false">
                                    <?= html::clean(text::limit_chars($breadcrumb->title, module::get_var("gallery", "visible_title_length"))) ?>
                                </a>
                            <? endif; ?>
                        </li>
                    <? endforeach ?>
                </ul>
            </li>
        <? endif; ?>

        <? if ($user->guest): ?>
            <!-- Login -->
            <li data-role="collapsible" data-iconpos="left" data-expanded-icon="user" data-collapsed-icon="user"  data-corners="false" class="ui-nodisc-icon">
            <h2><?= t("Login") ?></h2>
                <ul data-role="listview" data-inset="false" data-shadow="false" data-corners="false">
                    <form action="<?= url::abs_site("") ?>imobile_login/auth_html" method="post" data-ajax="false">
                        <input type="hidden" name="csrf" value="<?= access::csrf_token() ?>" />
                        <input type="hidden" name="continue_url" value="<?= url::abs_current(true) ?>"  />
                        <div style="padding:10px 20px;">
                            <h3><?= t("Please sign in") ?></h3>
                            <label for="un" class="ui-hidden-accessible">Username:</label>
                            <input name="name" id="un" value="" placeholder="<?= t("Name") ?>" data-theme="a" type="text">
                            <label for="pw" class="ui-hidden-accessible">Password:</label>
                            <input name="password" id="pw" value="" placeholder="<?= t("Password") ?>" data-theme="a" type="password">
                            <button type="submit" class="ui-btn ui-corner-all ui-shadow ui-btn-b ui-btn-icon-left ui-icon-check"><?= t("Login") ?></button>
                        </div>
                    </form>
                </ul>
            </li>
        <? else: ?>
            <!-- Logout -->
            <li><a data-role="button" href="<?= url::site("logout?csrf=".access::csrf_token()."&amp;continue_url=" . urlencode(url::abs_site(""))) ?>" data-ajax="false" data-icon="power" class="ui-nodisc-icon"><?= t("Logout") ?></a></li>
        <? endif ?>
    </ul>
</div>

<div data-role="header">
    <? if ($theme->item()->parent_id > 0): ?>
        <a class="ui-btn ui-mini ui-icon-carat-l ui-btn-icon-left ui-corner-all ui-nodisc-icon" href="<?= ORM::factory("item", $theme->item()->parent_id)->url() ?>" data-ajax="false"><?= ORM::factory("item", $theme->item()->parent_id)->title ?></a>
    <? endif ?>
    <a id="bars-button" class="ui-btn ui-btn-right ui-btn-icon-notext ui-icon-bars ui-corner-all ui-nodisc-icon ui-btn-inline" href="#navpanel">Menu</a>
    <h1>
        <?= html::purify($item->title) ?>
    </h1>
</div>

<div role="main" class="ui-content">
    <!-- THUMBNAILS -->
    <div class="thumbs"></div>
    <!-- SLIDESHOW -->
    <div class="fotorama"></div>

    <script type="text/javascript">
        var images = [
            <? if (count($children)): ?>
            <? for ($i = 0; $i < $children_count; $i++): ?>
            <? $child = $children_all[$i] ?>
            <?= imobile::itemlink($child, $i) ?>
            <? endfor ?>
            <? endif ?>
        ];
        var img_url = '<?= $theme->url("../imobile/images/") ?>';
    </script>

</div>

