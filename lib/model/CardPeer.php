<?php

class CardPeer extends BaseCardPeer
{
	static public function authenticate($card_number, $pincode)
	{
		$c = new Criteria();
		$c->add(CardPeer::CARD_NUMBER, $card_number, Criteria::EQUAL);
		$c->addAnd(CardPeer::PIN_CODE, $pincode, Criteria::EQUAL);
		$c->addAnd(CardPeer::IS_ACTIVE, true, Criteria::EQUAL);
		$c->addAnd(CardPeer::OWNER, null, Criteria::ISNOTNULL);

		$card = CardPeer::doSelectOne($c);

		if (!$card || !$card->isOwned())
		{
			return null;
		}

		return $card;
	}

	static public function doSelectCards($offset = 0, $limit = -1, $c = null)
	{
		$c = self::getCardCriteria($c);

		$c->setOffset($offset);

		if ($limit >= 0)
		{
			$c->setLimit($limit);
		}

		return self::doSelect($c);
	}

	static public function doSelectFreeCards($offset = 0, $limit = -1, $c = null)
	{
		$c = self::getFreeCardCriteria($c);

		return $this->doSelectCards($offset, $limit, $c);
	}

	static public function getSortAliases()
	{
		return array(
			'card_number' => self::CARD_NUMBER,
			'pin_code' => self::PIN_CODE,
		);
	}

	static public function getCardsCount($c = null)
	{
		$c = self::getCardCriteria($c);

		return self::doCount($c);
	}

	static public function cardNumberExists($card_number)
	{
		$c = new Criteria();
		$c->add(CardPeer::CARD_NUMBER, $card_number, Criteria::EQUAL);

		return (self::doCount($c) > 0);
	}

# Criterias

	static public function getSortByNumberAscCriteria($c = null)
	{
		if (is_null($c))
		{
			$c = new Criteria();
		}

		$c->addAscendingOrderByColumn(CardPeer::CARD_NUMBER);

		return $c;
	}

	static public function getSearchNumberPatternCriteria($numberPattern, $c = null)
	{
		if (is_null($c))
		{
			$c = new Criteria();
		}

		$cton1 = $c->getNewCriterion(CardPeer::CARD_NUMBER, '%'.$numberPattern.'%', Criteria::LIKE);

		$c->add($cton1);

		return $c;
	}

	static public function getCardCriteria($c = null)
	{
		if (is_null($c))
		{
			$c = new Criteria();
		}

		return $c;
	}

	static public function getOwnedCardCriteria($c = null)
	{
		if (is_null($c))
		{
			$c = new Criteria();
		}

		$c->add(CardPeer::OWNER, null, Criteria::ISNOTNULL);

		return $c;
	}

	static public function getFreeCardCriteria($c = null)
	{
		if (is_null($c))
		{
			$c = new Criteria();
		}

		$c->add(CardPeer::OWNER, null, Criteria::ISNULL);

		return $c;
	}
}
