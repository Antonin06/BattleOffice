<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Order;
use App\Entity\Product;
use App\Entity\Shipping;
use App\Form\ClientType;
use App\Form\OrderType;
use App\Form\ProductType;
use App\Form\ShippingType;
use App\Manager\OrderManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;




class LandingPageController extends AbstractController
{
  /**
  * @Route("/", name="landing_page")
  * @throws \Exception
  */
  public function index(Request $request)
  {
    $product = $this->getDoctrine()
    ->getRepository(Product::class)
    ->findAll();

    $entityInstance=[
      'product' => new Product(),
      'client' => new Client(),
      'shipping' => new Shipping(),
      'order'=>new Order(),
    ];

    $formBuilder = $this->createFormBuilder($entityInstance)

    ->add('client',ClientType::class)
    ->add('product',ProductType::class)
    ->add('shipping',ShippingType::class);

    $form=$formBuilder->getForm();
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      dd($request->request);
      $entityManager = $this->getDoctrine()->getManager();
      // $entityManager->persist($client>getParticipant());
      $entityManager->persist($entityInstance['client']);
      $entityManager->persist($entityInstance['shipping']);
      $entityManager->persist($entityInstance['order']);
      $entityManager->flush();

      return $this->redirectToRoute('landing_page');

    }


    return $this->render('landing_page/index_new.html.twig', [
      'entityInstance'=>[
        'product' => new Product(),
        'client' => new Client(),
        'shipping' => new Shipping(),
        'order'=> new Order(),
      ],
      'products'=>$product,
      'form' => $form->createView(),
    ]);
  }

  /**
  * @Route("/confirmation", name="confirmation")
  */
  public function confirmation()
  {
    return $this->render('landing_page/confirmation.html.twig', [

    ]);
  }
}
