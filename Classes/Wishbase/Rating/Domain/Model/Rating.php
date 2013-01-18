<?php
namespace Wishbase\Rating\Domain\Model;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Wishbase.Rating".       *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * Concrete rating model; intends to handle integer ratings within boundaries from 1 to 5.
 * @Flow\ValueObject
 */
class Rating extends AbstractRating {

	/**
	 * The best resp. the worst possible rating for this implementation. Override in concrete class appropriately.
	 */
	const BEST_RATING = '5';
	const WORST_RATING = '1';

	/**
	 * The value (grade) of this rating
	 * @var integer
	 */
	protected $value;

	/**
	 * Constructor
	 *
	 * @param \TYPO3\Party\Domain\Model\AbstractParty $rater
	 * @param integer $value
	 * @throws \InvalidArgumentException
	 */
	public function __construct(\TYPO3\Party\Domain\Model\AbstractParty $rater, $value) {
		parent::__construct($rater);
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