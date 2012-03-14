<?php
namespace Wishbase\Rating;

/*                                                                        *
 * This script belongs to the FLOW3 package "Rating".                     *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        */

use TYPO3\FLOW3\Annotations as FLOW3;

/**
 * Interface for objects that are allowed to be rated
 */
interface RateableInterface {
	/**
	 * @param \Wishbase\Rating\Domain\Model\RatingInterface $rating
	 * @return void
	 */
	public function addRating(\Wishbase\Rating\Domain\Model\RatingInterface $rating);

	/**
	 * @param \Wishbase\Rating\Domain\Model\RatingInterface $rating
	 * @return void
	 */
	public function removeRating(\Wishbase\Rating\Domain\Model\RatingInterface $rating);

	/**
	 * @return \Traversable
	 */
	public function getRatings();
}

?>