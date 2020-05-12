<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\CartList;
use App\Entity\Customer;
use App\Form\CustomerType;
use Doctrine\DBAL\DriverManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CustomerController extends AbstractController
{
    private $currentCustomer;
    /**
     * @Route("/customer", name="customer")
     */
    public function index(  )
    {
        $connectionParams = array(
            'url' => 'mysql://root:mercury@127.0.0.1:3306/PlanetShop',
        );
        $conn = DriverManager::getConnection($connectionParams);


        $sql = "SELECT * FROM customer;";
        $customers = $conn->query($sql);

        $count = $customers->rowCount();

        return $this->render('customer/index.html.twig', [
            'customers' => $customers,
            'count' => $count
        ]);

    }

}
