<?php

namespace Galahad\Medusa\Collections;

use Galahad\Medusa\Contracts\ContentType;
use Illuminate\Support\Collection;
use InvalidArgumentException;

class ContentTypeCollection extends Collection
{
	/**
	 * FieldCollection constructor.
	 *
	 * @param array $items
	 * @throws InvalidArgumentException
	 */
	public function __construct($items = [])
	{
		parent::__construct($items);
		
		foreach ($this->items as $item) {
			if (!$item instanceof ContentType) {
				throw new InvalidArgumentException(__CLASS__.' can only contain content type instances.');
			}
		}
	}
}
