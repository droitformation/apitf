<?php namespace App\Droit\Decision\Repo;

interface DecisionInterface {

    public function getAll();
    public function setConnection($connexion);
    public function countByYear();
    public function getWeekPublished(array $dates);
    public function getDates(array $dates);
    public function getDate($date);
    public function getMissingDates(array $dates);
    public function getExistDates(array $dates);
    public function getYear($year);
    public function search($params);
    public function searchTable($table,$conn,$params,$year);
    public function find($data);
    public function findByNumeroAndDate($numero,$date);

    public function byCategories($categorie_id);
    public function getest($categorie_id);

    /* Archives */
    public function findArchive($id,$year);
    public function updateArchive(array $data, $year);
    public function getDateArchive($date,$year);
    public function archiveCountByYear();
    public function searchArchives($params);

    public function create(array $data);
    public function update(array $data);
    public function delete($id);
    public function deleteDate($date);
}
