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

class Admin_TreeView_Controller extends Admin_Controller {
  public function index() {
    // Generate a new admin page.
    $view = new Admin_View("admin.html");
    $view->content = new View("admin_treeview.html");
    $view->content->treeview_admin_form = $this->_get_admin_form();
    print $view;
  }

  private function _get_admin_form() {
    $form = new Forge("admin/treeview/saveprefs", "", "post",
                      array("id" => "g-treeview-admin-form"));

    $group = $form->group("preferences")->label(t("Settings"));

    $group->input("treeview_menutitle")
      ->label(t("Menu Title"))
      ->value(module::get_var("treeview", "menutitle", "Tree View"));
    $group->dropdown("treeview_viewtype")
            ->id("treeview_viewtype")
            ->label(t("View Type"))
            ->options(array("list" => t("List"), "csstree" => t("CSS Tree"), "dtree" => t("DTree")))
            ->selected(module::get_var("treeview", "viewstyle", "list"));
    $group->checkbox("treeview_hidemenu")
          ->label(t("Hide menu link to \"Tree View\""))
          ->checked( module::get_var("treeview", "hidemenu") );
    $group->checkbox("treeview_showdetails")
          ->label(t("Display details (thumbnail, description) in \"Tree View\"."))
          ->checked( module::get_var("treeview", "showdetails") );

    $group_modules = $form->group("preferences_modules")->label(t("Module Integration"));
    if (module::is_active("dynamic")) {
    $group_modules->checkbox("treeview_showdynamic")
          ->label(t("Display albums, provided by the \"dynamic\" module."))
          ->checked( module::get_var("treeview", "showdynamic") );
    }
    if (module::is_active("tag_cloud_page")) {
    $group_modules->checkbox("treeview_showtagcloudpage")
          ->label(t("Display Tag Coud, provided by the \"Tag Cloud Page\" module."))
          ->checked( module::get_var("treeview", "showtagcloudpage") );
    }
    if (module::is_active("pages")) {
    $group_modules->checkbox("treeview_showpages")
          ->label(t("Display pages, provided by the \"pages\" module."))
          ->checked( module::get_var("treeview", "showpages") );
    }

    $group_dtree = $form->group("preferences_dtree")->label(t("DTree Settings"));
    $group_dtree->checkbox("dtree_onmouseover")
          ->label(t("Open subalbums on mouseover '+'."))
          ->checked( module::get_var("treeview", "dtree_openonmouseover") );
    $group_dtree->checkbox("dtree_closesamelevel")
          ->label(t("Close other albums in in the same level."))
          ->checked( module::get_var("treeview", "dtree_closesamelevel") );

    // Add a save button to the form.
    $form->submit("SaveSettings")->value(t("Save"));

    // Return the newly generated form.
    return $form;
  }

  public function saveprefs() {
    // Prevent Cross Site Request Forgery
    access::verify_csrf();

    $form = $this->_get_admin_form();
    if ($form->validate()) {
      Kohana_Log::add("error",print_r($form,1));    

    // Save form variables.
      $treeview_menutitle = Input::instance()->post("treeview_menutitle");
      if ( $treeview_menutitle == "" ) { $treeview_menutitle = "Tree View"; }
      module::set_var("treeview", "menutitle", $treeview_menutitle);

      module::set_var("treeview", "viewstyle",  Input::instance()->post("treeview_viewtype"));
      module::set_var("treeview", "hidemenu",  Input::instance()->post("treeview_hidemenu"));
      module::set_var("treeview", "showdetails",  Input::instance()->post("treeview_showdetails"));
      module::set_var("treeview", "showdynamic",  Input::instance()->post("treeview_showdynamic"));
      module::set_var("treeview", "showtagcloudpage",  Input::instance()->post("treeview_showtagcloudpage"));
      module::set_var("treeview", "showpages",  Input::instance()->post("treeview_showpages"));
      module::set_var("treeview", "modulesexpandable",  Input::instance()->post("treeview_modulesexpandable"));     
      module::set_var("treeview", "dtree_openonmouseover",  Input::instance()->post("dtree_onmouseover"));
      module::set_var("treeview", "dtree_closesamelevel",  Input::instance()->post("dtree_closesamelevel"));
      message::success(t("Your settings have been saved."));

      url::redirect("admin/treeview");
    }

    // Else show the page with errors
    $view = new Admin_View("admin.html");
    $view->content = new View("admin_treeview.html");
    $view->content->treeview_admin_form = $form;
    print $view;
  }
}
