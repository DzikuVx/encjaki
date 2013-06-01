<?php

interface iCache {

  function set($module, $property, $value);

  function get($module, $property);

  function check($module, $property);

}

?>