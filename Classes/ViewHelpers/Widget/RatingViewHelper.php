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
class RatingViewHelper extends \TYPO3\Fluid\Core\Widget\AbstractWidgetViewHelper {

	/**
	 * @var bool
	 */
	protected $ajaxWidget = TRUE;

	/**
	 * @FLOW3\Inject
	 * @var \Rating\ViewHelpers\Widget\Controller\RatingController
	 */
	protected $controller;

	/**
	 * @param object $object The target object to rate
	 * @return string
	 */
	public function render($object) {
		return $this->initiateSubRequest();
	}
}
?>