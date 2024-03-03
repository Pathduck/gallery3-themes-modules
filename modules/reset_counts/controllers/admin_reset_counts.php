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
class Admin_Reset_counts_Controller extends Admin_Controller {
  public function index() {
   $album_id = Input::instance()->get("album_id");
    print $this->_get_view($album_id);
  }

  public function handler() {
   $album_id 	= Input::instance()->get("album_id");
    access::verify_csrf();
	$form = $this->_get_form();
	if ($form->validate()) {
      $reset_counts = $form->reset->reset_counts->value;
	  $item = ORM::factory("item", $album_id);
        if ($reset_counts):
         db::build()
           ->update("items")
           ->set("view_count", 0)
           ->where("left_ptr", ">=", $item->left_ptr)
		   ->where("right_ptr", "<=", $item->right_ptr)
           ->execute();
		  message::success(t('The view counts have been updated.  <a class="g-album-link" href="%url" class="g-dialog-link">Return to album</a>.',
          		array("url" => html::mark_clean(url::site("items/$album_id")))));
        endif;
	   url::redirect("admin/reset_counts?album_id=$album_id");
    }
    print $this->_get_view($form);
  }

  private function _get_view($album_id) {
    $v = new Admin_View("admin.html");
    $v->content = new View("admin_reset_counts.html");
    $v->content->form = empty($form) ? $this->_get_form($album_id) : $form;
    return $v;
  }

  private function _get_form() {
    $album_id	= Input::instance()->get("album_id");
    $album 		= ORM::factory("item", $album_id);
    $form 		= new Forge("admin/reset_counts/handler?album_id=$album_id", "", "post", array("id" => "g-admin-form"));
		
    $group = $form->group("reset")
		->label(t('Reset counts'));
	$group->input("album_id")->label(t("Items in this album will be changed:"))
		->value($album->title)->disabled(true);
    $group->checkbox("reset_counts")->label(t("Check to reset the item counts in this album."))
        ->checked(false);

    $group->submit("submit")->value(t("Commit changes"));

    return $form;
  }
}