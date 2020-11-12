<?php


namespace App\Http\Contracts;


interface PostInterface{
    public function index();
    public function show($id);
    public function create($credentials);
    public function update($credentials,$id);
}
