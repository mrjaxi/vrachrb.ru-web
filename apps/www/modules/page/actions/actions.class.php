<?php

class pageActions extends sfActions
{
  public function executeShow(sfWebRequest $request)
  {
    $this->page = Doctrine::getTable('Page')->findOneByFolderAndIsActivated($request->getParameter('folder'), 1);
    $this->forward404Unless($this->page);
  }
  public function executeHelp(sfWebRequest $request)
  {
    $this->pages = Doctrine::getTable('Page')->findOneByFolder('help');
  }
  public function executeAbout(sfWebRequest $request)
  {
    $this->pages = Doctrine::getTable('Page')->findOneByFolder('about');
  }
  public function executeDonate(sfWebRequest $request)
  {
    $this->pages = Doctrine::getTable('Page')->findOneByFolder('donate');
    $this->consultation_count = Doctrine_Query::create()
      ->select("q.*")
      ->from("Question q")
      ->where("q.approved = 1")
      ->count("*")
    ;
  }
  public function executeSponsor(sfWebRequest $request)
  {

  }
  public function executeSitemap(sfWebRequest $request)
  {
    $this->getResponse()->setHttpHeader('Content-type', 'text/xml');
    echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";

    $url_arr = array(
      '/',
      '/question-answer/',
      '/specialist/',
      '/tip/',
      '/categories/',
      '/article/',
      '/news/',
      '/partner/',
      '/documentation/',
      '/help/',
      '/donate/'
    );
    echo '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';
    foreach ($url_arr as $url)
    {
      echo '<url><loc>' . sfConfig::get('app_protocol') . '://' . $request->getHost() . $url . '</loc><lastmod>' . date('c') . '</lastmod><priority>1.0</priority></url>';
    }

    $specialists = Doctrine_Query::create()
      ->select("s.title_url")
      ->from("Specialist s")
      ->where("s.id != 51")
      ->fetchArray()
    ;
    foreach ($specialists as $specialist)
    {
      echo '<url><loc>' . sfConfig::get('app_protocol') . '://' . $request->getHost() . '/specialist/' . $specialist['title_url'] . '/</loc><lastmod>' . date('c') . '</lastmod><priority>0.5</priority></url>';
    }

    $prompts = Doctrine_Query::create()
      ->select("p.title_url")
      ->from("Prompt p")
      ->fetchArray()
    ;
    foreach ($prompts as $prompt)
    {
      echo '<url><loc>' . sfConfig::get('app_protocol') . '://' . $request->getHost() . '/tip/' . $prompt['title_url'] . '/</loc><lastmod>' . date('c') . '</lastmod><priority>0.5</priority></url>';
    }

    $articles = Doctrine_Query::create()
      ->select("a.title_url")
      ->from("Article a")
      ->fetchArray()
    ;
    foreach ($articles as $article)
    {
      echo '<url><loc>' . sfConfig::get('app_protocol') . '://' . $request->getHost() . '/article/' . $article['title_url'] . '/</loc><lastmod>' . date('c') . '</lastmod><priority>0.5</priority></url>';
    }

    $news = Doctrine_Query::create()
      ->select("n.title_url")
      ->from("News n")
      ->fetchArray()
    ;
    foreach ($news as $new)
    {
      echo '<url><loc>' . sfConfig::get('app_protocol') . '://' . $request->getHost() . '/news/' . $new['title_url'] . '/</loc><lastmod>' . date('c') . '</lastmod><priority>0.5</priority></url>';
    }
    echo '</urlset>';

    return sfView::NONE;
  }
  public function executeRobots(sfWebRequest $request)
  {
    $this->getResponse()->setHttpHeader('Content-type', 'text');
    $disallow_arr = array(
      '/arm/',
      '/*?',
      '/recover-password*',
      '/lp-auth/',
      '/signin*',
      '/user*',
      '/check_token/',
      '/signout/',
      '/login*',
      '/notice-update/',
      '/register/',
      '/change*',
      '/message-error-add/',
      '/agreement*',
      '/yam/',
      '/question-answer-filter/',
      '/sheet-history-update/',
      '/ask-question/specialist-filter/',
      '/specialist_filter/',
      '/tip/page*',
      '/specialty_filter/',
      /*'/categories/specialty/',*/
      '/categories_filter/',
      '/article/page*',
      /*'/news/page*',*/
      '/documentation/*',
      /*'/donate/sponsor/',*/
      '/photo/',
      '/personal-account*',
      '/account*',
      '/search*',
      '/doctor-account*',
      '/vk-callback/',
      '/uploader',
      '/question-answer/*',
      '/ask-question/*',
      '/comment*',
      '/mobile*',
      '/frame*'
    );
    echo 'User-agent: *' . "\n";
    foreach ($disallow_arr as $disallow)
    {
      echo 'Disallow: ' . $disallow . "\n";
    }
    echo 'Host: ' . sfConfig::get('app_protocol') . '://' . $request->getHost() . '/' . "\n";
    echo 'Sitemap: ' . sfConfig::get('app_protocol') . '://' . $request->getHost() . '/sitemap.xml';

    return sfView::NONE;
  }
}
