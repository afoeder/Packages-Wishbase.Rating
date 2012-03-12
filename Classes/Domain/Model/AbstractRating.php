<?php
namespace Rating\Domain\Model;

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
 * Abstract Rating model. Implement your rating mechanism according to your need and setting.
 * @FLOW3\Entity
 * @ORM\InheritanceType("JOINED")
 */
abstract class AbstractRating implements RatingInterface {
	/**
	 * @var \DateTime
	 */
	protected $creationDate;

	/**
	 * @var \TYPO3\Party\Domain\Model\AbstractParty
	 * @ORM\ManyToOne(cascade={"all"})
	 */
	protected $rater;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->creationDate = new \DateTime();
	}

	/**
	 * @param \TYPO3\Party\Domain\Model\AbstractParty $rater
	 */
	public function setRater($rater) {
		$this->rater = $rater;
	}

	/**
	 * @return \TYPO3\Party\Domain\Model\AbstractParty
	 */
	public function getRater() {
		return $this->rater;
	}
}

?>