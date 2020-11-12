<?php


namespace App\Http\Services;

use App\Http\Contracts\PostInterface;
use App\Post;

class PostService implements PostInterface
{
    public function __construct(Post $post)
    {
        $this->post = $post;
    }
    public function index()
    {
        return $this->post->all();
    }
    public function show($id)
    {
        return  $this->post->where('id',$id)->first();
    }
    public function create($credentials)
    {
        return $this->post->create($credentials);
    }

    public function update($credentials,$id)
    {
        return $this->post->where('id', $id)->update($credentials);
    }

}
