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
class Imobile_login_Controller extends Controller {
    const ALLOW_MAINTENANCE_MODE = true;
    const ALLOW_PRIVATE_GALLERY = true;

    public function auth_html() {
        access::verify_csrf();

        list ($valid, $form) = $this->_auth("login/auth_html");
        $continue_url = $form->continue_url->value;
        url::redirect($continue_url ? $continue_url : item::root()->abs_url());
    }

    private function _auth($url) {
        $form = auth::get_login_form($url);
        $valid = $form->validate();
        if ($valid) {
            $user = identity::lookup_user_by_name($form->login->inputs["name"]->value);
            if (empty($user) || !identity::is_correct_password($user, $form->login->password->value)) {
                $form->login->inputs["name"]->add_error("invalid_login", 1);
                $name = $form->login->inputs["name"]->value;
                log::warning("user", t("Failed login for %name", array("name" => $name)));
                module::event("user_auth_failed", $name);
                $valid = false;
            }
        }

        if ($valid) {
            auth::login($user);
        }

        // Either way, regenerate the session id to avoid session trapping
        Session::instance()->regenerate();

        return array($valid, $form);
    }
}