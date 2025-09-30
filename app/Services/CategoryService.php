<?php
namespace App\Services;

use App\Repositories\CategoryRepository;

class CategoryService
{
    protected $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function getAllCategories()
    {
        return $this->categoryRepository->getAllCategories();
    }

    public function getAllCategoriesWithPaginate()
    {
        return $this->categoryRepository->getAllCategoriesWithPaginate();
    }

    public function search( $query)
    {
        return $this->categoryRepository->search($query);
    }

    public function lastPage()  {

        return $this->categoryRepository->lastPage();
    }

    public function createCategory(array $data)
    {
        return $this->categoryRepository->createCategory($data);
    }

    public function updateCategory($id, array $data)
    {
        return $this->categoryRepository->updateCategory($id, $data);
    }

    public function findCategoryById($id)
    {
        return $this->categoryRepository->findCategoryById($id);
    }

    public function deleteCategory($id)
    {
        return $this->categoryRepository->deleteCategory($id);
    }

    public function restoreCategory($id)
    {
        return $this->categoryRepository->restoreCategory($id);
    }
}
