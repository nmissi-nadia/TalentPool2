<?php

namespace App\Interfaces;

interface AnnonceRepositoryInterface
{
    public function index();
    public function store($data);
    public function update($data, $id);
    public function show($id);
    public function destroy($id);
    public function stats();
    public function getByRecruteur(int $recruteurId);
    public function getByStatut(string $statut);
}