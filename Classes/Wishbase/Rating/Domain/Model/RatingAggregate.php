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
 * Aggregate rating model; not meant for persisting. Should be built on-fly from a given collection
 */
class RatingAggregate {
	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Security\Context
	 */
	protected $securityContext;

	/**
	 * @var \TYPO3\Flow\Reflection\ReflectionService
	 * @Flow\Inject
	 */
	protected $reflectionService;

	/**
	 * @var \TYPO3\Flow\Object\ObjectManagerInterface
	 * @Flow\Inject
	 */
	protected $objectManager;

	/**
	 * @var \Wishbase\Rating\Domain\Model\RatingInterface
	 */
	protected $ratingInstance;

	/**
	 * @var \Wishbase\Rating\RateableInterface Holds the object passed in via the constructor
	 */
	protected $itemReviewed;

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
	 * @var mixed
	 */
	protected $ownRating;

	/**
	 * Note that initializeObject has to be called after instanciation in order to get the values filled.
	 * @var \Wishbase\Rating\RateableInterface $rateableObject
	 */
	public function __construct(\Wishbase\Rating\RateableInterface $rateableObject) {
		$this->itemReviewed = $rateableObject;
	}

	/**
	 * @return void
	 */
	public function initializeObject() {
		$this->ratingInstance = $this->getRatingInstance();

		$ratingCount = 0;
		$ratingSum = 0;
		$ownRating = NULL;

		foreach ($this->itemReviewed->getRatings() AS $rating) {
			if (!$rating instanceof $this->ratingInstance) {
				continue;
			}
			$ratingCount++;
			$ratingSum += $rating->getValue();
			#if ($rating->getRater() === $this->securityContext->getParty()) {
			#	$ownRating = $rating->getValue();
			#}
		}

		$this->ratingCount = $ratingCount;
		$this->ratingValue = ($ratingCount > 0 ? $ratingSum / $ratingCount : $ratingCount);

		$this->bestRating = $this->ratingInstance->getBestRating();
		$this->worstRating = $this->ratingInstance->getWorstRating();
		$this->ownRating = $ownRating;
	}

	/**
	 * Finds out what Rating instance is required for this Rating Collection, i.e. what type are the collection members of
	 * @return \Wishbase\Rating\Domain\Model\RatingInterface
	 * @throws \Exception
	 */
	protected function getRatingInstance() {
		$className = $this->reflectionService->getClassNameByObject($this->itemReviewed);
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
	 * @return \Wishbase\Rating\RateableInterface
	 */
	public function getItemReviewed() {
		return $this->itemReviewed;
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
	 * The user's own rating
	 * return mixed
	 */
	public function getOwnRating() {
		return $this->ownRating;
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

	/**
	 * Returns the class name of the responsible Rating instance
	 * @return string
	 */
	public function getRatingClassName() {
		return get_class($this->ratingInstance);
	}
}

?>