<?php

namespace App\Controller;

use App\Entity\Category;
use App\Utils\CategoryTreeAdminList;
use App\Utils\CategoryTreeAdminOptionList;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="app_admin")
     */
    public function index(): Response
    {
        return $this->render('admin/my_profile.html.twig');
    }

    /**
     * @Route("/categories", name="app_admin_categories")
     */
    public function categories(CategoryTreeAdminList $categories): Response
    {
        $categories->getCategoryList($categories->buildTree());
        dump($categories);
        return $this->render('admin/categories.html.twig',[
            'categories' => $categories->categorylist
        ]);
    }

    /**
     * @Route("/edit-category/{id}", name="app_admin_edit_category")
     */
    public function editCategory(Category $category): Response
    {
        return $this->render('admin/edit_category.html.twig',[
            'category' => $category
        ]);
    }

    /**
     * @Route("/delete-category/{id}", name="app_admin_delete_category")
     */
    public function deleteCategory(Category $category): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($category);
        $entityManager->flush();
        return $this->redirectToRoute('app_admin_categories');
    }

    /**
     * @Route("/upload-video", name="app_admin_upload_video")
     */
    public function upload(): Response
    {
        return $this->render('admin/upload_video.html.twig');
    }

    /**
     * @Route("/users", name="app_admin_users")
     */
    public function users(): Response
    {
        return $this->render('admin/users.html.twig');
    }

    /**
     * @Route("/videos", name="app_admin_videos")
     */
    public function videos(): Response
    {
        return $this->render('admin/videos.html.twig');
    }

    public function getAllCategories(CategoryTreeAdminOptionList $categories, $editedCategory = null)
    {
        $categories->getCategoryList($categories->buildTree());
        return $this->render('admin/_all_categories.html.twig',[
            'categories' => $categories,
            'editedCategory' => $editedCategory
        ]);
    }
}
