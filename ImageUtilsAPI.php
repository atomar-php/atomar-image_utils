<?php
use atomic\core\Logger;

/**
* This is the internal api class that can be used by third party extensions
*/
class ImageUtilsAPI
{
  /**
   * Resizes an image down to the specified dimensions.
   * If the dimensions are bigger than the image size then no resizing will occure.
   * The aspect ratio will be maintained unless both width and height are specified and cropping is enabled.
   * @param string $path the path to the image. Important! Make sure you file has the correct extension.
   * @param int $width the maximum width to which the image will be resized
   * @param int $height the maximum height to which the image will be resized
   * @param array $options allows you to specify additional options. See the defaults array on this method for examples
   * @return string the path to the cached image.
   */
  public static function resize($path, $width=null, $height=null, $options) {
    $defaults = array(
      'crop'=>false,
      'crop_weight_top'=>true,
      'crop_weight_right'=>true,
      'jpg_quality'=>100,
      'crop_vertical_offset'=>0,
      'crop_horizontal_offset'=>0
    );
    $options = array_merge($defaults, $options);
    if (!file_exists($path)) {
        Logger::log_warning('ImageUtilsAPI:resize the file does not exist', $path);
      return false;
    }
    $ext = strtolower(end(explode('.', $path)));

    $src = self::loadImageResource($path);
    if (!$src) {
        Logger::log_warning('ImageUtilsAPI::resize the image source could not be loaded', $path);
      return false;
    }
    $w = imagesx($src);
    $h = imagesy($src);
    $new_w = $w;
    $new_h = $h;
    $aspect = $h/$w;

    // calculate dimensions
    if ($w>$width && $h>$height) {
      if ($width && $height) {
        if ($options['crop']) {
          $ratio = max($width/$w, $height/$h);
          $new_w = $w*$ratio;
          $new_h = $h*$ratio;
        } else {
          // w
          if ($w > $width) {
            $new_w = $width;
            $new_h = abs($new_w * $aspect);
          }
          // h
          if ($new_h > $height) {
            $new_h = $height;
            $new_w = abs($new_h / $aspect);
          }
        }
      } elseif ($width) {
        // resize by width
        if ($w > $width) {
          $new_w = $width;
          $new_h = abs($new_w * $aspect);
        }
      } else {
        // resize by height
        if ($h > $height) {
          $new_h = $height;
          $new_w = abs($new_h / $aspect);
        }
      }

      // resize
      if ($options['crop']) {
        $img = imagecreatetruecolor($width, $height);
        // apply crop weights
        $x = $options['crop_weight_right'] ? $options['crop_horizontal_offset'] : $options['crop_horizontal_offset'] - ($new_w - $width) / 2;
        $y = $options['crop_weight_top'] ? -$options['crop_vertical_offset'] : $options['crop_vertical_offset'] - ($new_h - $height);
      }  else {
        $img = imagecreatetruecolor($new_w, $new_h);
        $x = 0;
        $y = 0;
      }

      // preserve transparency
      if ($ext=='gif' || $ext=='png') {
        imagecolortransparent($img, imagecolorallocatealpha($img, 0, 0, 0, 127));
        imagealphablending($img, false);
        imagesavealpha($img, true);
      }
      imagecopyresampled($img, $src, $x, $y, 0, 0, $new_w, $new_h, $w, $h);
      switch (strtolower($ext)) {
        case 'png':
          imagepng($img, $path);
          break;
        case 'gif':
          imagegif($img, $path);
          break;
        case 'wbmp':
          imagewbmp($img, $path);
          break;
        case 'jpg':
        case 'jpeg';
        default:
          imagejpeg($img, $path, $options['jpg_quality']);
          break;
      }
      imagedestroy($img);
      imagedestroy($src);
    }
    return true;
  }

  /**
   * Loads the image resource using the appropriate file type methods
   * @param $path
   * @return bool|resource
   */
  public static function loadImageResource($path) {
    if (!file_exists($path)) return false;
    $ext = strtolower(end(explode('.', $path)));
    switch($ext) {
      case 'png':
        return imagecreatefrompng($path);
      case 'gif':
        return imagecreatefromgif($path);
      case 'wbmp':
        return imagecreatefromwbmp($path);
      case 'jpg':
      case 'jpeg':
        return imagecreatefromjpeg($path);
      default:
          Logger::log_warning('ImageUtilsAPI::loadImageResource unknown extension', $path);
        return imagecreatefromjpeg($path);
    }
  }
}