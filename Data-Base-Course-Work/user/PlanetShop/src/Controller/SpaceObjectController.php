<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Customer;
use App\Entity\SpaceObject;
use App\Form\SpaceObjectType;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SpaceObjectController extends AbstractController
{
    /**
     * @Route("/space_object/{id}", name="space_object")
     */
    public function index($id)
    {

        $connectionParams = array(
            'url' => 'mysql://root:mercury@127.0.0.1:3306/PlanetShop',
        );
        $conn = DriverManager::getConnection($connectionParams);

        $sql = " SELECT * FROM space_object WHERE id_customer_owner is NULL;";
        $space_objectes = $conn->query($sql);

        $count = $space_objectes->rowCount();

        $em = $this->getDoctrine()->getManager();
        $customer = $em->getRepository(Customer::class)->find($id);

        return $this->render('space_object/index.html.twig', [
            'customer' => $customer,
            'space_objectes' => $space_objectes,
            'id' => $id,
            'count' => $count
        ]);

    }

    /**
     * @Route("/add_space_object/{id}/{space_object}", name="add_space_object")
     */
    public function add($id, SpaceObject $space_object)
    {
        $cart = new Cart;
        $cart->setIdCustomer($id);
        $cart->setIdSpaceObject($space_object->getId());

        $em = $this->getDoctrine()->getManager();
        $em->persist($cart);
        try {
            $em->flush();
        } catch ( UniqueConstraintViolationException $e) {
            echo 'Выброшено исключение: ',  $e->getMessage(), "\n";
        }

        return $this->redirectToRoute('space_object', [
            'id' => $id
        ]);
    }
}
