<?php
namespace App\Utils\AbstractClasses;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

abstract class CategoryTreeAbstract
{
    public $categoriesArrayFromDb;
    public $categorylist;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    protected static $dbConnection;
    /**
     * @var UrlGeneratorInterface
     */


    public function __construct(EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator)
    {
        $this->entityManager = $entityManager;
        $this->urlGenerator = $urlGenerator;
        $this->categoriesArrayFromDb = $this->getCategories();
    }

    public function buildTree(int $parent_id = null): array
    {
        $subcategory = [];
        foreach ($this->categoriesArrayFromDb as $category) {

            if ($category['parent_id'] == $parent_id) {
                $children = $this->buildTree($category['id']);

                if ($children) {
                    $category['children'] = $children;
                }
                $subcategory[] = $category;
            }
        }
        return $subcategory;
    }

    abstract public function getCategoryList(array $categories_array);

    private function getCategories(): array
    {
        if (self::$dbConnection) {
            return self::$dbConnection;
        }
        else {
            $connection = $this->entityManager->getConnection();
            $sql = "SELECT * FROM categories";
            $statement = $connection->executeQuery($sql);
            return self::$dbConnection = $statement->fetchAllAssociative();
        }
    }
}