<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Customer;
use App\Repository\UserRepository;
use App\Repository\CustomerRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserListController extends AbstractController
{
    /**
     *
     * @var UserRepository
     */
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository  = $userRepository;
    }

    /**
     * @Route(
     *     name="route_customers_users",
     *     path="/api/customers/{id}/users",
     *     defaults={"_api_resource_class"=User::class,
     *               "_api_item_operation_name"="read:Users:collection"
     *     }
     * )
     * @Method("GET")
     */
    public function findUsersByCustomer()
    {
        $customerConnecte = $this->getUser();
        $users = $this->userRepository->findBy([
            'customer' => $customerConnecte
        ]);
        return $users;
    }
    public function __invoke(Customer $customer)
    {
        return $this->findUsersByCustomer();
    }
}
