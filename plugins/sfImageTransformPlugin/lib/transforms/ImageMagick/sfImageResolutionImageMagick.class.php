<?php

class sfImageResolutionImageMagick extends sfImageTransformAbstract
{

  public function __construct()
  {
    
  }

  protected function transform(sfImage $image)
  {
    $resource = $image->getAdapter()->getHolder();
    
    $resource->setImageResolution(96, 96);

    $image->getAdapter()->setHolder($resource);

    return $image;
  }
}
