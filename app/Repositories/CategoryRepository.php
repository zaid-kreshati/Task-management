<?php
namespace App\Repositories;

use App\Models\Categories;
use Illuminate\Support\Facades\Auth;

class CategoryRepository
{
    public function getAllCategories()
    {
            return Categories::all();
    }

    public function getAllCategoriesWithPaginate()
    {
            return Categories::paginate(5);
    }

    public function createCategory(array $data)
    {
        // Ensure the 'name' field is properly formatted as JSON
        if (isset($data['name_en']) && isset($data['name_ar'])) {
            $data['name'] = ([
                'en' => $data['name_en'],
                'ar' => $data['name_ar']
            ]);
        }

        // Remove the separate language fields from the data array if they exist
        unset($data['name_en'], $data['name_ar']);

        // Create the category
        $category = Categories::create($data);

        return $category;
    }


    public function search($query)
    {
        $locale = app()->getLocale(); // Get the current application locale

        // Use JSON_UNQUOTE and JSON_EXTRACT to search within the 'name' JSON field for the current locale
        $categories = Categories::whereRaw("JSON_UNQUOTE(JSON_EXTRACT(name, '$.$locale')) LIKE ?", ["%$query%"])->get();

        return $categories;
    }


    public function lastPage()  {
        // Get the total number of categories to determine the last page
        $totalCategories = Categories::count();
        $categoriesPerPage = 5; // Assuming you're paginating 10 categories per page
        $lastPage = ceil($totalCategories / $categoriesPerPage);
        return $lastPage;
    }

    public function updateCategory($id, array $data)
    {
        $category = Categories::findOrFail($id);
        $category->update($data);
        return $category;
    }

    public function findCategoryById($id)
    {
        return Categories::findOrFail($id);
    }

    public function deleteCategory($id)
    {
        $category = Categories::findOrFail($id);
        $category->delete();
    }

    public function restoreCategory($id)
    {
        $category = Categories::withTrashed()->findOrFail($id);
        $category->restore();
    }


}
