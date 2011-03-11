<?php

/*
 * This file is part of the LiveTest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LiveTest\Listener\Cli;

use LiveTest\Listener\Base;

/**
 * This extension is used to let the application sleep after a test ran. This is used
 * to not DOS attack a server.
 *
 * @author Nils Langner
 */
class Debug extends Base
{
  private $debug = false;

  /**
   * @Event("LiveTest.Runner.InitCore")
   *
   * @param array $arguments
   */
  public function runnerInitCore(array $arguments)
  {
    if (array_key_exists('debug', $arguments))
    {
      ini_set('display_errors', 1);
      error_reporting(E_ALL);
      $this->debug = true;
    }
    return true;
  }

  /**
   * @Event("LiveTest.Runner.Error")
   *
   * @param \Exception $e
   */
  public function handleException(\Exception $exception)
  {
    if ($this->debug)
    {
      echo "  An error occured (debug modus):\n\n";
      echo "  Class  : " . get_class($exception) . "\n";
      echo "  Message: " . $exception->getMessage() . "\n";
      echo "  File   : " . $exception->getFile() . "\n";
      echo "  Line   : " . $exception->getLine() . "\n\n";
      $trace = str_replace('#', '           #', $exception->getTraceAsString());
      $trace = str_replace('           #0', '#0', $trace);
      echo "  Trace  : " . $trace;
      return false;
    }
    else
    {
      echo "  An error occured: " . $exception->getMessage() . " (" . get_class($exception) . ")";
    }
  }
}