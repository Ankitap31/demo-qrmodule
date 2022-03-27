<?php

namespace Drupal\json_resource\Commands;

use Drush\Commands\DrushCommands;
use Symfony\Component\Filesystem\Filesystem;
use Drush\Drush;

/**
 * Base class for Json Resource commands for Drush 9.x.
 */
abstract class JsonResourceCommandsBase extends DrushCommands {

  /**
   * The json_resource CLI service.
   *
   * @var \Drupal\json_resource\Commands\JsonResourceCliServiceInterface
   */
  protected $cliService;

  /**
   * Constructs a JsonEditorCommandsBase object.
   *
   * @param \Drupal\json_resource\Commands\JsonResourceCliServiceInterface $cli_service
   *   The json_resource CLI service.
   */
  public function __construct(JsonResourceCliServiceInterface $cli_service) {
    $this->cliService = $cli_service;
    $this->cliService->setCommand($this);
  }

  /**
   *
   */
  public function drush_print($message) {
    $this->output()->writeln($message);
  }

  /**
   *
   */
  public function drush_mkdir($path) {
    $fs = new Filesystem();
    $fs->mkdir($path);
    return TRUE;
  }

  /**
   *
   */
  public function drush_set_error($error) {
    throw new \Exception($error);
  }

  /**
   *
   */
  public function drush_move_dir($src, $dest) {
    $fs = new Filesystem();
    $fs->rename($src, $dest, TRUE);
    return TRUE;
  }

  /**
   *
   */
  public function drush_download_file($url, $destination) {
    // Copied from: \Drush\Commands\SyncViaHttpCommands::downloadFile.
    static $use_wget;
    if ($use_wget === NULL) {
      $process = Drush::process('which wget');
      $use_wget = $process->run();
    }

    $destination_tmp = drush_tempnam('download_file');
    if ($use_wget) {
      $process = Drush::process(sprintf("wget -q --timeout=30 -O %s %s", $destination_tmp, $url));
      $process->run();
    }
    else {
      $process = Drush::process(sprintf("curl -s -L --connect-timeout 30 -o %s %s", $destination_tmp, $url));
      $process->run();
    }
    if (!drush_file_not_empty($destination_tmp) && $file = @file_get_contents($url)) {
      @file_put_contents($destination_tmp, $file);
    }
    if (!drush_file_not_empty($destination_tmp)) {
      // Download failed.
      throw new \Exception(dt("The URL !url could not be downloaded.", ['!url' => $url]));
    }
    if ($destination) {
      $fs = new Filesystem();
      $fs->rename($destination_tmp, $destination, TRUE);
      return $destination;
    }
    return $destination_tmp;
  }

  /**
   *
   */
  public function drush_tarball_extract($path, $destination = FALSE) {
    $this->drush_mkdir($destination);
    $cwd = getcwd();
    if (preg_match('/\.tgz$/', $path)) {
      drush_op('chdir', dirname($path));
      $process = Drush::process(['tar -xvzf', $path, '-C', $destination]);
      $process->run();
      $return = $process->isSuccessful();
      drush_op('chdir', $cwd);
      if (!$return) {
        throw new \Exception(dt('Unable to extract !filename.' . PHP_EOL . $process->getOutput(), ['!filename' => $path]));
      }
//      $return = drush_shell_cd_and_exec(dirname($path), "tar -xvzf %s -C %s", $path, $destination);
//      if (!$return) {
//        throw new \Exception(dt('Unable to extract !filename.' . PHP_EOL . implode(PHP_EOL, drush_shell_exec_output()), ['!filename' => $path]));
//      }
    }
    else {
      drush_op('chdir', dirname($path));
      $process = Drush::process(['unzip', $path, '-d', $destination]);
      $process->run();
      $return = $process->isSuccessful();
      drush_op('chdir', $cwd);
      if (!$return) {
        throw new \Exception(dt('Unable to extract !filename.' . PHP_EOL . $process->getOutput(), ['!filename' => $path]));
      }
      // $return = drush_shell_cd_and_exec(dirname($path), "unzip %s -d %s", $path, $destination);
      //      if (!$return) {
      //        throw new \Exception(dt('Unable to extract !filename.' . PHP_EOL . implode(PHP_EOL, drush_shell_exec_output()), ['!filename' => $path]));
      //      }
    }
    return $return;
  }

}
