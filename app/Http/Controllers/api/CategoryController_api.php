<?php
namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Services\CategoryService;
use App\Traits\JsonResponseTrait;
use App\Http\Requests\Category\storeCategoryRequest;
use App\Http\Requests\Category\updateCategoryRequest;
use Illuminate\Database\QueryException; // Import for database exception handling
use Illuminate\Validation\ValidationException; // Import for validation exception handling


class CategoryController_api extends Controller_api
{
    use JsonResponseTrait;  // Use the JsonResponseTrait

    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function search(Request $request)
    {
        try {
            $query = $request->get('name');
            $categories = $this->categoryService->search($query);

            return $this->successResponse($categories, 'Categories retrieved successfully.');
        } catch (QueryException $e) {
            return $this->errorResponse('Database error occurred.', 500);
        } catch (\Exception $e) {
            return $this->errorResponse('An error occurred while searching for categories.', 500);
        }
    }

    public function store(storeCategoryRequest $request)
    {
        try {
            $category = $this->categoryService->createCategory($request->all());

            return $this->successResponse($category, 'Category created successfully!');
        } catch (ValidationException $e) {
            return $this->errorResponse('Validation failed.', 422, $e->errors());
        } catch (QueryException $e) {
            // Handle unique constraint violations, e.g. duplicate category name
            if ($e->getCode() === '23000') {
                return $this->errorResponse('Category already exists.', 409);
            }
            return $this->errorResponse('Database error occurred.', 500);
        } catch (\Exception $e) {
            return $this->errorResponse('An error occurred while creating the category.', 500);
        }
    }

    public function index()
    {
        try {
            $categories = $this->categoryService->getAllCategoriesWithPaginate();
            return $this->successResponse($categories, 'Categories fetched successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to fetch categories.', 500);
        }
    }

    public function destroy($id)
    {
            try {
                $this->categoryService->deleteCategory($id);
                return $this->successResponse(null, 'Category deleted successfully.');
            } catch (\Exception $e) {
                return $this->errorResponse('Failed to delete category.', 500);
            }
    }

    public function restore($id)
    {
            try {
                $this->categoryService->restoreCategory($id);
                return $this->successResponse(null, 'Category restored successfully.');
            } catch (\Exception $e) {
                return $this->errorResponse('Failed to restore category.', 500);
            }

    }

    public function update(updateCategoryRequest $request, $id)
    {
            try {
                $this->categoryService->updateCategory($id, $request->only(['name', 'color']));
                return $this->successResponse(null, 'Category updated successfully.');
            } catch (ValidationException $e) {
                return $this->errorResponse('Validation failed.', 422, $e->errors());
            } catch (QueryException $e) {
                if ($e->getCode() === '23000') {
                    return $this->errorResponse('Category already exists.', 409);
                }
                return $this->errorResponse('Database error occurred.', 500);
            } catch (\Exception $e) {
                return $this->errorResponse('An error occurred while updating the category.', 500);
            }

    }
}
