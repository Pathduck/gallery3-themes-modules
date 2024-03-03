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
class Treeview_Controller extends Controller {
  public function index() {
    // Require view permission for the root album for security purposes.
    $album = ORM::factory("item", 1);
    access::required("view", $album);

    // Set up breadcrumbs.
    $breadcrumbs = array();
    $root = item::root();
    $breadcrumbs[] = Breadcrumb::instance($root->title, $root->url())->set_first();
    $breadcrumbs[] = Breadcrumb::instance(t("Album Tree View"), url::site("treeview"))->set_last();

    // Set up and display the actual page.
    $style = module::get_var("treeview", "viewstyle", "list");
    $template = new Theme_View("page.html", "other", "Tree View");
    $template->set_global(array("breadcrumbs" => $breadcrumbs));
    $template->page_title = t("Gallery :: Album Tree View");
    $template->content = new View("treeview_page_{$style}.html");
    $template->content->title = t("Album Tree View");
    $template->content->root = item::root();

    // Display the page.
    print $template;
  }
}
