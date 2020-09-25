<?php
/**
 * Imager plugin for Craft CMS 3.x
 *
 * Image transforms gone wild
 *
 * @link      https://www.vaersaagod.no
 * @copyright Copyright (c) 2017 André Elvan
 */

namespace aelvan\imager\twigextensions;

use Craft;

use aelvan\imager\Imager as Plugin;

/**
 * Twig can be extended in many ways; you can add extra tags, filters, tests, operators,
 * global variables, and functions. You can even extend the parser itself with
 * node visitors.
 *
 * http://twig.sensiolabs.org/doc/advanced.html
 *
 * @author    André Elvan
 * @package   Imager
 * @since     2.0.0
 */
class ImagerTwigExtension extends \Twig_Extension
{
    // Public Methods
    // =========================================================================

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'Imager';
    }

    /**
     * Returns an array of Twig filters, used in Twig templates via:
     *
     *      {{ 'something' | someFilter }}
     *
     * @return array
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('srcset', [$this, 'srcsetFilter']),
            new \Twig_SimpleFilter('srcsetAttr', [$this, 'srcsetAttrFilter']),
            new \Twig_SimpleFilter('srcsetCustomAttr', [$this, 'srcsetCustomAttrFilter']),
            new \Twig_SimpleFilter('silhouette', [$this, 'silhouetteFilter']),
        ];
    }
    
    /**
     * Twig filter interface for srcset
     *
     * @param array $images
     * @param string $descriptor
     *
     * @return string
     */
    public function srcsetFilter($images, $descriptor='w')
    {
        return Plugin::$plugin->imager->srcset($images, $descriptor);
    }

    /**
     * Twig filter interface for srcsetAttr
     *
     * @param array $images
     * @param string $prefix
     * @param string $descriptor
     *
     * @return string
     */
    public function srcsetAttrFilter($images, $config = [])
    {
        return Plugin::$plugin->imager->srcsetAttr($images, $config);
    }

    /**
     * Twig filter interface for srcsetCustomAttr
     *
     * @param array $images
     * @param string $prefix
     * @param string $descriptor
     *
     * @return string
     */
    public function srcsetCustomAttrFilter($images, $descriptor = 'w', $tag = 'data-bgset,data-bg')
    {
        return Plugin::$plugin->imager->srcsetCustomAttr($images, $descriptor, $tag);
    }

    /**
     * Twig filter silhouette
     *
     * @param array $images
     * @param object $transforms
     *
     * @return string
     */
    public function silhouetteFilter($images, $transforms = ["width" => 50]){
      return Plugin::$plugin->imager->silhouette($images, $transforms);
    }
}
