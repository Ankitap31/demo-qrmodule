<?php

namespace Drupal\json_resource\Commands;

use Drush\Commands\DrushCommands;

/**
 * Defines an interface for Drush version agnostic commands.
 */
interface JsonResourceCliServiceInterface {

  /**
   * Set the Drush 9.x command.
   *
   * @param \Drush\Commands\DrushCommands $command
   *   A Drush 9.x command.
   */
  public function setCommand(DrushCommands $command);

  /**
   * Implements hook_drush_command().
   */
  public function json_resource_drush_command();

  /**
   * Implements drush_hook_COMMAND().
   */
  public function drush_json_resource_libraries_download();

  /**
   * Implements drush_hook_COMMAND().
   */
  public function drush_json_resource_generate_commands();

}
