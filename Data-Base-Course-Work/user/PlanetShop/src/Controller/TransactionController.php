<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\SpaceObject;
use App\Entity\Transaction;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TransactionController extends AbstractController
{
    /**
     * @Route("/transaction", name="transaction")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        return $this->render('transaction/index.html.twig', [
            'controller_name' => 'TransactionController',
        ]);
    }

    /**
     * @Route("/add_transaction/{id}/{space_object}", name="add_transaction")
     */
    public function add($id, SpaceObject $space_object)
    {
        $em = $this->getDoctrine()->getManager();
        $customer = $em->getRepository(Customer::class)->find($id);
        $purse_customer = $customer->getPurse();
        $cost_space_object = $space_object->getCost();
        if($purse_customer >= $cost_space_object) {
            $transaction = new Transaction();
            $transaction->setIdCustomer($id);
            $id_space_object = $space_object->getId();
            $transaction->setIdSpaceObject($id_space_object);

            $connectionParams = array(
                'url' => 'mysql://root:mercury@127.0.0.1:3306/PlanetShop',
            );
            $conn = DriverManager::getConnection($connectionParams);


            $sql = " DELETE FROM cart WHERE id_space_object = '$id_space_object'";


            $space_object->setIdCustomerOwner($id);
            $customer->setPurse($purse_customer - $cost_space_object);

            $transaction->setPaidAt(new \DateTime('now'));

            $em->persist($transaction);
            try {
                $em->flush();
                $conn->query($sql);
            } catch (\PDOException $e) {
                echo 'Выброшено исключение: ', $e->getMessage(), "\n";
            }
        }

        return $this->redirectToRoute('cart', [
            'id' => $id
        ]);
    }
}
