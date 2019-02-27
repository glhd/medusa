<?php

namespace Galahad\Medusa\Fields;

class DateRange extends Field
{
	/**
	 * @return array
	 */
	public function defaultInitialValue()
	{
		return [
			'start' => [
				'month' => 1,
				'day' => 1,
			],
			'end' => [
				'month' => 12,
				'day' => 31,
			],
		];
	}
	
	public function getMessages() : array
	{
		return [
			'between' => 'That is not a valid selection',
		];
	}
	
	protected function configureRules()
	{
		$this->addRules([
			'start.month' => ['integer', 'between:1,12'],
			'start.day' => ['integer', 'between:1,31'],
			'end.month' => ['integer', 'between:1,12'],
			'end.day' => ['integer', 'between:1,31'],
		]);
	}
}
