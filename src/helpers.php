<?php

use EFrane\Tinkr\Console\App;

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

if (!function_exists('tinkr_version'))
{
  function tinkr_version()
  {
    return App::tinkrVersion();
  }
}
