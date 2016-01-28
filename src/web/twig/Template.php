<?php
/**
 * @link      http://craftcms.com/
 * @copyright Copyright (c) Pixel & Tonic, Inc.
 * @license   http://craftcms.com/license
 */

namespace craft\app\web\twig;

use Craft;
use craft\app\base\Element;
use craft\app\base\ElementInterface;
use yii\base\Object;

/**
 * Base Twig template class.
 *
 * @author Pixel & Tonic, Inc. <support@pixelandtonic.com>
 * @since  3.0
 */
abstract class Template extends \Twig_Template
{
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function display(array $context, array $blocks = array())
    {
        $name = $this->getTemplateName();
        Craft::beginProfile($name, __METHOD__);
        parent::display($context, $blocks);
        Craft::endProfile($name, __METHOD__);
    }

    // Protected Methods
    // =========================================================================

    /**
     * Returns the attribute value for a given array/object.
     *
     * @param mixed   $object            The object or array from where to get the item
     * @param mixed   $item              The item to get from the array or object
     * @param array   $arguments         An array of arguments to pass if the item is an object method
     * @param string  $type              The type of attribute (@see \Twig_Template constants)
     * @param boolean $isDefinedTest     Whether this is only a defined check
     * @param boolean $ignoreStrictCheck Whether to ignore the strict attribute check or not
     *
     * @throws \Twig_Error_Runtime If the attribute does not exist and Twig is running in strict mode and $isDefinedTest
     *                             is false
     * @return mixed               The attribute value, or a bool when $isDefinedTest is true, or null when the
     *                             attribute is not set and $ignoreStrictCheck is true
     */
    protected function getAttribute($object, $item, array $arguments = [], $type = \Twig_Template::ANY_CALL, $isDefinedTest = false, $ignoreStrictCheck = false)
    {
        if ($object instanceof ElementInterface) {
            $this->_includeElementInTemplateCaches($object);
        }

        if ($type !== \Twig_Template::METHOD_CALL && $object instanceof Object) {
            if ($object->canGetProperty($item)) {
                return $isDefinedTest ? true : $object->$item;
            }
        }

        return parent::getAttribute($object, $item, $arguments, $type, $isDefinedTest, $ignoreStrictCheck);
    }

    // Private Methods
    // =========================================================================

    /**
     * Includes this element in any active template caches.
     *
     * @param ElementInterface|Element $element
     *
     * @return void
     */
    private function _includeElementInTemplateCaches(ElementInterface $element)
    {
        $elementId = $element->id;

        if ($elementId) {
            // Don't initialize the TemplateCache service if we don't have to
            if (Craft::$app->has('templateCache', true)) {
                Craft::$app->getTemplateCache()->includeElementInTemplateCaches($elementId);
            }
        }
    }
}