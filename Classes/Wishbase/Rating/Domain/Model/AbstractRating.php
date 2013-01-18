<?php
namespace Wishbase\Rating\Domain\Model;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Wishbase.Rating".       *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        */

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

/**
 * Abstract Rating model. Implement your rating mechanism according to your need and setting.
 * @Flow\ValueObject
 * @ORM\InheritanceType("JOINED")
 */
abstract class AbstractRating implements RatingInterface {

	/**
	 * The best resp. the worst possible rating for this implementation. Override in concrete class appropriately.
	 */
	const BEST_RATING = '10';
	const WORST_RATING = '1';

	/**
	 * @var \DateTime
	 */
	protected $creationDate;

	/**
	 * @var \TYPO3\Party\Domain\Model\AbstractParty
	 * @ORM\ManyToOne(cascade={"all"})
	 */
	protected $rater;

	/**
	 * Constructor
	 * @param \TYPO3\Party\Domain\Model\AbstractParty $rater
	 */
	public function __construct(\TYPO3\Party\Domain\Model\AbstractParty $rater) {
		$this->creationDate = new \DateTime();
		$this->rater = $rater;
	}

	/**
	 * @return \TYPO3\Party\Domain\Model\AbstractParty
	 */
	public function getRater() {
		return $this->rater;
	}

	/**
	 * The highest value allowed in this rating system
	 *
	 * @return mixed
	 */
	public function getBestRating() {
		return self::BEST_RATING;
	}

	/**
	 * The lowest value allowed in this rating system
	 *
	 * @return mixed
	 */
	public function getWorstRating() {
		return self::WORST_RATING;
	}

	/**
	 * Returns an array representing directions for amount and values of rating "stars"
	 *
	 * @return array
	 */
	public static function getIterable() {
		$iterable = array();
		for($i = static::WORST_RATING; $i <= static::BEST_RATING; $i++) {
			$iterable[] = $i;
		}
		return $iterable;
	}

}

?>