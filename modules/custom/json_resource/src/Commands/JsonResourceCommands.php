<?php

namespace Drupal\json_resource\Commands;

/**
 * Json Resource commands for Drush 9.x.
 */
class JsonResourceCommands extends JsonResourceCommandsBase {
  /****************************************************************************/
  // Drush json_resource:libraries:download. DO NOT EDIT.
  /****************************************************************************/

  /**
   * Download third party libraries required by the Json Resource module.
   *
   * @command json_resource:libraries:download
   * @usage json_resource:libraries:download
   *   Download third party libraries required by the Json Resource module.
   * @aliases jrdl
   */
  public function drush_json_resource_libraries_download() {
    $this->cliService->drush_json_resource_libraries_download();
  }

  /**
   * Generate Drush commands from json_resource.drush.inc for Drush 8.x
   * to JsonResourceCommands for Drush 9.x.
   *
   * @command json_resource:generate:commands
   * @usage drush json_resource:generate:commands
   *   Generate Drush commands from json_resource.drush.inc for Drush 8.x to JsonResourceCommands for Drush 9.x.
   * @aliases jrgc
   */
  public function drush_json_resource_generate_commands() {
    $this->cliService->drush_json_resource_generate_commands();
  }

}
