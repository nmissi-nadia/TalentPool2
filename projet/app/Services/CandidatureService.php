<?php

namespace App\Services;

use App\Interfaces\CandidatureRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class CandidatureService
{
    protected $repository;

    public function __construct(CandidatureRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getAll()
    {
        return $this->repository->index();
    }

    public function create(array $data)
    {
        $data['candidat_id'] = Auth::id();
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

    public function getByAnnonce(int $annonceId)
    {
        return $this->repository->getByAnnonce($annonceId);
    }

    public function getByCandidat(int $candidatId)
    {
        return $this->repository->getByCandidat($candidatId);
    }
}