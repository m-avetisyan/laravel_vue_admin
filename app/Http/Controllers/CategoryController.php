<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Resources\FailedResource;
use App\Http\Resources\CategoryResource;
use App\Http\Contracts\CategoryInterface;
use App\Http\Requests\CategoryRequest;


class CategoryController extends Controller
{

    protected $categoryService;
    public function __construct(CategoryInterface $categoryService)
    {
        $this->categoryService = $categoryService;
    }
    /**
     * Get categories with parent_id is Null.
     *
     * @return CategoryResource
     */
    public function index()
    {
        $categories = $this->categoryService->index();
        return new CategoryResource((object)['data' => $categories,'message' =>'Successfully fetched']);
    }
    /**
     * Get All categories
     *
     * @return CategoryResource
     */
    public function all()
    {
        $categories = Category::with('subOptions')->get();
        return new CategoryResource((object)['data' => $categories,'message' =>'Successfully fetched']);
    }

    /**
     * GET /api/category/{id}
     * Get single account with id
     *
     * @param $id
     * @return FailedResource|CategoryResource
     */
    public function show($id)
    {
        $category = $this->categoryService->show($id);
        if (!$category) {
            return new FailedResource((object)['error' => 'Sorry, account with id ' . $id . ' cannot be found']);
        }
        return new CategoryResource((object)['data' => $category,'message' =>'Successfully fetched']);
    }
    /**
     * POST /api/category/new
     * Create Category
     *
     * @param CategoryRequest $request
     * @return FailedResource|CategoryResource
     */
    public function store(CategoryRequest $request)
    {
        $credentials = [
            'name' => $request->name,
            'parent_id' => $request->parent_id,
        ];
        $category = $this->categoryService->create($credentials);

        if ($category) {
            $categories = $this->categoryService->index();
            return new CategoryResource((object)['data' => $categories,'message' =>'Successfully added']);
        }
        else {
            return new FailedResource((object)['error' => 'Category can not be added']);
        }
    }
    /**
     * PUT /api/category/{id}
     * Update account
     *
     * @param CategoryRequest $request
     * @param $id
     * @return FailedResource|CategoryResource
     */
    public function update(CategoryRequest $request, $id)
    {
        $credentials = [
            'name' => $request->name,
            'parent_id' => $request->parent_id
        ];
        $category =$this->categoryService->update($credentials, $id);
        if ($category) {
            $categories = $this->categoryService->index();
            return new CategoryResource((object)['message' => 'Category  updated','data' => $categories]);
        }
        else {
            return new FailedResource((object)['error' => 'Category can not be updated']);
        }
    }
    /**
     * DELETE /api/category/{id}
     * Delete account
     *
     * @param   $id
     * @return FailedResource|CategoryResource
     */
    public function destroy($id)
    {
        $category =$this->categoryService->show($id);
        if(!$category) {
            return new FailedResource((object)['error' => 'Sorry, account with id ' . $id . ' cannot be found']);
        }
        if ($this->categoryService->delete($id)) {
            $categories =  $this->categoryService->index();
            return new CategoryResource((object)['message' => 'Category  deleted','data' => $categories]);
        }
        else {
            return new FailedResource((object)['error' => 'Category can not be deleted']);
        }
    }
}
