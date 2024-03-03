<?php defined("SYSPATH") or die("No direct script access.") ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=2.0, user-scalable=1, minimal-ui"/>
        <link rel="apple-touch-icon-precomposed"
              href="<?= url::file(module::get_var("gallery", "apple_touch_icon_url")) ?>" />
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="apple-mobile-web-app-status-bar-style" content="black" />
        <? $theme->start_combining("script,css") ?>

        <title>
            <? if ($page_title): ?>
                <?= $page_title ?>
            <? else: ?>
                <? if ($theme->item()): ?>
                    <? if ($theme->item()->is_album()): ?>
                        <?= t("Browse Album :: %album_title", array("album_title" => $theme->item()->title)) ?>
                    <? elseif ($theme->item()->is_photo()): ?>
                        <?= t("Photo :: %photo_title", array("photo_title" => $theme->item()->title)) ?>
                    <? else: ?>
                        <?= t("Movie :: %movie_title", array("movie_title" => $theme->item()->title)) ?>
                    <? endif ?>
                <? elseif ($theme->tag()): ?>
                    <?= t("Browse Tag :: %tag_title", array("tag_title" => $theme->tag()->name)) ?>
                <? else: /* Not an item, not a tag, no page_title specified.  Help! */ ?>
                    <?= t("Gallery") ?>
                <? endif ?>
            <? endif ?>
        </title>

        <?= $theme->script("staystandalone.min.js") ?>
        <?= $theme->script("jquery-1.11.0.min.js") ?>
        <?= $theme->script("jquery.mobile-1.4.1.js") ?>
        <?= $theme->script("jquery.unveil.min.js") ?>
        <?= $theme->script("fotorama.min.js") ?>
        <?= $theme->script("imobile.min.js") ?>

        <?= $theme->head() ?>

        <!-- LOOKING FOR YOUR CSS? It's almost all been combined into the link below -->
        <?= $theme->get_combined("css") ?>

        <?= $theme->css("jquery.mobile-1.4.1.min.css") ?>
        <?= $theme->css("fotorama.css") ?>
        <?= $theme->css("imobile.css") ?>

        <!-- LOOKING FOR YOUR JAVASCRIPT? It's all been combined into the link below -->
        <?= $theme->get_combined("script") ?>




    </head>
    <body <?= $theme->body_attributes() ?>>
    <?= $theme->page_top() ?>
    <div data-role="page" data-theme="b">
        <?= $theme->messages() ?>
        <? if ($header_text = module::get_var("gallery", "header_text")): ?>
            <div id="g-banner">
                <?= $header_text ?>
            </div>
        <? endif ?>

        <? if($page_subtype == "error"): ?>
            <div data-role="header">
                <h1>
                    <?= t("Gallery") ?> - <?= $page_title ?>
                    <a id="bars-button"  class="ui-btn ui-btn-right ui-btn-icon-notext ui-icon-power ui-corner-all ui-nodisc-icon ui-btn-inline" href="<?= url::site("logout?csrf=".access::csrf_token()."&amp;continue_url=" . urlencode(url::abs_site(""))) ?>"><?= t("Logout") ?></a>
                </h1>
            </div>
        <? endif ?>

        <? if($page_subtype == "login"): ?>
            <div data-role="header">
                <h1>
                    <?= t("Gallery") ?> - <?= $page_title ?>
                </h1>
            </div>
        <div role="main" class="ui-content">
            <div data-theme="a" class="ui-field-contain ui-corner-all">
                <fieldset data-role="controlgroup">
                    <form action="<?= url::abs_site("") ?>imobile_login/auth_html" method="post" data-ajax="false">
                        <input type="hidden" name="csrf" value="<?= access::csrf_token() ?>" />
                        <input type="hidden" name="continue_url" value="<?= url::abs_current(true) ?>"  />
                        <div style="padding:10px 20px;">
                            <label for="un" class="ui-hidden-accessible">Username:</label>
                            <input name="name" id="un" value="" placeholder="<?= t("Name") ?>" data-theme="a" type="text">
                            <label for="pw" class="ui-hidden-accessible">Password:</label>
                            <input name="password" id="pw" value="" placeholder="<?= t("Password") ?>" data-theme="a" type="password">
                            <button type="submit" class="ui-btn ui-corner-all ui-shadow ui-btn-b ui-btn-icon-left ui-icon-check"><?= t("Login") ?></button>
                        </div>
                    </form>
                </fieldset>
            </div>
        </div>
        <? else: ?>
            <?= $content ?>
        <? endif ?>

        <div data-role="footer" data-theme="b">
            <? if ($footer_text = module::get_var("gallery", "footer_text")): ?>
                <div class="g-footer_text">
                    <?= $footer_text ?>
                </div>
            <? endif ?>
            <? if (module::get_var("gallery", "show_credits")): ?>
                <?= $theme->credits() ?>
            <? endif ?>
            <?= $theme->page_bottom() ?>
        </div>
    </div>
    </body>
</html>