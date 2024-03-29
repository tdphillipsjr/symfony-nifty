<?php

namespace NiftyThrifty\ShopBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormError;
use NiftyThrifty\ShopBundle\Entity\State;

/**
 * This form is displayed from a user's basket.  It gives the user the ability to select
 * their shipping type.
 */
class OrderFormStep2Type extends AbstractType
{
    private $shippingManager;
    private $user;
    private $userId;

    /**
     * Add the shipping manager so we can manage the shipping choices and the user so we can get the
     * user's payment profiles.
     *
     * @param   ShopBundle:Service:ShippingCostService
     * @param   ShopBundle:Entity:User
     */
    public function __construct($user, $shippingManager)
    {
        $this->user             = $user;
        $this->shippingManager  = $shippingManager;
        $this->userId           = $user->getUserId();
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $userId = $this->userId;
        $builder->add('orderAmount',            'hidden')
                ->add('orderAmountCoupon',      'hidden')
                ->add('orderAmountVat',         'hidden')
                ->add('orderAmountShipping',    'hidden')
                ->add('orderAmountCredits',     'hidden')
                ->add('orderAmountTotal',       'hidden')

                // Products and shipping
                ->add('orderShippingMethod',
                      'choice',
                      array('choices' => $this->shippingManager->getShippingChoices(),
                            'expanded'=> true))
                ->add('couponCode', 'text', array('mapped'  => false, 
                                                  'required'=> false))
                ->add('userCredits','text', array('mapped'  => false, 
                                                  'required'=> false, 
                                                  'label'   => 'Use my credits'))

                // Hidden stuff passed from step 1
                ->add('orderShippingAddressFirstName',  'hidden')
                ->add('orderShippingAddressLastName',   'hidden')
                ->add('orderShippingAddressStreet',     'hidden')
                ->add('orderShippingAddressCity',       'hidden')
                ->add('orderShippingAddressState',      'hidden')
                ->add('orderShippingAddressZipcode',    'hidden')
                ->add('orderShippingAddressCountry',    'hidden')
                ->add('orderBillingAddressFirstName',   'hidden')
                ->add('orderBillingAddressLastName',    'hidden')
                ->add('orderBillingAddressStreet',      'hidden')
                ->add('orderBillingAddressCity',        'hidden')
                ->add('orderBillingAddressState',       'hidden')
                ->add('orderBillingAddressZipcode',     'hidden')
                ->add('orderBillingAddressCountry',     'hidden')

                /**
                 * The card stuff is required, but either a saved profile id OR all the
                 * card info is required.  Symfony can't support this in the form builder, so
                 * each of these are not required, but the controller will have to check that either
                 * savedCardProfileId is set OR all the other fields are set.  We might be able to
                 * hackify this in javascript at some point.
                 */
                ->add('savedCardProfileId',  'entity', array('class'        => 'NiftyThriftyShopBundle:UserPaymentProfile',
                                                             'label'        => false,
                                                             'mapped'       => false,
                                                             'query_builder'=> function(EntityRepository $er) use ($userId) {
                                                                                    return $er->createQueryBuilder('upp')
                                                                                              ->where('upp.userId = ?1')
                                                                                              ->setParameter(1, $userId);
                                                                               },
                                                             'empty_value'  => 'Select saved card...',
                                                             'required'     => false))
                ->add('cardName',           'text', array('mapped' => false, 'required' => false))
                ->add('cardNumber',         'text', array('mapped' => false, 'required' => false))
                ->add('expirationDateMonth', 'choice', array('empty_value'  => 'Month',
                                                             'choices'      => $this->getMonths(),
                                                             'mapped'       => false,
                                                             'label'        => false,
                                                             'required'     => false))
                ->add('expirationDateYear', 'choice', array('empty_value'   => 'Year',
                                                             'choices'      => $this->getYears(),
                                                             'mapped'       => false,
                                                             'label'        => false,
                                                             'required'     => false))
                ->add('securityCode',       'text', array('mapped' => false, 'required' => false))
                ->add('saveCard',           'choice',array('expanded' => true,
                                                           'multiple' => true,
                                                           'mapped'   => false,
                                                           'label'    => false,
                                                           'choices'  => array('yes' => 'Save this card to my account.')))
                ->add('Review Order',       'submit')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'NiftyThrifty\ShopBundle\Entity\Order'
        ));
    }

    /**
     * Get the years array for expiration dates.  Start with the current year and go out 10 years.
     *
     * return array
     */
    public function getYears()
    {
        $nowDate = new \DateTime();
        $thisYear = $nowDate->format('Y');
        $years = array();
        for ($i=0; $i<15; $i++) {
            $yearVal = $thisYear + $i;
            $years["$yearVal"] = $yearVal;
        }
        return $years;
    }

    public function getMonths()
    {
        $months = array();
        for ($i=1; $i<=12; $i++) {
            $months[$i] = $i;
        }
        return $months;
    }

    public function getName()
    {
        return 'orderForm';
    }
}
