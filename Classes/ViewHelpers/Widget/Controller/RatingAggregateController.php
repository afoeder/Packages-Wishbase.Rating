<?php
namespace Wishbase\Rating\ViewHelpers\Widget\Controller;

/*                                                                        *
 * This script belongs to the FLOW3 package "Wishbase.Rating".            *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        */

use TYPO3\FLOW3\Annotations as FLOW3;

/**
 * Rating controller
 */
class RatingAggregateController extends \TYPO3\Fluid\Core\Widget\AbstractWidgetController {
	/**
	 * @var array
	 */
	protected $supportedFormats = array('json');

	/**
	 * @var array
	 */
	protected $viewFormatToObjectNameMap = array('json' => '\TYPO3\FLOW3\MVC\View\JsonView');

	/**
	 * @return void
	 */
	public function indexAction() {
		$ratingAggregate = new \Wishbase\Rating\Domain\Model\RatingAggregate($this->widgetConfiguration['rateableObject']);
		$ratingAggregate->initializeObject();

		$this->view->assign('ratingAggregate', $ratingAggregate);
	}

}
?>