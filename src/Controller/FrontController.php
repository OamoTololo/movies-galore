<?php

namespace App\Controller;

use App\Entity\Category;
use App\Utils\CategoryTreeFrontPage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{
    /**
     * @Route("/", name="app_front")
     */
    public function index(): Response
    {
        return $this->render('front/index.html.twig');
    }

    /**
     * @Route("/video-list/category/{categoryname},{id}", name="app_video_list")
     */
    public function videoList($id, CategoryTreeFrontPage $categories): Response
    {
        return $this->render('front/video_list.html.twig');
    }

    /**
     * @Route("/video-details", name="app_video_details")
     */
    public function videoDetails(): Response
    {
        return $this->render('front/video_details.html.twig');
    }

    /**
     * @Route("/search-results", methods={"POST"}, name="app_search_results")
     */
    public function searchResults(): Response
    {
        return $this->render('front/search_results.html.twig');
    }

    /**
     * @Route("/pricing", name="app_pricing")
     */
    public function pricing(): Response
    {
        return $this->render('front/pricing.html.twig');
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(): Response
    {
        return $this->render('front/register.html.twig');
    }

    /**
     * @Route("/login", name="app_login")
     */
    public function login(): Response
    {
        return $this->render('front/login.html.twig');
    }

    /**
     * @Route("/payment", name="app_payment")
     */
    public function payment(): Response
    {
        return $this->render('front/payment.html.twig');
    }

    /**
     * @Route("/main-categories", name="app_main_categories")
     */
    public function mainCategories() : Response
    {
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findBy(['parent'=> null], ['name' => 'ASC']);
        return $this->render('front/_main_categories.html.twig', [
            'categories' => $categories
        ]);
    }

}
