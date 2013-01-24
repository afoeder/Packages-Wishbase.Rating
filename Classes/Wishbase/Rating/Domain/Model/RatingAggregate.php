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
 * Aggregate rating model; not meant for persisting. Should be built on-fly from a given collection
 */
class RatingAggregate {

	/**
	 * Regex pattern for matching an occurrence like \Foo\Bar\Collection<\Bar\Foo\Item> to "Bar\Foo\Item"
	 */
	const PATTERN_MATCH_MEMBER = '/(?:\\\\?\w+)+<\\\\((?:\\\\?\w+)+)>/';

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
	 * The responsible rating instance class name, implements \Wishbase\Rating\Domain\Model\RatingInterface
	 * @var string
	 */
	protected $ratingImplementationClassName;

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
		$this->ratingImplementationClassName = $this->findRatingImplementationClassName();

		$ratingCount = 0;
		$ratingSum = 0;
		$ownRating = NULL;

		foreach ($this->itemReviewed->getRatings() AS $rating) {
			if (!is_a($rating, $this->ratingImplementationClassName)) {
				continue;
			}
			$ratingCount++;
			$ratingSum += $rating->getValue();
			if ($this->securityContext->canBeInitialized() && $rating->getRater() === $this->securityContext->getParty()) {
				$ownRating = $rating->getValue();
			}
		}

		$this->ratingCount = $ratingCount;
		$this->ratingValue = ($ratingCount > 0 ? $ratingSum / $ratingCount : $ratingCount);

		$this->bestRating = constant($this->ratingImplementationClassName . '::BEST_RATING');
		$this->worstRating = constant($this->ratingImplementationClassName . '::WORST_RATING');
		$this->ownRating = $ownRating;
	}

	/**
	 * Finds out what Rating instance is required for this Rating Collection, i.e. what type are the collection members of
	 * @return \Wishbase\Rating\Domain\Model\RatingInterface
	 * @throws \Exception
	 */
	protected function findRatingImplementationClassName() {
		$className = $this->reflectionService->getClassNameByObject($this->itemReviewed);
		$methodName = 'getRatings';
		$methodTagsValues = $this->reflectionService->getMethodTagsValues($className, $methodName);
		$returnType = current($methodTagsValues['return']);
		$matches = array();
		if (preg_match('' . self::PATTERN_MATCH_MEMBER . '', $returnType, $matches)) {
			$possibleObjectName = $matches[1];
			if (!$this->objectManager->isRegistered($possibleObjectName)) {
				throw new \Exception('The object ' . $possibleObjectName . ', which is considered a rating implementation responsible for ' . $className . ', is not known to the object manager.', 1358439607);
			} elseif (!$this->reflectionService->isClassImplementationOf($possibleObjectName, 'Wishbase\Rating\Domain\Model\RatingInterface')) {
				throw new \Exception('The object ' . $possibleObjectName . ', which is considered a rating implementation responsible for ' . $className . ', does not implement Wishbase\Rating\Domain\Model\RatingInterface which is mandatory.', 1358439689);
			} else {
				return $possibleObjectName;
			}
		} else {
			throw new \Exception('Annotated return type of "' . $className . '::' . $methodName . '", which is "' . $returnType . '", gives no information about collection member type');
		}
	}

	/**
	 * @return string
	 */
	public function getRatingImplementationClassName() {
		return $this->ratingImplementationClassName;
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
		return call_user_func(array($this->ratingImplementationClassName, 'getIterable'));
	}

}

?>