<?php
use atomic\core\Logger;

/**
 * Implements hook_uninstall()
 */
function image_utils_uninstall() {
  // destroy tables and variables
  return true;
}

/**
 * Implements hook_update_version()
 */
function image_utils_update_1() {
  // prepare sql
  $sql = <<<SQL
-- TODO: impliment db installation
SQL;

  // perform installation
  R::begin();
  try {
    R::exec($sql);
    R::commit();
    return true;
  } catch (Exception $e) {
    R::rollback();
      Logger::log_error('Installation of Image Utils failed', $e->getMessage());
    return false;
  }
}