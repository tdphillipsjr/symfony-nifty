<?php

namespace NiftyThrifty\ShopBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * AddressRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AddressRepository extends EntityRepository
{
    /**
     * Return the addresses associated with this user other than the ones
     * recorded in their user row.
     */
    public function findOtherAddressesForUser($userId)
    {
        $dql = "SELECT a
                  FROM NiftyThrifty\ShopBundle\Entity\Address a
                  JOIN a.user u
                 WHERE a.userId = u.userId
                   AND a.userId = :userId
                   AND a.addressId != u.addressIdShipping 
                   AND a.addressId != u.addressIdBilling
                 ORDER BY a.addressId";
        $params = array('userId' => $userId);

        return $this->runQuery($dql, $params);
    }
}