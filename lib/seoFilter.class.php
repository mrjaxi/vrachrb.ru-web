<?php 
class seoFilter extends sfFilter
{
  public function execute($filterChain)
  {
    $context = $this->getContext();

    $request = $context->getRequest();
    $user = $context->getUser();

    $seo_array = false;

    $seo = Doctrine::getTable('Seo')->findOneByUrl($request->getPathInfo());

    $h = false;
    $t = false;
    $d = false;

    if($seo)
    {
      $h = $seo->getTitleH();
      $t = $seo->getTitleTag();
      $d = $seo->getDescriptionTag();
    }

    $user->setAttribute('seoH', $h);
    $user->setAttribute('seoT', $t);
    $user->setAttribute('seoD', $d);

    $filterChain->execute();
  }
}
?>