<?php

namespace App\Http\Controllers;


use App\Http\Resources\FailedResource;
use App\Http\Resources\SuccessResource;
use App\Http\Resources\PostResource;
use App\Http\Services\PostService;
use App\Http\Requests\PostRequest;


class PostController extends Controller
{


//    /**
//     * Get Authenticated user posts.
//     *
//     * @param postService $postService
//     * @return postResource
//     */
    public function index(PostService $postService)
    {
        $posts = $postService->index();

        return new PostResource((object)['data' => $posts,'message' =>'Successfully fetched']);
    }
//    /**
//     * GET /api/post/{id}
//     * Get single post with id
//     *
//     * @param postService $postService
//     * @param $id
//     * @return FailedResource|postResource
//     */
    public function show(PostService $postService,$id)
    {
        $post = $postService->show($id);
        if (!$post) {
            return new FailedResource((object)['error' => 'Sorry, post with id ' . $id . ' cannot be found']);
        }
        return new PostResource((object)['data' => $post,'message' =>'Successfully fetched']);
    }

    public function store(PostService $postService,PostRequest $request)
    {

        $credentials = [
            'name' => $request->name,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'image' => $request->file('image')->getClientOriginalName(),
        ];
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = $image->getClientOriginalName();
            $uploadPath = public_path('uploads/posts');
            $image->move($uploadPath, $name);
        }

        $post = $postService->create($credentials);
        if ($post) {
            $posts = $postService->index();
            return new PostResource((object)['data' => $posts,'message' => 'Added Successfully']);
        }
        else {
            return new FailedResource((object)['error' => 'Post can not be added']);
        }
    }
//    /**
//     * PUT /api/post/{id}
//     * Update post
//     *
//     * @param postService $postService
//     * @param postRequest $request
//     * @param $id
//     * @return FailedResource|postResource
//     */
    public function update(PostService $postService,PostRequest $request,$id)
    {
        $credentials = [
            'name' => $request->name,
            'description' => $request->description,
            'category' => $request->category,
        ];
        $post = $postService->update($credentials, $id);
        if ($post) {
            $posts = $postService->index();
            return new PostResource((object)['message' => 'post  updated','data' => $posts]);
        }
        else {
            return new FailedResource((object)['error' => 'post can not be updated']);
        }
    }
//    /**
//     * DELETE /api/post/{id}
//     * Delete post
//     *
//     * @param postService $postService
//     * @param   $id
//     * @return FailedResource|postResource
//     */
    public function destroy(PostService $postService,$id)
    {
        $post = $postService->show($id);
        if(!$post) {
            return new FailedResource((object)['error' => 'Sorry, post with id ' . $id . ' cannot be found']);
        }
        if ($post->delete()) {
            $posts =  $postService->index();
            return new PostResource((object)['message' => 'post  deleted','data' => $posts]);
        }
        else {
            return new FailedResource((object)['error' => 'post can not be deleted']);
        }
    }
}
