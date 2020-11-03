<?php


namespace App\Http\Services;

use App\Http\Contracts\CategoryInterface;
use App\Category;

class CategoryService implements CategoryInterface
{
    public function __construct(Category $category)
    {
        $this->category = $category;
    }
    public function index()
    {
        $categories = $this->category->whereNull('parent_id')
            ->with('subOptions')
            ->get();
        return $categories;

    }
    public function show($categoryID)
    {

        return $this->category->find($categoryID);
    }
    public function create($credentials)
    {
        return $this->category->create($credentials);
    }

    public function update($credentials,$id)
    {
        return $this->category->where('id', $id)->update($credentials);
    }
    public function delete($id)
    {
        return $this->category->where('id', $id)->delete();
    }

}
