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
class Admin_Reset_count_Controller extends Admin_Controller {
  public function index() {
   $item_id = Input::instance()->get("item_id");
    print $this->_get_view($item_id);
  }

  public function handler() {
   $item_id 	= Input::instance()->get("item_id");
    access::verify_csrf();
	$form = $this->_get_form();
	if ($form->validate()) {
      $reset_count 	= $form->reset->reset_count->value;
	  $item 		= ORM::factory("item", $item_id);
	  $count		= $form->reset->count->value;
        if ($reset_count):
         db::build()
           ->update("items")
           ->set("view_count", $count)
           ->where("id", "=", $item_id)
           ->execute();
		  message::success(t('The view count has been updated.  <a class="g-album-link" href="%url" class="g-dialog-link">Return to item</a>.',
          		array("url" => html::mark_clean(url::site("items/$item_id")))));
        endif;
	   url::redirect("admin/reset_count?item_id=$item_id");
    }
    print $this->_get_view($form);
  }

  private function _get_view($item_id) {
    $v = new Admin_View("admin.html");
    $v->content = new View("admin_reset_count.html");
    $v->content->form = empty($form) ? $this->_get_form($item_id) : $form;
    return $v;
  }

  private function _get_form() {
    $item_id	= Input::instance()->get("item_id");
	$item		= ORM::factory("item", $item_id);
    $form 	= new Forge("admin/reset_count/handler?item_id=$item_id", "", "post", array("id" => "g-admin-form"));
		
    $group = $form->group("reset")
		->label(t('Reset count'));
	$group->input("item_id")->label(t("Item to be changed:"))
		->value($item->title)->disabled(true);
	$group->input("count")->label(t("new count for this item:"))
		->value("")
		->rules("required")
		->rules("valid_numeric|length[1,5]");
    $group->checkbox("reset_count")->label(t("Check to reset the item count for this item."))
        ->checked(false);

    $group->submit("submit")->value(t("Commit changes"));

    return $form;
  }
}