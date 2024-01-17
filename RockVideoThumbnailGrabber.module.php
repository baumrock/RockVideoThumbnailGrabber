<?php

namespace ProcessWire;

/**
 * @author Bernhard Baumrock, 29.11.2022
 * @license Licensed under MIT
 * @link https://www.baumrock.com
 */
class RockVideoThumbnailGrabber extends WireData implements Module, ConfigurableModule
{
  private $relations = [];

  public static function getModuleInfo()
  {
    return [
      'title' => 'RockVideoThumbnailGrabber',
      'version' => '1.0.1',
      'summary' => 'Grab video thumbnail from YouTube and save it to PW image field',
      'autoload' => true,
      'singular' => true,
      'icon' => 'youtube',
    ];
  }

  public function init()
  {
    $this->wire->addHookAfter("Pages::saveReady", $this, "grabThumb");
  }

  public function add($urlfield, $thumbfield)
  {
    $this->relations[] = [(string)$urlfield, (string)$thumbfield];
  }

  public function grabThumb(HookEvent $event)
  {
    $page = $event->arguments(0);
    foreach ($this->relations as $relation) {
      $url = $page->getUnformatted($relation[0]);
      if (!$url) continue;
      $img = $page->getUnformatted($relation[1]);
      if (!$img) continue;
      if ($img->count()) continue;

      // get url of thumbnail
      parse_str(parse_url($url, PHP_URL_QUERY), $vars);
      if (array_key_exists('v', $vars)) {
        // youtube url
        $id = $vars['v'];
        foreach ($this->imageNames() as $name) {
          $img->add("https://img.youtube.com/vi/$id/$name.jpg");
        }
      }
    }
  }

  private function imageNames(): array
  {
    return array_map('trim', explode(",", $this->youtubeNames));
  }

  /**
   * Config inputfields
   * @param InputfieldWrapper $inputfields
   */
  public function getModuleConfigInputfields($inputfields)
  {
    $inputfields->add([
      'type' => 'text',
      'name' => 'youtubeNames',
      'label' => 'Youtube-Images to grab',
      'value' => $this->youtubeNames ?: "maxresdefault, sddefault, hqdefault, mqdefault, default, 0, 1, 2, 3",
    ]);
    return $inputfields;
  }
}
