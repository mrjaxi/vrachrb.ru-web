<?php

class sfImageBlurImageMagick extends sfImageTransformAbstract
{

  protected $radius;
  protected $sigma;

  public function __construct($radius, $sigma)
  {
    $this->setRadius($radius);
    $this->setSigma($sigma);
  }

  function setRadius($radius)
  {
    $this->radius = $radius;
  }
  
  function setSigma($sigma)
  {
    $this->sigma = $sigma;
  }
  
  function getRadius()
  {
    return $this->radius;
  }
  
  function getSigma()
  {
    return $this->sigma;
  }

  protected function transform(sfImage $image)
  {
    $resource = $image->getAdapter()->getHolder();

    $resource->blurImage($this->getRadius(), $this->getSigma());

    $image->getAdapter()->setHolder($resource);

    return $image;
  }
}
