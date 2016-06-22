<?php
namespace Rrb\TwigUtilsBundle\Twig;

/**
 * Class RrbTwigUtilsExtension
 * @package Rrb\TwigUtilsBundle\Twig
 */
class RrbTwigUtilsExtension extends \Twig_Extension
{
    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction(
                'lazy_image_render',
                [$this, 'lazyImageRender'],
                [
                    'is_safe' => ['html'],
                    'needs_environment' => true,
                ]
            ),
            new \Twig_SimpleFunction(
                'lazy_image_css',
                [$this, 'lazyImageCss'],
                [
                    'is_safe' => ['html'],
                    'needs_environment' => true,
                ]
            ),
        ];
    }

    /**
     * @param \Twig_Environment $twig
     * @param string            $imageUrl
     * @param string            $imageBase64
     * @param string            $title
     * @param string            $alt
     * @return string
     */
    public function lazyImageRender(\Twig_Environment $twig, $imageUrl, $imageBase64, $title = '', $alt = '')
    {
        return $twig->render('RrbTwigUtilsBundle::lazy-image-render.html.twig', [
            'imageUrl' => $imageUrl,
            'imageBase64' => $imageBase64,
            'title' => $title,
            'alt' => $alt,
        ]);
    }

    /**
     * @param \Twig_Environment $twig
     * @return string
     */
    public function lazyImageCss(\Twig_Environment $twig)
    {
        return $twig->render('RrbTwigUtilsBundle::lazy-image-css.html.twig');
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'rrb_twigutils_extension';
    }
}
