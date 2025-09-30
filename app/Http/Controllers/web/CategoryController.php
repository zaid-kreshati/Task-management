<?php
namespace App\Http\Controllers\web;

use Illuminate\Http\Request;
use App\Services\CategoryService;
use App\Http\Requests\Category\storeCategoryRequest;
use App\Http\Requests\Category\updateCategoryRequest;

class CategoryController extends Controller
{

    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

public function search(Request $request)
{
    $query = $request->get('search');

    $categories=$this->categoryService->search($query);
    return response()->json($categories);
}

    public function store(storeCategoryRequest $request)
    {

        $category=$this->categoryService->createCategory($request->all());
        $lastPage=$this->categoryService->lastPage();

         // Return JSON response for AJAX
         return response()->json([
            'success' => true,
            'message' => 'category created successfully!',
            'category' => $category,
            'lastPage' => $lastPage,

        ]);
    }



    public function index(Request $request)
    {
        $categories = $this->categoryService->getAllCategoriesWithPaginate();
        if ($request->ajax()) {
            return response()->json([
                'categories' => view('partials.categoryIndex', ['categories' => $categories])->render(),
                'pagination' => (string) $categories->links('pagination::bootstrap-5')
            ]);
        }

        return view('categoryIndex', compact('categories'));

    }

















    public function destroy($id)
    {

        $this->categoryService->deleteCategory($id);
        return response()->json(['success' => true, 'message' => 'Category deleted successfully.']);
    }

    public function restore($id)
    {
        $this->categoryService->restoreCategory($id);
        return redirect()->back();
    }

    public function update(updateCategoryRequest $request, $id)
    {

        $this->categoryService->updateCategory($id, $request->only(['name', 'color']));

        return response()->json(['success' => true, 'message' => 'Category updated successfully.']);
    }
}
