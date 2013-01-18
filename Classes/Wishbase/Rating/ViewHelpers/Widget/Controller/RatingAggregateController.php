<?php
namespace Wishbase\Rating\ViewHelpers\Widget\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Wishbase.Rating".       *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use Wishbase\Rating\Domain\Model\RatingInterface,
	Wishbase\Rating\RateableInterface;

/**
 * Rating controller
 */
class RatingAggregateController extends \TYPO3\Fluid\Core\Widget\AbstractWidgetController {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Persistence\PersistenceManagerInterface
	 */
	protected $persistenceManager;

	/**
	 * @Flow\Inject
	 * @var \Doctrine\Common\Persistence\ObjectManager
	 */
	protected $entityManager;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Security\Context
	 */
	protected $securityContext;

	/**
	 * @var array
	 */
	protected $supportedMediaTypes = array('text/html', 'application/json');

	/**
	 * @return void
	 */
	public function indexAction() {
		$this->view->assign('ratingAggregate', $this->widgetConfiguration['ratingAggregate']);
	}

	/**
	 * @return void
	 */
	public function initializeRateAction() {
		$intendedRatingClassName = $this->widgetConfiguration['ratingAggregate']->getRatingImplementationClassName();
		$this->arguments->getArgument('rating')
			->setDataType($intendedRatingClassName)
			->getPropertyMappingConfiguration()
				->setTypeConverterOption('TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter', \TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter::CONFIGURATION_CREATION_ALLOWED, TRUE)
				->allowProperties('value', 'rater');

		$rawRequestRatingArgument = $this->request->getArgument('rating');
		$rawRequestRatingArgument['rater'] = $this->securityContext->getParty();
		$this->request->setArgument('rating', $rawRequestRatingArgument);
	}

	/**
	 * @param \Wishbase\Rating\Domain\Model\RatingInterface $rating
	 * @return string JSON
	 */
	public function rateAction(\Wishbase\Rating\Domain\Model\RatingInterface $rating) {
		/** @var $rateableObject \Wishbase\Rating\RateableInterface */
		$rateableObject = $this->widgetConfiguration['rateableObject'];
		$rateableObjectClassName = get_class($rateableObject);
		$identifier = $this->persistenceManager->getIdentifierByObject($rateableObject);
		$reHydratedObject = $this->persistenceManager->getObjectByIdentifier($identifier, $rateableObjectClassName);

		$this->removeRatingsAlreadyDoneByAuthenticatedParty($reHydratedObject);

		$reHydratedObject->addRating($rating);
		$this->persistenceManager->update($reHydratedObject);

		return json_encode(array('rated' => $rating->getValue()));
	}

	/**
	 * Removes all ratings from the currently logged in user
	 * @param \Wishbase\Rating\RateableInterface $ratedObject
	 * @return void
	 */
	protected function removeRatingsAlreadyDoneByAuthenticatedParty(RateableInterface $ratedObject) {
		$securityContext = $this->securityContext;
		$ratingsByParty = $ratedObject->getRatings()->filter(function(RatingInterface $rating) use ($securityContext) {
			return $rating->getRater() === $securityContext->getParty();
		});
		foreach ($ratingsByParty AS $presentRating) {
			$ratedObject->removeRating($presentRating);
		}
	}
}
?>