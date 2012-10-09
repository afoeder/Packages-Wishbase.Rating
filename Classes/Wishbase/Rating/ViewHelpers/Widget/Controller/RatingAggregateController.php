<?php
namespace Wishbase\Rating\ViewHelpers\Widget\Controller;

/*                                                                        *
 * This script belongs to the FLOW3 package "Wishbase.Rating".            *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * Rating controller
 */
class RatingAggregateController extends \TYPO3\Fluid\Core\Widget\AbstractWidgetController {
	/**
	 * @var \TYPO3\Flow\Persistence\PersistenceManagerInterface
	 * @Flow\Inject
	 */
	protected $persistenceManager;

	/**
	 * @var array
	 */
	protected $supportedFormats = array('json');

	/**
	 * @var array
	 */
	protected $viewFormatToObjectNameMap = array('json' => '\TYPO3\Flow\Mvc\View\JsonView');

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
		$intendedRatingClassName = $this->widgetConfiguration['ratingAggregate']->getRatingClassName();
		$this->arguments['rating']->setDataType($intendedRatingClassName);
	}

	/**
	 * @param \Wishbase\Rating\Domain\Model\RatingInterface $rating
	 * @return void
	 */
	public function rateAction(\Wishbase\Rating\Domain\Model\RatingInterface $rating) {
		$rateableObject = $this->widgetConfiguration['rateableObject'];

		$rateableObject->addRating($rating);
		$this->persistenceManager->update($rateableObject);

		$this->view->assign('value', array(
			'ratingClassName' => get_class($rating),
			'isNewObject?' => $this->persistenceManager->isNewObject($rateableObject),
			'rateableObjectClass' => get_class($rateableObject)
		));
	}
}
?>