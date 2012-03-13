# Rating system

This package provides a rating system for FLOW3. It should supply everything that is needed for that purpose,
and is inspired by the suggestions of http://schema.org/Rating.

## Entity abstract

![YUML representation](http://yuml.me/diagram/scruffy;/class/[RatingInterface|getBestRating();getWorstRating()]^-.-[AbstractRating|rater;value|getRater();setRater();getValue();setValue()], [AbstractRating]^-[Rating|getBestRating();getWorstRating()])

The Rating may be implemented to your needs; use one rating class for each variant of upper/lower ratings; for example
one for rating boundaries 1-5, and one for 0-10 or such.

## Usage

### Make your objects Rateable!

Basically, you might have various objects that need to be rateable. So, let them `implements \Rating\RateableInterface`.
This requires you to implement the appropriate methods there, usually the additional properties and methods would look
like

```php
	/**
	 * @var \Doctrine\Common\Collections\Collection<\Rating\Domain\Model\Rating>
	 * @ORM\ManyToMany
	 * @ORM\JoinTable(inverseJoinColumns={@ORM\JoinColumn(unique=true)})
	 */
	protected $ratings;

	/**
	 * @param \Rating\Domain\Model\RatingInterface $rating
	 */
	public function addRating(\Rating\Domain\Model\RatingInterface $rating) {
		$this->ratings->add($rating);
	}

	/**
	 * @param \Rating\Domain\Model\RatingInterface $rating
	 */
	public function removeRating(\Rating\Domain\Model\RatingInterface $rating) {
		$this->ratings->removeElement($rating);
	}

	/**
	 * @return \Doctrine\Common\Collections\Collection<\Rating\Domain\Model\Rating>
	 */
	public function getRatings() {
		return $this->ratings;
	}
}
```

Note the type hints, `\Rating\Domain\Model\RatingInterface`, and especially
`\Doctrine\Common\Collections\Collection<\Rating\Domain\Model\Rating>`. You might set your own Rating implementation,
the default one shipped with this package is intended to have a `worstRating` of 1 and a `bestRating` of 5, like the
[default assumptions](http://schema.org/Rating) are.

### Display the AggregateRating!

Remember you have an owner object which is the Rateable one, so pass this object to the ViewHelper provided:

```html
<r:widget.ratingAggregate rateableObject="{product}" />
```

This will result into the following markup (using the default template):

```html
<div itemtype="http://schema.org/AggregateRating" itemscope="itemscope" itemprop="aggregateRating">
	<meta content="3.4" itemprop="ratingValue">
	<meta content="319" itemprop="ratingCount">

	<span itemprop="worstRating" class="rating rating-rated">1</span>
	<span class="rating  rating-rated">2</span>
	<span class="rating  rating-rated">3</span>
	<span class="rating ">4</span>
	<span itemprop="bestRating" class="rating ">5</span>
</div>
```
