<?php
namespace Wishbase\Rating\Domain\Model;

/*                                                                        *
 * This script belongs to the FLOW3 package "Wishbase.Rating".            *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * An interface for ratings
 */
interface RatingInterface {

	/**
	 * The highest value allowed in this rating system
	 * @return mixed
	 */
	public function getBestRating();

	/**
	 * The lowest value allowed in this rating system
	 * @return mixed
	 */
	public function getWorstRating();

	/**
	 * @param mixed $value
	 */
	public function setValue($value);

	/**
	 * @return mixed
	 */
	public function getValue();

	/**
	 * @param \TYPO3\Party\Domain\Model\AbstractParty $rater
	 */
	public function setRater($rater);

	/**
	 * @return \TYPO3\Party\Domain\Model\AbstractParty
	 */
	public function getRater();

	/**
	 * Returns an array representing directions for amount and values of rating "stars"
	 * @return array
	 */
	public function getIterable();
}

?>