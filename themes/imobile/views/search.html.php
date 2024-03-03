<?php defined("SYSPATH") or die("No direct script access.") ?>

<div data-role="panel" id="navpanel" data-theme="b" data-display="push" data-position="right">
    <ul data-role="listview">
        <li data-icon="delete" class="ui-corner-all ui-nodisc-icon"><a href="#" data-rel="close"><?= t("Close menu") ?></a></li>
        <? if (module::is_active("search")): ?>
            <form action="<?= url::site("search") ?>" method="get" data-ajax="false">
                <li data-icon="search" class="ui-corner-all ui-nodisc-icon"><input name="q" id="search" value="" placeholder="<?= t("Search") ?>" data-theme="a" type="search"></li>
            </form>
        <? endif ?>

        <? if ($user->guest): ?>
            <li><a data-role="button" href="#popupLogin" data-rel="popup" data-position-to="window"><?= t("Login") ?></a></li>
            <div data-role="popup" id="popupLogin" data-theme="a" class="ui-corner-all">
                <form action="<?= url::abs_site("") ?>login/auth_html" method="post" data-ajax="false">
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
            </div>
        <? else: ?>
            <li><a data-role="button" href="<?= url::site("logout?csrf=".access::csrf_token()."&amp;continue_url=" . urlencode(url::abs_site(""))) ?>" data-ajax="false"><?= t("Logout") ?></a></li>
        <? endif ?>
    </ul>
</div>

<div data-role="header">
    <a class="ui-btn ui-mini ui-icon-carat-l ui-btn-icon-left ui-corner-all ui-nodisc-icon" href="<?= ORM::factory("item", 0)->url() ?>" data-ajax="false"><?= t("Gallery") ?></a>
    <a id="bars-button" class="ui-btn ui-btn-right ui-btn-icon-notext ui-icon-bars ui-corner-all ui-nodisc-icon ui-btn-inline" href="#navpanel">Menu</a>
    <h1>
        <?= t("Search results") ?>
    </h1>
</div>

<div role="main" class="ui-content">
    <!-- THUMBNAILS -->
    <div class="thumbs"></div>
    <!-- SLIDESHOW -->
    <div class="fotorama"></div>

    <script type="text/javascript">
        var images = [
            <? if (count($items)): ?>
                <? for ($i = 0; $i < count($items); $i++): ?>
                    <?= imobile::itemlink($items[$i], $i) ?>
                <? endfor ?>
            <? endif ?>
        ];
        var img_url = '<?= $theme->url("../imobile3/images/") ?>';
    </script>

</div>

