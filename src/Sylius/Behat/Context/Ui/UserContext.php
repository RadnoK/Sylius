<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Behat\Context\Ui;

use Behat\Behat\Context\Context;
use Sylius\Behat\Page\User\LoginPage;
use Sylius\Behat\Page\Customer\CustomerShowPage;
use Sylius\Behat\Page\Customer\CustomersIndexPage;
use Sylius\Component\Core\Test\Services\SharedStorageInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

/**
 * @author Magdalena Banasiak <magdalena.banasiak@lakion.com>
 * @author Mateusz Zalewski <mateusz.zalewski@lakion.com>
 */
class UserContext implements Context
{
    /**
     * @var SharedStorageInterface
     */
    private $sharedStorage;

    /**
     * @var RepositoryInterface
     */
    private $customerRepository;

    /**
     * @var CustomersIndexPage
     */
    private $customersIndexPage;

    /**
     * @var CustomerShowPage
     */
    private $customerShowPage;

    /**
     * @var LoginPage
     */
    private $loginPage;

    /**
     * @param SharedStorageInterface $sharedStorage
     * @param RepositoryInterface $customerRepository
     * @param CustomersIndexPage $customersIndexPage
     * @param CustomerShowPage $customerShowPage
     * @param LoginPage $loginPage
     */
    public function __construct(
        SharedStorageInterface $sharedStorage,
        RepositoryInterface $customerRepository,
        CustomersIndexPage $customersIndexPage,
        CustomerShowPage $customerShowPage,
        LoginPage $loginPage
    ) {
        $this->sharedStorage = $sharedStorage;
        $this->customerRepository = $customerRepository;
        $this->customersIndexPage = $customersIndexPage;
        $this->customerShowPage = $customerShowPage;
        $this->loginPage = $loginPage;
    }

    /**
     * @Given /^I log in as "([^"]*)" with "([^"]*)" password$/
     */
    public function iLogInAs($login, $password)
    {
        $this->loginPage->open();
        $this->loginPage->logIn($login, $password);
    }

    /**
     * @When I delete the account of :email
     */
    public function iDeleteAccount($email)
    {
        $customersIndexPage = $this->customersIndexPage;
        $customersIndexPage->open();
        $customersIndexPage->deleteUser($email);
    }

    /**
     * @Then there should be no account with email :email
     */
    public function thereShouldBeNoAccount($email)
    {
        $customer = $this->customerRepository->findOneBy(array('email' =>$email));

        $this->customerShowPage->open(array('id' => $customer->getId()));

        $this->customerShowPage->isThereAccountOf($email);
    }
}
