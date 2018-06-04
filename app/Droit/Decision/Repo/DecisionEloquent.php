<?php namespace  App\Droit\Decision\Repo;

use App\Droit\Decision\Repo\DecisionInterface;
use App\Droit\Decision\Entities\Decision as M;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DecisionEloquent implements DecisionInterface{

    protected $decision;
    protected $main_connection = 'mysql';

    public function __construct(M $decision)
    {
        $this->decision = $decision;

        if (\App::environment('testing')) {
            $this->main_connection = 'testing';
        }

    }

    public function setConnection($connexion)
    {
        $this->decision->setConnection($connexion);

        return $this;
    }

    public function getAll()
    {
        $name = 'decisions';
        $cast = 'Year(publication_at) as year';

        return $this->decision
            ->select($name.'.id',$name.'.numero',$name.'.categorie_id',$name.'.remarque',$name.'.publication_at',$name.'.decision_at',$name.'.langue',$name.'.publish')
            ->selectRaw($cast)
            ->orderBy('publication_at','DESCp')
            ->take(20)->get();
    }

    public function getest($categorie_id)
    {
        $name = 'decisions';
        $cast = 'Year(publication_at) as year';

        return $this->decision
            ->select($name.'.id',$name.'.numero',$name.'.categorie_id',$name.'.remarque',$name.'.publication_at',$name.'.decision_at',$name.'.langue',$name.'.publish')
            ->selectRaw($cast)
            ->where($name.'.categorie_id', '=' ,$categorie_id)
            ->orderBy('publication_at')
            ->get();
    }

    public function prepareCount($collection){

        return $collection->groupBy(function($date) {
            return $date->publication_at->format('Y');
        },'publication_at')->map(function ($year, $key) {
            return $year->groupBy(function($pub) {
                return $pub->publication_at->format('Y-m-d');
            })->map(function ($date, $key) {
                return ['date' => $key, 'count' => $date->count()];
            })->groupBy(function($item, $key) {
                $month = explode('-',$key);
                return $month[1];
            });
        });
    }

    public function countByYear(){

        $collection =  $this->decision->select('id','publication_at')->orderBy('publication_at')->get();

        return $this->prepareCount($collection);
    }

    public function archiveCountByYear()
    {
        $results = collect([]);
        $year    = date('Y') - 1;
        $tables  = range('2012',$year);

        foreach ($tables as $table) {
            if (Schema::connection('sqlite')->hasTable('archive_'.$table)) {
                $result  = $this->decision->setTable('archive_'.$table)->select('id','publication_at')->orderBy('publication_at')->get();
                $results = $results->merge($result);
            }
        }

        return $this->prepareCount($results);
    }

    public function getYear($year){

        return $this->decision->whereYear('publication_at', $year)->get();
    }

    public function getDate($date){

        return $this->decision->where('publication_at', '=', $date)->get();
    }

    public function getWeekPublished(array $dates){

        return $this->decision->whereIn('publication_at', $dates)->published(true)->get();
    }

    public function getDates(array $dates)
    {
        return $this->decision->select('publication_at')->whereIn('publication_at', $dates)->groupBy('publication_at')->get();
    }

    public function getMissingDates(array $dates)
    {
        $exist = $this->decision->select('publication_at')->whereIn('publication_at', $dates)->get();

        return collect($dates)->diff($exist->pluck('publication_at'))->unique()->map(function ($item, $key) {
            return \Carbon\Carbon::parse($item);
        });
    }

    public function getExistDates(array $dates)
    {
        return $this->decision->whereIn('publication_at', $dates)->get();
    }

    public function find($id){

        return $this->decision->find($id);
    }

    public function findArchive($id,$year){

        $name = $year == date('Y') ? 'decisions' : 'archive_'.$year;
        $conn = $year == date('Y') ? $this->main_connection : 'sqlite';

        return $this->decision->setConnection($conn)->setTable($name)->find($id);
    }

    public function getDateArchive($date,$year){

        $name = $year == date('Y') ? 'decisions' : 'archive_'.$year;
        $conn = $year == date('Y') ? $this->main_connection : 'sqlite';

        return $this->decision->setConnection($conn)->setTable($name)->whereDate('publication_at', '=', $date)->get();
    }

    public function findByNumeroAndDate($numero,$date){

        $found = $this->decision->where('numero','=',$numero)->where('publication_at','=',$date)->get();

        return !$found->isEmpty() ? $found->first() : false;
    }

    // $params array terms, categorie, published, publications_at
    public function search($params)
    {
        $terms     = isset($params['terms']) && !empty($params['terms']) ? $params['terms'] : null;
        $categorie = isset($params['categorie']) ? $params['categorie'] : null;
        $published = isset($params['published']) ? $params['published'] : null;
        $publication_at = isset($params['publication_at']) ? $params['publication_at'] : null;

        return $this->decision->select('id','numero','categorie_id','remarque','publication_at','decision_at','langue','publish')
            ->with(['categorie'])
            ->search($terms)
            ->categorie($categorie)
            ->published($published)
            ->publicationAt($publication_at)
            ->groupBy('id')
            ->get();
    }

    public function byCategories($categorie_id){
        $results = collect([]);
        $tables  = array_reverse(range(2012,date('Y')));

        foreach ($tables as $table) {
            $name = $table == date('Y') ? 'decisions' : 'archive_'.$table;
            $conn = $table == date('Y') ? $this->main_connection : 'sqlite';
            $cast = $table == date('Y') ? 'Year(publication_at) as year' : "strftime('%Y',publication_at) as year";

            if (Schema::connection($conn)->hasTable($name)) {

                $result = \DB::connection($conn)->table($name)
                    ->select($name.'.id',$name.'.numero',$name.'.categorie_id',$name.'.remarque',$name.'.publication_at',$name.'.decision_at',$name.'.langue',$name.'.publish')
                    ->selectRaw($cast)
                    ->where($name.'.categorie_id', '=' ,$categorie_id)
                    ->orderBy('publication_at')
                    ->get();

                $results = $results->merge($result);
            }
        }

        return $results;
    }

    public function searchArchives($params)
    {
        $results = collect([]);
        $period  = isset($params['period']) && !empty($params['period']) ? $params['period'] : null;
        $tables  = $period ? archiveTableForDates($period[0],$period[1]) : range(2012,date('Y'));

        foreach ($tables as $year) {
            // For live
            $name    = $year == date('Y') ? 'decisions' : 'archive_'.$year;
            $conn    = $year == date('Y') ? $this->main_connection : 'sqlite';
            // For dev
           // $name    = 'decisions';
            //$conn    = $this->main_connection;

            if (Schema::connection($conn)->hasTable($name)) {
                $result  = $this->searchTable($name,$conn,$params,$year);
                $results = $results->merge($result);
            }
        }

        return $results;
    }

    public function searchTable($table,$conn,$params,$year)
    {
        $terms        = isset($params['terms']) && !empty($params['terms']) ? prepareTerms($params['terms']) : null;
        $published    = isset($params['published']) && $params['published'] == 1 ? $params['published'] : null;
        $period       = isset($params['period']) ? $params['period'] : null;
        $categorie_id = isset($params['categorie_id']) ? $params['categorie_id'] : null;

        // For live
        $cast         = $year == date('Y') ? 'Year(publication_at) as year' : "strftime('%Y',publication_at) as year";
        // For dev
        //$cast         = 'Year(publication_at) as year';

        $model = \DB::connection($conn)->table($table)
            ->select($table.'.id',$table.'.numero',$table.'.categorie_id',$table.'.remarque',$table.'.publication_at',$table.'.decision_at',$table.'.langue',$table.'.publish')
            ->selectRaw($cast);

        if($terms){
            $terms = array_map('addSlashes', $terms->toArray());
            $first = array_shift($terms);
            $model->where('texte','LIKE',$first);

            if(!empty($terms)){
                foreach($terms as $term) {
                    $model->orWhere('texte','LIKE',$term);
                }
            }
        }

        if($period){
            $model->whereBetween('publication_at', $period);
        }

        if($published){
            $model->where('publish', '=' ,1);
        }

        if($categorie_id){
            $model->where('categorie_id', '=' ,$categorie_id);
        }

        return $model->get();
    }

    protected function fullTextWildcards($term)
    {
        return str_replace(' ', '*', $term) . '*';
    }

    public function searchTableLite($table,$params)
    {
        $terms     = isset($params['terms']) && !empty($params['terms']) ? prepareTerms($params['terms']) : null;
        $published = isset($params['published']) ? $params['published'] : null;
        $period    = isset($params['period']) ? $params['period'] : null;

        return $this->decision->setTable($table)
            ->search($terms)
            ->whereBetween('publication_at', $period)
            ->published($published)
            ->get();
    }

    public function create(array $data){

        $exist = $this->findByNumeroAndDate($data['numero'],$data['publication_at']);

        if($exist){ return false; }

        $decision = $this->decision->create(array(
            'id'             => isset($data['id']) ? $data['id'] : null,
            'publication_at' => $data['publication_at'],
            'decision_at'    => $data['decision_at'],
            'categorie_id'   => isset($data['categorie_id']) ? $data['categorie_id'] : null,
            'remarque'       => isset($data['remarque']) ? $data['remarque'] : null,
            'numero'         => isset($data['numero']) ? $data['numero'] : null,
            'link'           => isset($data['link']) ? $data['link'] : '',
            'texte'          => isset($data['texte']) ? $data['texte'] : '',
            'langue'         => isset($data['langue']) ? $data['langue'] : 0,
            'publish'        => isset($data['publish']) ? $data['publish'] : null,
            'updated'        => isset($data['updated']) ? $data['updated'] : null,
            'created_at'     => isset($data['created_at']) ? $data['created_at'] : null,
            'updated_at'     => isset($data['updated_at']) ? $data['updated_at'] : null,
        ));

        if( ! $decision )
        {
            return false;
        }

        return $decision;

    }

    public function update(array $data){

        $decision = $this->decision->setConnection($this->main_connection)->findOrFail($data['id']);

        if( ! $decision )
        {
            return false;
        }

        $decision->fill($data);
        $decision->save();

        return $decision;
    }

    public function updateArchive(array $data, $year){

        return \DB::connection('sqlite')->table('archive_'.$year)->where('id','=',$data['id'])->update($data);
    }

    public function delete($id){

        $decision = $this->decision->find($id);

        return $decision->delete();
    }

    public function deleteDate($date){
        return $this->decision->whereIn('publication_at', $date)->delete();
    }

}
