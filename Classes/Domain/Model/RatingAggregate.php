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
class RatingAggregate {
	/**
	 * @var \TYPO3\FLOW3\Reflection\ReflectionService
	 * @FLOW3\Inject
	 */
	protected $reflectionService;

	/**
	 * @var \TYPO3\FLOW3\Object\ObjectManagerInterface
	 * @FLOW3\Inject
	 */
	protected $objectManager;

	/**
	 * @var \Rating\RateableInterface Holds the object passed in via the constructor
	 */
	protected $rateableObject;

	/**
	 * @var \Rating\Domain\Model\RatingInterface
	 */
	protected $ratingInstance;

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
	 * Note that initializeObject has to be called after instanciation in order to get the values filled.
	 * @var \Rating\RateableInterface $rateableObject
	 */
	public function __construct(\Rating\RateableInterface $rateableObject) {
		$this->rateableObject = $rateableObject;
	}

	/**
	 * @return void
	 */
	public function initializeObject() {
		$this->ratingInstance = $this->getRatingInstance();

		$ratingCount = 0;
		$ratingSum = 0;

		foreach ($this->rateableObject->getRatings() AS $rating) {
			if (!$rating instanceof $this->ratingInstance) {
				continue;
			} else {
				$ratingCount++;
				$ratingSum += $rating->getValue;
			}
		}

		$this->ratingCount = $ratingCount;
		$this->ratingValue = ($ratingCount > 0 ? $ratingSum / $ratingCount : $ratingCount);

		$this->bestRating = $this->ratingInstance->getBestRating();
		$this->worstRating = $this->ratingInstance->getWorstRating();
	}

	/**
	 * Finds out what Rating instance is required for this Rating Collection, i.e. what type are the collection members of
	 * @return \Rating\Domain\Model\RatingInterface
	 * @throws \Exception
	 */
	protected function getRatingInstance() {
		$className = get_class($this->rateableObject);
		$methodName = 'getRatings';
		$methodTagsValues = $this->reflectionService->getMethodTagsValues($className, $methodName);
		$returnType = current($methodTagsValues['return']);
		$matches = array();
		if (preg_match('/(?:\\\\?\w+)+<((?:\\\\?\w+)+)>/', $returnType, $matches)) {
			return $this->objectManager->get($matches[1]);
		} else {
			throw new \Exception('Annotated return type of "' . $className . '::' . $methodName . '", which is "' . $returnType . '", gives no information about collection member type');
		}
	}

	/**
	 * @return mixed
	 */
	public function getBestRating() {
		return $this->bestRating;
	}

	/**
	 * @return mixed
	 */
	public function getWorstRating() {
		return $this->worstRating;
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
	 * @return array
	 */
	public function getIterable() {
		return $this->ratingInstance->getIterable();
	}
}

?>