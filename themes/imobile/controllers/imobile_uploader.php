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
class Imobile_uploader_Controller extends Controller {

    public function add($id) {
        $album = ORM::factory("item", $id);
        access::required("view", $album);
        access::required("add", $album);
        access::verify_csrf();

        $form = $this->_get_add_form($album);
        if ($form->validate()) {
            batch::start();

            $continue_url = $form->continue_url->value;

            try {
                $temp_filename = $form->add_photos->file->value;
                $item = ORM::factory("item");
                $item->name = basename($temp_filename);
                $item->title = $form->add_photos->title->value;
                $item->description = $form->add_photos->description->value;
                $item->parent_id = $album->id;
                $item->set_data_file($temp_filename);

                $path_info = @pathinfo($item->name);
                if (array_key_exists("extension", $path_info) &&
                    legal_file::get_movie_extensions($path_info["extension"])) {
                    $item->type = "movie";
                    $item->save();
                    log::success("content", t("Added a movie"),
                        html::anchor("movies/$item->id", t("view movie")));
                } else {
                    $item->type = "photo";
                    $item->save();
                    log::success("content", t("Added a photo"),
                        html::anchor("photos/$item->id", t("view photo")));
                }
                module::event("add_photos_form_completed", $item, $form);
            } catch (Exception $e) {
                // Lame error handling for now.  Just record the exception and move on
                Kohana_Log::add("error", $e->getMessage() . "\n" . $e->getTraceAsString());

                // Ugh.  I hate to use instanceof, But this beats catching the exception separately since
                // we mostly want to treat it the same way as all other exceptions
                if ($e instanceof ORM_Validation_Exception) {
                    Kohana_Log::add("error", "Validation errors: " . print_r($e->validation->errors(), 1));
                }
            }

            if (file_exists($temp_filename)) {
                unlink($temp_filename);
            }

            batch::stop();
            url::redirect($continue_url ? $continue_url : item::root()->abs_url());
        } else {
            url::redirect(item::root()->abs_url());
        }

        // Override the application/json mime type.  The dialog based HTML uploader uses an iframe to
        // buffer the reply, and on some browsers (Firefox 3.6) it does not know what to do with the
        // JSON that it gets back so it puts up a dialog asking the user what to do with it.  So force
        // the encoding type back to HTML for the iframe.
        // See: http://jquery.malsup.com/form/#file-upload
        header("Content-Type: text/html; charset=" . Kohana::CHARSET);
    }

    private function _get_add_form($album) {
        $form = new Forge("uploader/add/{$album->id}", "", "post");
        $group = $form->group("add_photos");
        $group->hidden("continue_url")->value(Session::instance()->get("continue_url"));
        $group->input("title")->type("text");
        $group->input("description")->type("text");
        $group->upload("file");

        module::event("add_photos_form", $album, $form);

        return $form;
    }
}
