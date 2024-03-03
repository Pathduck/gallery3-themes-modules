<?php defined("SYSPATH") or die("No direct script access.");
/**
 * Gallery - a web based photo album viewer and editor
 * Copyright (C) 2000-2011 Bharat Mediratta
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

/**
 * This is the API for handling themes.
 *
 * Note: by design, this class does not do any permission checking.
 */
class theme extends theme_Core {
  public static $admin_theme_name;
  public static $site_theme_name;
  public static $is_admin;
  public static $dispatch;

  /**
   * Load the active theme.  This is called at bootstrap time.  We will only ever have one theme
   * active for any given request.
   */
  static function load_themes() {
    $input = Input::instance();
    $path = $input->server("PATH_INFO");
    if (empty($path)) {
      $path = "/" . $input->get("kohana_uri");
    }

    $config = Kohana_Config::instance();
    $modules = $config->get("core.modules");
    self::$is_admin = $path == "/admin" || !strncmp($path, "/admin/", 7);

    // Select different theme based on specific user agent
    self::$dispatch = false;
    $ua_list = module::get_var("themedispatcher", "useragents");
    if ($ua_list) {
      $ua_list = unserialize($ua_list);
      foreach( $ua_list as $key => $val) {
        $agent = request::user_agent("agent");
        if (($agent) && stristr($agent, $key)) {
          self::$dispatch = true;
        }
      }
    }
    $dispatchoverride = cookie::get("g3themeoverride", 0);

    if ((self::$dispatch) && !($dispatchoverride)) {
       self::$site_theme_name = module::get_var("themedispatcher", "theme");
    } else {
       self::$site_theme_name = module::get_var("gallery", "active_site_theme");
    } 

    if (self::$is_admin) {
      // Load the admin theme
      self::$admin_theme_name = module::get_var("gallery", "active_admin_theme");
      array_unshift($modules, THEMEPATH . self::$admin_theme_name);

      // If the site theme has an admin subdir, load that as a module so that
      // themes can provide their own code.
      if (file_exists(THEMEPATH . self::$site_theme_name . "/admin")) {
        array_unshift($modules, THEMEPATH . self::$site_theme_name . "/admin");
      }
      // Admins can override the site theme, temporarily.  This lets us preview themes.
      if (identity::active_user()->admin && $override = $input->get("theme")) {
        if (file_exists(THEMEPATH . $override)) {
          self::$admin_theme_name = $override;
          array_unshift($modules, THEMEPATH . self::$admin_theme_name);
        } else {
          Kohana_Log::add("error", "Missing override admin theme: '$override'");
        }
      }
    } else {
      // Admins can override the site theme, temporarily.  This lets us preview themes.
      if (identity::active_user()->admin && $override = $input->get("theme")) {
        if (file_exists(THEMEPATH . $override)) {
          self::$site_theme_name = $override;
        } else {
          Kohana_Log::add("error", "Missing override site theme: '$override'");
        }
      }
      array_unshift($modules, THEMEPATH . self::$site_theme_name);
    }

    $config->set("core.modules", $modules);
  }

}

