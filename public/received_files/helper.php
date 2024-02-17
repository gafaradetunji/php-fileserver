<?php
/**
 * Create a function that returns the absolute path of the specified file or URL
 * @param string $file
 * @return string
 */

 function baseUrl($file = '')
 {
     return __DIR__ . '/' . $file;
 }
 