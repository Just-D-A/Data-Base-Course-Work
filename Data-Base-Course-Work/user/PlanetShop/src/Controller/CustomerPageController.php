<?php

namespace App\Controller;

use App\Entity\Customer;
use Doctrine\DBAL\DriverManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CustomerPageController extends AbstractController
{
    /**
     * @Route("/customer_page/{id}", name="customer_page")
     */
    public function index($id)
    {

        $connectionParams = array(
            'url' => 'mysql://root:mercury@127.0.0.1:3306/PlanetShop',
        );
        $conn = DriverManager::getConnection($connectionParams);


        $sql = " SELECT *,  c.id AS id_transaction  FROM transaction c LEFT JOIN space_object so ON so.id = c.id_space_object WHERE id_customer = '$id';";
        $space_objects = $conn->query($sql);

        $count = $space_objects->rowCount();
        
        $em = $this->getDoctrine()->getManager();
        $customer = $em->getRepository(Customer::class)->find($id);

        return $this->render('customer_page/index.html.twig', [
            'id' => $id,
            'customer' => $customer,
            'space_objects' => $space_objects,
            'count' => $count

        ]);
    }
}

