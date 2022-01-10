<?php

class sfImageProgressiveImageMagick extends sfImageTransformAbstract
{

  public function __construct()
  {
    
  }

  protected function transform(sfImage $image)
  {
    $resource = $image->getAdapter()->getHolder();

    $resource->setInterlaceScheme(Imagick::INTERLACE_PLANE);

    $image->getAdapter()->setHolder($resource);

    return $image;
  }
}
