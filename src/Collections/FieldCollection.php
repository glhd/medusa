<?php

namespace Galahad\Medusa\Collections;

use Galahad\Medusa\Contracts\Field;
use Illuminate\Support\Collection;
use InvalidArgumentException;

class FieldCollection extends Collection
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
			if (!$item instanceof Field) {
				throw new InvalidArgumentException(__CLASS__.' can only contain field instances.');
			}
		}
	}
}
