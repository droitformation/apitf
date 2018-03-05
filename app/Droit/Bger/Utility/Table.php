<?php
/**
 * Created by PhpStorm.
 * User: cindyleschaud
 * Date: 02.08.17
 * Time: 10:54
 */

namespace App\Droit\Bger\Utility;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class Table
{
    public $prefix = 'archive_';
    public $yearStart = '2012';
    public $year;
    public $mainTable = 'decisions';
    protected $delete = [];

    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    public function getTableName(){

        return $this->prefix.$this->year;
    }

    public function create(){

        if (!Schema::connection('sqlite')->hasTable($this->prefix.$this->year)) {

            Schema::connection('sqlite')->create($this->prefix.$this->year, function (Blueprint $table) {
                $table->integer('id');
                $table->string('numero');
                $table->dateTime('publication_at');
                $table->dateTime('decision_at');
                $table->integer('categorie_id')->nullable();
                $table->text('remarque')->nullable();
                $table->string('link')->nullable();
                $table->longText('texte')->nullable();
                $table->tinyInteger('langue')->nullable();
                $table->tinyInteger('publish')->nullable();
                $table->tinyInteger('updated')->nullable();
                $table->timestamps();
            });

            //\DB::connection('sqlite')->statement('ALTER TABLE '.$this->prefix.$this->year.' ADD FULLTEXT full(texte)');
        }

        return $this;
    }

    public function transfertArchives()
    {
        $name = $this->getTableName();

        \DB::connection('mysql')->table($this->mainTable)->whereYear('publication_at', $this->year)->orderBy('id')->chunk(100, function ($decisions) use ($name) {
            foreach ($decisions as $decision) {

                $exist = \DB::connection('sqlite')->table($name)->where("id", $decision->id)->get();

                if($exist->isEmpty()){
                    // Archive decision
                    \DB::connection('sqlite')->table($name)->insert((array) $decision);
                    \Log::info('insert');
                }

                $this->delete[] = $decision->id;
            }
        });

        // Delete after from main table because elese chunl doesn't get all recordss
        \DB::connection('mysql')->table($this->mainTable)->whereIn("id", $this->delete)->delete();
    }

    public function deleteLastYear()
    {
        $name = $this->getTableName();

        if($this->countDecisions($this->mainTable,'mysql') == $this->countDecisions($name,'sqlite')){
            \DB::connection('mysql')->table($this->mainTable)->whereYear('publication_at', $this->year)->delete();
            \Log::info('delete ',$this->year);
        }
    }

    public function countDecisions($table,$connexion)
    {
        return \DB::connection($connexion)->table($table)->whereYear('publication_at', $this->year)->count();
    }

    public function canTransfert()
    {
        $count = \DB::connection('mysql')->table($this->mainTable)->whereYear('publication_at', $this->year)->count();

        if($count == 0){
            throw new \App\Exceptions\TransfertException('Aucun arrêt pour cette année: '.$this->year);
            die();
        }

        return $this;
    }
}