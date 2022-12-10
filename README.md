# RockVideoThumbnailGrabber

Grab Thumbnail Picture from YouTube Video Url and save it to a ProcessWire image field.

This makes it possible to show a video preview image without connecting to Youtube so it is GDPR compliant without the need to request consent from the user.

## Usage

```php
// site/init.php
$grabber = $modules->get('RockVideoThumbnailGrabber');
$grabber->add("your-video-url-field", "your-thumbnail-image-field");
```

## See also

https://processwire.com/talk/topic/4614-youtube-and-vimeo-thumbnail-grabber/ --> this might be a better choice. I simply didn't know about that module!
