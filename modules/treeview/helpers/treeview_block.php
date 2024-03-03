<?php defined("SYSPATH") or die("No direct script access.");
/**
 * Gallery - a web based photo album viewer and editor
 * Copyright (C) 2000-2014 Bharat Mediratta
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
class treeview_block_Core {
  static function get_site_list() {
    // Create a sidebar block to link to the tree view page.
    return array("treeview_block" => t("Tree View"));
  }

  static function get($block_id, $theme) {
    // Generate the sidebar block for linking to the tree view page.
    $block = "";
    switch ($block_id) {
    case "treeview_block":
      $block = new Block();
      $block->css_id = "g-treeview";
      $block->title = t("Tree View");
      $block->content = new View("treeview_block.html");
      break;
    }
    return $block;
  }
}
