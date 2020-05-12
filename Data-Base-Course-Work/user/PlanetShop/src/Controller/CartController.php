<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Customer;
use App\Entity\SpaceObject;
use Doctrine\DBAL\DriverManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/cart/{id}", name="cart")
     */
    public function index($id)
    {
        $connectionParams = array(
            'url' => 'mysql://root:mercury@127.0.0.1:3306/PlanetShop',
        );
        $conn = DriverManager::getConnection($connectionParams);


        $sql = " SELECT *,  c.id AS id_cart  FROM cart c LEFT JOIN space_object so ON so.id = c.id_space_object WHERE id_customer = $id;";
        $space_objects = $conn->query($sql);

        $count = $space_objects->rowCount();


        $em = $this->getDoctrine()->getManager();
        $customer = $em->getRepository(Customer::class)->find($id);

        return $this->render('cart/index.html.twig', [
            'id' => $id,
            'customer' => $customer,
            'space_objects' => $space_objects,
            'count' => $count
        ]);

    }

    /**
     * @Route("/cart/{id}/{id_cart}", name="delete_cart")
     */
    public function delete($id, $id_cart)
    {

        $em = $this->getDoctrine()->getManager();
        $cart = $em->getRepository(Cart::class)->find($id_cart);
        if ($cart != NULL) {
            $em->remove($cart);
            $em->flush();
        }


        return $this->redirectToRoute('cart', [
            'id' => $id
        ]);
    }


}
