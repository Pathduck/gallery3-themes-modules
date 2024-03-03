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
class treeview_installer {
  static function install() {
    module::set_var("treeview", "menutitle", "Tree View");
    module::set_var("treeview", "hidemenu", false);
    module::set_var("treeview", "showdetails", true);
    module::set_var("treeview", "showdynamic", true);
    module::set_var("treeview", "showtagcloudpage", false);
    module::set_var("treeview", "showpages", true);
    module::set_var("treeview", "dtree_openonmouseover",  false);
    module::set_var("treeview", "dtree_closesamelevel",  false);
    // Set the module version number.
    module::set_version("treeview", 4);
  }

  static function upgrade($version) {
  /* not actually necessary yet, implemented for develope reasons */
    module::set_var("treeview", "menutitle", "Tree View");
    module::set_var("treeview", "hidemenu", false);
    module::set_var("treeview", "showdetails", true);
    module::set_var("treeview", "showdynamic", true);
    module::set_var("treeview", "showtagcloudpage", false);
    module::set_var("treeview", "showpages", true);
    module::set_var("treeview", "dtree_openonmouseover",  false);
    module::set_var("treeview", "dtree_closesamelevel",  false);
    // Set the module version number.
    module::set_version("treeview", 4);
  }

  static function deactivate() {
    module::clear_var("treeview", "menutitle");
    module::clear_var("treeview", "hidemenu");
    module::clear_var("treeview", "showdetails");
    module::clear_var("treeview", "showdynamic");
    module::clear_var("treeview", "showtagcloudpage");
    module::clear_var("treeview", "showpages");
    module::clear_var("treeview", "dtree_openonmouseover");
    module::clear_var("treeview", "dtree_closesamelevel");
  }

}
