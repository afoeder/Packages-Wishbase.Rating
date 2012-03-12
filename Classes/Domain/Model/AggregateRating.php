<?php
namespace Rating\Domain\Model;

/*                                                                        *
 * This script belongs to the FLOW3 package "Rating".                     *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        */

use TYPO3\FLOW3\Annotations as FLOW3;

/**
 * Aggregate rating model; not meant for persisting. Should be built on-fly from a given collection
 */
class AggregateRating {
	/**
	 * @var \TYPO3\FLOW3\Object\ObjectManagerInterface
	 */
	protected $objectManager;

	/**
	 * @var integer
	 */
	protected $ratingCount;

	/**
	 * @var float
	 */
	protected $ratingValue;

	/**
	 * @var mixed
	 */
	protected $bestRating;

	/**
	 * @var mixed
	 */
	protected $worstRating;

	/**
	 * @var \Doctrine\Common\Collections\Collection $ratingCollection
	 * @var string $ratingClass Class name of the responsible rating class
	 */
	public function __construct(\Doctrine\Common\Collections\Collection $ratingCollection, $ratingClass) {
		$ratingCount = 0;
		$ratingSum = 0;

		foreach ($ratingCollection AS $rating) {
			if (!$rating instanceof $ratingClass) {
				continue;
			} else {
				$ratingCount++;
				$ratingSum += $rating->getValue;
			}
		}

		$this->ratingCount = $ratingCount;
		$this->ratingValue = $ratingSum / $ratingCount;

		$targetObject = $this->objectManager->get($ratingClass);
		$this->bestRating = $targetObject->getBestRating();
		$this->worstRating = $targetObject->getWorstRating();
	}

	/**
	 * @return mixed
	 */
	public function getBestRating() {
		return $this->bestRating;
	}

	/**
	 * @return int
	 */
	public function getRatingCount() {
		return $this->ratingCount;
	}

	/**
	 * @return float
	 */
	public function getRatingValue() {
		return $this->ratingValue;
	}

	/**
	 * @return mixed
	 */
	public function getWorstRating() {
		return $this->worstRating;
	}
}

?>