<?php


namespace App\Http\Contracts;


interface UserInterface{
 public function index();
 public function create($credentials);
 public function update($credentials,$id);
}
