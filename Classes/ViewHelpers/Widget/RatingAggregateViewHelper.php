<?php
namespace Rating\ViewHelpers\Widget;

/*                                                                        *
 * This script belongs to the FLOW3 package "Rating".                     *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        */

use Doctrine\ORM\Mapping as ORM;
use TYPO3\FLOW3\Annotations as FLOW3;

/**
 * Ratings ViewHelper
 */
class RatingAggregateViewHelper extends \TYPO3\Fluid\Core\Widget\AbstractWidgetViewHelper {

	/**
	 * @var bool
	 */
	protected $ajaxWidget = TRUE;

	/**
	 * @FLOW3\Inject
	 * @var \Rating\ViewHelpers\Widget\Controller\RatingAggregateController
	 */
	protected $controller;

	/**
	 * @param \Rating\RateableInterface $rateableObject The target object which is rateable
	 * @return string
	 */
	public function render(\Rating\RateableInterface $rateableObject) {
		return $this->initiateSubRequest();
	}
}
?>