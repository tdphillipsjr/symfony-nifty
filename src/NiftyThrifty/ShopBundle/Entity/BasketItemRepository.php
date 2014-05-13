<?php

namespace NiftyThrifty\ShopBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

/**
 * BasketItemRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BasketItemRepository extends EntityRepository
{
    /**
     * Get the count of items in a basket.
     */
    public function getItemCountByBasket($basket, $em)
    {
        $basket->expireItems($em);

        $dql = "SELECT COUNT(b.basketItemId)
                  FROM NiftyThrifty\ShopBundle\Entity\BasketItem b
                 WHERE b.basketId = :basketId
                   AND b.basketItemStatus = :status";

        $params = array('basketId' => $basket->getBasketId(),
                        'status'   => \NiftyThrifty\ShopBundle\Entity\BasketItem::VALID);

        return $this->returnScalarResult($dql, $params);
    }

    /**
     * Same as above, but get the actual items.
     */
    public function findByBasket($basket, $em)
    {
        $basket->expireItems($em);

        $dql = "SELECT bi
                  FROM NiftyThrifty\ShopBundle\Entity\BasketItem bi
                 WHERE bi.basketId = :basketId
                   AND bi.basketItemStatus = :status
                  ORDER BY bi.basketItemDateEnd ASC";

        $params = array('basketId' => $basket->getBasketId(),
                        'status'   => \NiftyThrifty\ShopBundle\Entity\BasketItem::VALID);

        return $this->runQuery($dql, $params);
    }

    /**
     * The basket item when having the basketItemId and productId.
     * Used to verify a product is in a basket.
     */
    public function findByBasketAndProduct($em, $basket, $productId)
    {
        $basket->expireItems($em);

        $dql = 'SELECT bi
                  FROM NiftyThrifty\ShopBundle\Entity\BasketItem bi
                 WHERE bi.productId = :productId
                   AND bi.basketId  = :basketId
                   AND bi.basketItemStatus = :status';
        $params = array('productId' => $productId,
                        'basketId'  => $basket->getBasketId(),
                        'status'    => \NiftyThrifty\ShopBundle\Entity\BasketItem::VALID);

        try {
            $basketItem = $this->runSingleResultQuery($dql, $params);
        } catch (NoResultException $e) {
            $basketItem = null;
        }

        return $basketItem;
    }









}
