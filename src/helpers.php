<?php

use EFrane\Tinkr\Console\App;

// FIXME!!!!!!

if (!function_exists('tinkr'))
{
  function tinkr($name = '')
  {
    if (strlen($name) > 0)
    {
      return App::container()->make($name);
    }

    return App::container();
  }
}
