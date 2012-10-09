<?php
namespace Wishbase\Rating\Domain\Model;

/*                                                                        *
 * This script belongs to the FLOW3 package "Wishbase.Rating".            *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        */

use TYPO3\FLOW3\Annotations as FLOW3;

/**
 * Concrete rating model; intends to handle integer ratings within boundaries from 1 to 5.
 * @FLOW3\Entity
 */
class Rating extends AbstractRating {
	/**
	 * The value (grade) of this rating
	 * @var integer
	 */
	protected $value;

	/**
	 * The highest value allowed in this rating system
	 *
	 * @return float
	 */
	public function getBestRating() {
		return 5;
	}

	/**
	 * The lowest value allowed in this rating system
	 *
	 * @return float
	 */
	public function getWorstRating() {
		return 1;
	}

	/**
	 * @param integer $value
	 */
	public function setValue($value) {
		if ($value > $this->getBestRating() || $value < $this->getWorstRating()) {
			throw new \InvalidArgumentException('Given rating value "' . $value . '" must be between "' . $this->getWorstRating() . '" and "' . $this->getBestRating() . '".', 1331555838);
		}
		$this->value = $value;
	}

	/**
	 * @return integer
	 */
	public function getValue() {
		return $this->value;
	}
}

?>