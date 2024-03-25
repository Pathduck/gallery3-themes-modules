<?php defined("SYSPATH") or die("No direct script access.");
/**
 * Gallery - a web based photo album viewer and editor
 * Copyright (C) 2000-2009 Bharat Mediratta
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or (at
 * your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street - Fifth Floor, Boston, MA  02110-1301, USA.
 */
class reset_counts_event_Core {
  static function site_menu($menu, $theme) {
    $item = $theme->item();
	$user = identity::active_user();
    if ($item && $item->is_album() && $user->admin) {
      $menu->get("options_menu")
        ->append(Menu::factory("link")
                 ->id("reset")
                 ->label(t("Reset item counts"))
                 ->css_id("g-menu-reset-link")
                 ->url(url::site("admin/reset_counts?album_id={$item->id}")));
    }
	if ($item && ($item->is_photo() || $item->is_movie()) && $user->admin) {
      $menu->get("options_menu")
        ->append(Menu::factory("link")
                 ->id("reset")
                 ->label(t("Reset item count"))
                 ->css_id("g-menu-reset-link")
                 ->url(url::site("admin/reset_count?item_id={$item->id}")));
    }
  }

  static function context_menu($menu, $theme, $item) {
    $user = identity::active_user();
	if ($user->admin) {
      if ($item->is_album()) {
	    $menu->get("options_menu")
          ->append(Menu::factory("link")
                   ->id("reset")
                   ->label(t("Reset album only count"))
                   ->css_class("ui-icon-refresh")
                   ->url(url::site("admin/reset_count?item_id={$item->id}")));
      } else {
        $menu->get("options_menu")
          ->append(Menu::factory("link")
                   ->id("reset")
                   ->label(t("Reset count"))
                   ->css_class("ui-icon-refresh")
                   ->url(url::site("admin/reset_count?item_id={$item->id}")));
      }
    }
  }
}