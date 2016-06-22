<?php

namespace Rrb\TwigUtilsBundle\Tests\Twig;

use Rrb\TwigUtilsBundle\Twig\RrbTwigUtilsExtension;
use Symfony\Bridge\Twig\Extension\AssetExtension;
use Symfony\Bridge\Twig\Tests\Extension\Fixtures\StubFilesystemLoader;
use Symfony\Component\Asset\Package;
use Symfony\Component\Asset\Packages;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class RrbTwigUtilsExtensionTest
 */
class RrbTwigUtilsExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var null|\Twig_Environment
     */
    private $twig = null;

    /**
     * @var null|RrbTwigUtilsExtension
     */
    private $extension = null;

    /**
     * Set twig environment and extension
     */
    public function setUp()
    {
        // We need packages in order to get the asset method of Twig by Symfony
        $fooPackage = new Package(new EmptyVersionStrategy());
        $packages = new Packages($fooPackage);

        $loader = new StubFilesystemLoader(array(
            __DIR__.'/../../Resources/views',
        ));

        // Create Twig Environment and add AssetExtension
        $this->twig = new \Twig_Environment($loader, array('strict_variables' => true, 'debug' => true));
        $this->twig->addExtension(new AssetExtension($packages));

        $this->extension = new RrbTwigUtilsExtension();
    }

    /**
     * Free variables
     */
    public function tearDown()
    {
        $this->twig = null;
        $this->extension = null;
    }

    /**
     * Test method lazyImageRender
     */
    public function testLazyImageRender()
    {
        // Testing without title nor alt
        $content = $this->extension->lazyImageRender($this->twig, 'imageUrl', 'imageBase64');

        $crawler = new Crawler($content);
        $div = $crawler->filter('div');

        $this->assertEquals(1, $div->count());
        $this->assertEquals('lazy-image-wrapper', $div->attr('class'));
        $this->assertEquals('background-image: url(\'imageBase64\');', $div->attr('style'));

        $img = $div->filter('img');
        $this->assertEquals(1, $img->count());
        $this->assertEquals('lazy-image', $img->attr('class'));
        $this->assertEquals('', $img->attr('title'));
        $this->assertEquals('', $img->attr('alt'));
        $this->assertEquals('imageUrl', $img->attr('src'));
        $this->assertEquals('this.style.opacity = \'1\'', $img->attr('onload'));


        // Testing with title and alt
        $content = $this->extension->lazyImageRender($this->twig, 'imageUrl', 'imageBase64', 'title', 'alt');

        $crawler = new Crawler($content);
        $div = $crawler->filter('div');

        $this->assertEquals(1, $div->count());
        $this->assertEquals('lazy-image-wrapper', $div->attr('class'));
        $this->assertEquals('background-image: url(\'imageBase64\');', $div->attr('style'));

        $img = $div->filter('img');
        $this->assertEquals(1, $img->count());
        $this->assertEquals('lazy-image', $img->attr('class'));
        $this->assertEquals('title', $img->attr('title'));
        $this->assertEquals('alt', $img->attr('alt'));
        $this->assertEquals('imageUrl', $img->attr('src'));
        $this->assertEquals('this.style.opacity = \'1\'', $img->attr('onload'));
    }

    /**
     * Test method lazyImageCss
     */
    public function testLazyImageCss()
    {
        $content = $this->extension->lazyImageCss($this->twig);

        $crawler = new Crawler($content);
        $link = $crawler->filter('link');

        $this->assertEquals(1, $link->count());
        $this->assertEquals('stylesheet', $link->attr('rel'));
        $this->assertEquals('text/css', $link->attr('type'));
        $this->assertEquals(true, strpos($link->attr('href'), 'lazy-image.css') !== false);
    }

    /**
     * Test common methods, yes, just to increase code coverage
     */
    public function testCommon()
    {
        $this->extension->getFunctions();
        $this->extension->getName();
    }
}
