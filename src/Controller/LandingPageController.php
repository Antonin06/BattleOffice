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
use App\Repository\ProductRepository;
use Container1xM9NcI\getProductTypeService;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Compiler\CheckTypeDeclarationsPass;
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
        $products  = $this->getDoctrine()
            ->getRepository(Product::class)
            ->findAll();

        // dd($products[0]->getId());


        $entityInstance=[
            'product' => new Product(),
            'client' => new Client(),
            'shipping' => new Shipping(),
            'order'=> new Order(),
        ];

        $entityInstance["order"]->setCreatedAt(new \DateTime());

        $formBuilder = $this->createFormBuilder($entityInstance, [
            'csrf_protection' => false,
            'csrf_field_name' => '_token',
            'csrf_token_id' => 'order_csrf'
        ])
            ->add('client',ClientType::class)
            ->add('product',ProductType::class)
            ->add('shipping',ShippingType::class)
            ->add('order' ,OrderType::class);

        $form=$formBuilder->getForm();
        $form->handleRequest($request);

        // dd($form);
        if ($form->isSubmitted() && $form->isValid()) {

            $productId = $request->get('order')["cart"]["cart_products"][0];



            // dd($clientId);
            $entityManager = $this->getDoctrine()->getManager();
            // $entityManager->persist($order>getProduct()->getId());
            $entityManager->persist($entityInstance['client']);
            $entityManager->persist($entityInstance['shipping']);

            $product = $entityManager->find(Product::class, $productId);
            $entityInstance["order"]->setProduct($product);
            $entityInstance["order"]->setClient($entityInstance['client']);
            $entityManager->persist($entityInstance['order']);

            $entityManager->flush();

            return $this->redirectToRoute('confirmation');

        }



        return $this->render('landing_page/index_new.html.twig', [
            'entityInstance'=>[
                'product' => new Product(),
                'client' => new Client(),
                'shipping' => new Shipping(),
                'order'=> new Order(),
                ],
            'products'=>$products,
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
