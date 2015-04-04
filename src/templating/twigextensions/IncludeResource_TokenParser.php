<?php
/**
 * @link http://buildwithcraft.com/
 * @copyright Copyright (c) 2015 Pixel & Tonic, Inc.
 * @license http://buildwithcraft.com/license
 */

namespace craft\app\templating\twigextensions;

/**
 * Class IncludeResource_TokenParser
 *
 * @author Pixel & Tonic, Inc. <support@pixelandtonic.com>
 * @since 3.0
 */
class IncludeResource_TokenParser extends \Twig_TokenParser
{
	// Properties
	// =========================================================================

	/**
	 * @var string
	 */
	private $_tag;

	// Public Methods
	// =========================================================================

	/**
	 * Constructor
	 *
	 * @param string $tag
	 *
	 * @return IncludeResource_TokenParser
	 */
	public function __construct($tag)
	{
		$this->_tag = $tag;
	}

	/**
	 * Parses resource include tags.
	 *
	 * @param \Twig_Token $token
	 *
	 * @return IncludeResource_Node
	 */
	public function parse(\Twig_Token $token)
	{
		$lineno = $token->getLine();
		$stream = $this->parser->getStream();
		$nodes['path'] = $this->parser->getExpressionParser()->parseExpression();

		$first = $stream->test(\Twig_Token::NAME_TYPE, 'first');

		if ($first)
		{
			$stream->next();
		}

		$stream->expect(\Twig_Token::BLOCK_END_TYPE);

		$attributes = [
			'function' => $this->_tag,
			'first'    => $first,
		];

		return new IncludeResource_Node($nodes, $attributes, $lineno, $this->getTag());
	}

	/**
	 * Defines the tag name.
	 *
	 * @return string
	 */
	public function getTag()
	{
		return $this->_tag;
	}
}
