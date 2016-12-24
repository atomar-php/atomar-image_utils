<?php
/**
 * Implements hook_permission()
 */
function image_utils_permission() {
  return array(
    'administer_image_utils',
    'access_image_utils'
  );
}

/**
 * Implements hook_menu()
 */
function image_utils_menu() {
  return array();
}

/**
 * Implements hook_url()
 */
function image_utils_url() {
  return array();
}

/**
 * Implements hook_libraries()
 */
function image_utils_libraries() {
  return array(
    'ImageUtilsAPI.php'
  );
}

/**
 * Implements hook_cron()
 */
function image_utils_cron() {
  // execute actions to be performed on cron
}

/**
 * Implements hook_twig_function()
 */
function image_utils_twig_function() {
  // return an array of key value pairs.
  // key: twig_function_name
  // value: actual_function_name
  // You may use object functions as well
  // e.g. ObjectClass::actual_function_name  
  return array();
}

/**
 * Implements hook_preprocess_page()
 */
function image_utils_preprocess_page() {
  // execute actions just before the page is rendered.
}

/**
 * Implements hook_preprocess_boot()
 */
function image_utils_preprocess_boot() {
  // execute actions after the core has been loaded but before the extensions have been loaded.
}

/**
 * Implements hook_postprocess_boot()
 */
function image_utils_postprocess_boot() {
  // execute actions after core and extensions have been loaded.
}