<?php

use Illuminate\Database\Seeder;

class ParentCategorieTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('parent_categories')->truncate();

        $parent_categories = array(
            array('id' => '1','nom' => 'Économie'),
            array('id' => '2','nom' => 'Instruction et formation professionnelle'),
            array('id' => '3','nom' => 'Procédure'),
            array('id' => '4','nom' => 'Média'),
            array('id' => '6','nom' => 'Responsabilité de l\'État'),
            array('id' => '7','nom' => 'Santé & sécurité sociale'),
            array('id' => '8','nom' => 'Droit constitutionnel'),
            array('id' => '9','nom' => 'Droit des poursuites et faillites'),
            array('id' => '10','nom' => 'Finances publiques & droit fiscal'),
            array('id' => '11','nom' => 'Droit pénal'),
            array('id' => '12','nom' => 'Droit de cité et droit des étrangers'),
            array('id' => '13','nom' => 'Propriété intellectuelle'),
            array('id' => '14','nom' => 'Droit privé'),
            array('id' => '15','nom' => 'Droit administratif'),
            array('id' => '16','nom' => 'Assurances sociales'),
            array('id' => '17','nom' => 'Énergie'),
            array('id' => '18','nom' => 'Droit des obligations'),
            array('id' => '19','nom' => 'Droit de l\'avocat'),
            array('id' => '20','nom' => 'Juridiction arbitrale'),
            array('id' => '21','nom' => 'Droit commercial'),
            array('id' => '23','nom' => 'Art et culture'),
            array('id' => '24','nom' => 'Poste et télécommunications')
        );


        \DB::table('parent_categories')->insert($parent_categories);
    }
}
