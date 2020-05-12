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

    /**
     * @Route("/customer/create", name="create_customer")
     */
    public function create(\Symfony\Component\HttpFoundation\Request $request)
    {
        $customer = new Customer();
        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $customer = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($customer);
            $em->flush();

            return $this->redirectToRoute('customer');
        }

        return $this->render('customer/form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/customer/{id}", name="delete_customer")
     */
    public function delete(Customer $customer)
    {

        $id_customer = $customer->getId();

        $connectionParams = array(
            'url' => 'mysql://root:mercury@127.0.0.1:3306/PlanetShop',
        );
        $conn = DriverManager::getConnection($connectionParams);

        $sql = " DELETE FROM cart WHERE id_customer = '$id_customer';";
        $conn->query($sql);

        $sql = " DELETE FROM transaction WHERE id_customer = '$id_customer';";
        $conn->query($sql);

        $sql = " DELETE FROM space_object WHERE id_customer_owner = '$id_customer';";
        $conn->query($sql);

        $em = $this->getDoctrine()->getManager();
        $em->remove($customer);
        $em->flush();
        return $this->redirectToRoute('customer');
    }

    /**
     * @Route("customer/update/{customer}", name="update_customer")
     */
    public function update(\Symfony\Component\HttpFoundation\Request $request, Customer $customer)
    {


        $form = $this->createForm(CustomerType::class, $customer, [
            'action' => $this->generateUrl('update_customer', [
                'customer' => $customer->getId()]),
            'method' => 'POST']);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $customer = $form->getData();;

            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('customer');
        }

        return $this->render('customer/form.html.twig', [
            'form' => $form->createView()
        ]);

    }
}
