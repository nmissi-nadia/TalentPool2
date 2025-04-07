<?php

namespace App\Interfaces;

interface CandidatureRepositoryInterface
{
    public function index();
    public function store($data);
    public function update($data, $id);
    public function show($id);
    public function destroy($id);
    public function stats();
    public function getByAnnonce(int $annonceId);
    public function getByCandidat(int $candidatId);
    public function getByAnnonceAndCandidat(int $annonceId, int $candidatId);
}