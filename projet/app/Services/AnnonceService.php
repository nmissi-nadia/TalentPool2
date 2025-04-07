<?php

namespace App\Services;

use App\Interfaces\AnnonceRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class AnnonceService
{
    protected $repository;

    public function __construct(AnnonceRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getAll()
    {
        return $this->repository->index();
    }

    public function create(array $data)
    {
        $data['recruteur_id'] = Auth::id();
        return $this->repository->store($data);
    }

    public function update(int $id, array $data)
    {
        return $this->repository->update($data, $id);
    }

    public function get(int $id)
    {
        return $this->repository->show($id);
    }

    public function delete(int $id)
    {
        return $this->repository->destroy($id);
    }

    public function getStats()
    {
        return $this->repository->stats();
    }

    public function getByRecruteur(int $recruteurId)
    {
        return $this->repository->getByRecruteur($recruteurId);
    }

    public function getByStatut(string $statut)
    {
        return $this->repository->getByStatut($statut);
    }
}