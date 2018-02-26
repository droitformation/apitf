<?php

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('categories')->truncate();

        $categories = array(
            array('id' => '174','name' => 'Droit fondamental','name_de' => 'Grundrecht','name_it' => 'Diritto fondamentale','parent_id' => '8','rang' => '2','general' => 'Droits fondamentaux','global' => NULL),
            array('id' => '175','name' => 'Droit de cité et droit des étrangers','name_de' => 'Bürgerrecht und Ausländerrecht','name_it' => 'Cittadinanza e diritto degli stranieri','parent_id' => '12','rang' => '1','general' => '','global' => NULL),
            array('id' => '176','name' => 'Responsabilité de l\'État','name_de' => 'Staatshaftung','name_it' => 'Responsabilità dello Stato','parent_id' => '6','rang' => '1','general' => '','global' => NULL),
            array('id' => '177','name' => 'Fonction publique','name_de' => 'Öffentliches Dienstverhältnis','name_it' => 'Pubblica amministrazione','parent_id' => '15','rang' => '5','general' => '','global' => NULL),
            array('id' => '178','name' => 'Droits politique','name_de' => 'Politische Rechte','name_it' => 'Diritto politici','parent_id' => '8','rang' => '3','general' => 'Droits politiques','global' => NULL),
            array('id' => '179','name' => 'Entraide et extradition','name_de' => 'Rechtshilfe und Auslieferung','name_it' => 'Assistenza giudiziaria e estradizione','parent_id' => '11','rang' => '5','general' => '','global' => NULL),
            array('id' => '180','name' => 'Instruction et formation professionnelle','name_de' => 'Unterrichtswesen und Berufsausbildung','name_it' => 'Istruzione e formazione professionale','parent_id' => '2','rang' => '1','general' => '','global' => NULL),
            array('id' => '181','name' => 'Art et culture','name_de' => 'Kunst und Kultur','name_it' => 'Arte e cultura','parent_id' => '23','rang' => '1','general' => '','global' => NULL),
            array('id' => '182','name' => 'Équilibre écologique','name_de' => 'Ökologisches Gleichgewicht','name_it' => 'Equilibrio ecologico','parent_id' => '15','rang' => '4','general' => '','global' => NULL),
            array('id' => '183','name' => 'Politique de sécurité et de promotion de la paix','name_de' => 'Sicherheits- und Friedenspolitik','name_it' => 'Politica di sicurezza e di pace','parent_id' => '15','rang' => '11','general' => '','global' => NULL),
            array('id' => '184','name' => 'Finances publiques & droit fiscal','name_de' => 'Öffentliche Finanzen & Abgaberecht','name_it' => 'Finanze pubbliche & diritto tributario','parent_id' => '10','rang' => '1','general' => '','global' => NULL),
            array('id' => '185','name' => 'Aménagement du territoire et droit public des constructions','name_de' => 'Raumplanung und öffentliches Baurecht','name_it' => 'Pianificazione territoriale e diritto pubblico edilizio','parent_id' => '15','rang' => '1','general' => '','global' => NULL),
            array('id' => '186','name' => 'Expropriation','name_de' => 'Enteignung','name_it' => 'Espropriazione','parent_id' => '15','rang' => '8','general' => '','global' => NULL),
            array('id' => '187','name' => 'Énergie & transport & poste et télécommunication, média','name_de' => 'Energie & Verkehr & Post- und Fernmeldeverkehr, Medien','name_it' => 'Energia & trasporto & poste e telecomunicazioni, mass media','parent_id' => '17','rang' => '1','general' => '','global' => NULL),
            array('id' => '188','name' => 'Énergie','name_de' => 'Energie','name_it' => 'Energia','parent_id' => '17','rang' => '0','general' => '','global' => NULL),
            array('id' => '189','name' => 'Construction des routes et circulation routière','name_de' => 'Strassenbau und Strassenverkehr','name_it' => 'Costruzioni stradali e circolazione stradale','parent_id' => '15','rang' => '2','general' => '','global' => NULL),
            array('id' => '190','name' => 'Transport (sans circulation routière)','name_de' => 'Verkehr (ohne Strassenverkehr)','name_it' => 'Trasporto (senza circolazione stradale)','parent_id' => '15','rang' => '3','general' => '','global' => NULL),
            array('id' => '191','name' => 'Poste et télécommunications','name_de' => 'Post- und Fernmelde Verkehr','name_it' => 'Posta e telecomunicazioni','parent_id' => '24','rang' => '1','general' => '','global' => NULL),
            array('id' => '192','name' => 'Média','name_de' => 'Medien','name_it' => 'Mass media','parent_id' => '4','rang' => '1','general' => '','global' => NULL),
            array('id' => '193','name' => 'Droit des assurances sociales','name_de' => 'Sozialversicherungsrecht','name_it' => 'Diritto delle assicurazioni sociali','parent_id' => '16','rang' => '8','general' => '','global' => NULL),
            array('id' => '194','name' => 'Assurance-vieillesse et survivants','name_de' => 'Alters- und Hinterlassenenversicherung','name_it' => 'Assicurazione per la vecchiaia e per i superstiti','parent_id' => '16','rang' => '7','general' => '','global' => NULL),
            array('id' => '195','name' => 'Assurance-invalidité','name_de' => 'Invalidenversicherung','name_it' => 'Assicurazione per l\'invalidità','parent_id' => '16','rang' => '5','general' => '','global' => NULL),
            array('id' => '196','name' => 'Prestations complémentaires à l\'AVS/AI','name_de' => 'Ergänzungsleistung','name_it' => 'Prestazione complementari','parent_id' => '16','rang' => '9','general' => '','global' => NULL),
            array('id' => '197','name' => 'Prévoyance professionnelle','name_de' => 'Berufliche Vorsorge','name_it' => 'Previdenza professionale','parent_id' => '16','rang' => '10','general' => '','global' => NULL),
            array('id' => '198','name' => 'Assurance maladie','name_de' => 'Krankenversicherung','name_it' => 'Assicurazione contro le malattie','parent_id' => '16','rang' => '6','general' => '','global' => NULL),
            array('id' => '199','name' => 'Assurance-accidents','name_de' => 'Unfallversicherung','name_it' => 'Assicurazione contro gli infortuni','parent_id' => '16','rang' => '3','general' => '','global' => NULL),
            array('id' => '200','name' => 'Assurance militaire','name_de' => 'Militärversicherung','name_it' => 'Assicurazione militare','parent_id' => '16','rang' => '1','general' => '','global' => NULL),
            array('id' => '201','name' => 'Régime allocations et pertes de gains','name_de' => 'Erwerbersatzordnung','name_it' => 'Indennità per perdita di guadagno','parent_id' => '16','rang' => '11','general' => '','global' => NULL),
            array('id' => '202','name' => 'Allocation familiale dans l\'agriculture','name_de' => 'Familienzulagen in der Landwirtschaft','name_it' => 'Assegni familiari nell\'agricoltura','parent_id' => '16','rang' => '12','general' => '','global' => NULL),
            array('id' => '203','name' => 'Assurance-chômage','name_de' => 'Arbeitslosenversicherung','name_it' => 'Assicurazione contro la disoccupazione','parent_id' => '16','rang' => '4','general' => '','global' => NULL),
            array('id' => '204','name' => 'Santé & sécurité sociale','name_de' => 'Gesundheitswesen & soziale Sicherheit','name_it' => 'Sanità & sicurezza sociale','parent_id' => '7','rang' => '1','general' => '','global' => NULL),
            array('id' => '205','name' => 'Économie','name_de' => 'Wirtschaft','name_it' => 'Economia','parent_id' => '1','rang' => '1','general' => '','global' => NULL),
            array('id' => '206','name' => 'Procédure','name_de' => 'Verfahren','name_it' => 'Procedura','parent_id' => '3','rang' => '0','general' => '','global' => NULL),
            array('id' => '207','name' => 'Procédure civile','name_de' => 'Zivilprozess','name_it' => 'Procedura civile','parent_id' => '3','rang' => '3','general' => '','global' => NULL),
            array('id' => '208','name' => 'Procédure pénale','name_de' => 'Strafprozess','name_it' => 'Procedura penale','parent_id' => '3','rang' => '2','general' => '','global' => NULL),
            array('id' => '209','name' => 'Procédure administrative','name_de' => 'Verwaltungsverfahren','name_it' => 'Procedura amministrativa','parent_id' => '3','rang' => '4','general' => '','global' => NULL),
            array('id' => '210','name' => 'Questions de compétences, garantie du juge du domicile et du juge naturel','name_de' => 'Zuständigkeitsfragen, Garantie des Wohnsitzrichters und des verfassungsmässigen Richters','name_it' => 'Quesiti di competenza, garanzia del foro del domicilio e del giudice costituzionale','parent_id' => '3','rang' => '16','general' => '','global' => NULL),
            array('id' => '211','name' => 'Exécution forcée','name_de' => 'Zwangsvollstreckung','name_it' => 'Esecuzione forzata','parent_id' => '3','rang' => '6','general' => '','global' => NULL),
            array('id' => '212','name' => 'Juridiction arbitrale','name_de' => 'Schiedsgerichtsbarkeit','name_it' => 'Giuridizione arbitrale','parent_id' => '20','rang' => '1','general' => '','global' => NULL),
            array('id' => '213','name' => 'Droit des poursuites et faillites','name_de' => 'Schuldbetreibungs- und Konkursrecht','name_it' => 'Diritto delle esecuzioni e del fallimento','parent_id' => '9','rang' => '1','general' => '','global' => NULL),
            array('id' => '214','name' => 'Droit privé','name_de' => 'Privatrecht','name_it' => 'Diritto privato','parent_id' => '14','rang' => '0','general' => '','global' => NULL),
            array('id' => '215','name' => 'Droit civil','name_de' => 'Zivilrecht','name_it' => 'Diritto civile','parent_id' => '14','rang' => '10','general' => '','global' => NULL),
            array('id' => '216','name' => 'Droit privé (en général)','name_de' => 'Privatrecht (allgemein)','name_it' => 'Diritto privato (in generale)','parent_id' => '14','rang' => '1','general' => '','global' => NULL),
            array('id' => '217','name' => 'Droit des personnes','name_de' => 'Personenrecht','name_it' => 'Diritto delle persone','parent_id' => '14','rang' => '2','general' => '','global' => NULL),
            array('id' => '218','name' => 'Droit de la famille','name_de' => 'Familienrecht','name_it' => 'Diritto di famiglia','parent_id' => '14','rang' => '3','general' => '','global' => NULL),
            array('id' => '219','name' => 'Droit des successions','name_de' => 'Erbrecht','name_it' => 'Diritto successorio','parent_id' => '14','rang' => '4','general' => '','global' => NULL),
            array('id' => '220','name' => 'Droits réels','name_de' => 'Sachenrecht','name_it' => 'Diritti reali','parent_id' => '14','rang' => '5','general' => '','global' => NULL),
            array('id' => '221','name' => 'Registre','name_de' => 'Register','name_it' => 'Registro','parent_id' => '21','rang' => '3','general' => '','global' => NULL),
            array('id' => '222','name' => 'Droit des obligations et droit commercial','name_de' => 'Obligationenrecht und Handelsrecht','name_it' => 'Diritto delle obbligazioni e diritto commerciale','parent_id' => '18','rang' => '1','general' => '','global' => NULL),
            array('id' => '223','name' => 'Droit des obligations (général)','name_de' => 'Obligationenrecht (allgemein)','name_it' => 'Diritto delle obbligazioni (generale)','parent_id' => '18','rang' => '0','general' => '','global' => NULL),
            array('id' => '224','name' => 'Droit des contrats','name_de' => 'Vertragsrecht','name_it' => 'Diritto contrattuale','parent_id' => '18','rang' => '7','general' => '','global' => NULL),
            array('id' => '225','name' => 'Droit des sociétés','name_de' => 'Gesellschaftsrecht','name_it' => 'Diritto delle società','parent_id' => '21','rang' => '2','general' => '','global' => NULL),
            array('id' => '226','name' => 'Papiers-valeurs','name_de' => 'Wertpapierrecht','name_it' => 'Cartavalore','parent_id' => '21','rang' => '1','general' => '','global' => NULL),
            array('id' => '227','name' => 'Assurance responsabilité civile','name_de' => 'Haftpflichtrecht','name_it' => 'Assicurazione responsabilità civile','parent_id' => '18','rang' => '4','general' => 'Responsabilité civile','global' => NULL),
            array('id' => '228','name' => 'Propriété intellectuelle, concurrence et cartels','name_de' => 'Immaterialgüter-, Wettbewerbs- und Kartellrecht','name_it' => 'Proprietà intelletuale, concorrenza e cartelli','parent_id' => '13','rang' => '1','general' => '','global' => NULL),
            array('id' => '229','name' => 'Droit pénal','name_de' => 'Strafrecht','name_it' => 'Diritto penale','parent_id' => '11','rang' => '0','general' => '','global' => NULL),
            array('id' => '230','name' => 'Droit pénal (en général)','name_de' => 'Strafrecht (allgemein)','name_it' => 'Diritto penale (in generale)','parent_id' => '11','rang' => '1','general' => '','global' => NULL),
            array('id' => '231','name' => 'Infractions','name_de' => 'Straftaten','name_it' => 'Infrazione','parent_id' => '11','rang' => '2','general' => '','global' => NULL),
            array('id' => '232','name' => 'Droit pénal administratif','name_de' => 'Verwaltungsstrafrecht','name_it' => 'Diritto penale amministrativo','parent_id' => '11','rang' => '6','general' => '','global' => NULL),
            array('id' => '233','name' => 'Exécution des peines et des mesures','name_de' => 'Straf- und Massnahmenvollzug','name_it' => 'Esecuzione delle pene e delle misure','parent_id' => '11','rang' => '4','general' => '','global' => NULL),
            array('id' => '234','name' => 'Procédures disciplinaires','name_de' => 'Aufsichtsbeschwerden','name_it' => 'Procedimento disciplinare','parent_id' => '3','rang' => '15','general' => '','global' => NULL),
            array('id' => '235','name' => 'Recours en matière de surveillance','name_de' => 'Zuflucht über die Überwachung','name_it' => 'Ricorso di sorveglianza','parent_id' => '15','rang' => '7','general' => '','global' => NULL),
            array('id' => '244','name' => 'Droit de l\'avocat','name_de' => 'Anwaltsgesetz','name_it' => 'Avvocatura','parent_id' => '19','rang' => '1','general' => '','global' => NULL),
            array('id' => '247','name' => 'Général','name_de' => 'Allgemeine','name_it' => 'Generale','parent_id' => '0','rang' => '0','general' => '','global' => NULL),
            array('id' => '279','name' => 'Régime allocations et pertes de gain','name_de' => 'Régime allocations et pertes de gain-allemand','name_it' => 'Régime allocations et pertes de gain-italien','parent_id' => '0','rang' => '0','general' => '','global' => NULL),
            array('id' => '280','name' => '0','name_de' => '0-allemand','name_it' => '0-italien','parent_id' => '0','rang' => '0','general' => '','global' => NULL),
            array('id' => '281','name' => 'Gesundheitswesen &amp; soziale Sicherheit','name_de' => 'Gesundheitswesen &amp; soziale Sicherheit-allemand','name_it' => 'Gesundheitswesen &amp; soziale Sicherheit-italien','parent_id' => '0','rang' => '0','general' => '','global' => NULL),
            array('id' => '282','name' => 'Sanità &amp; sicurezza sociale','name_de' => 'Sanità &amp; sicurezza sociale-allemand','name_it' => 'Sanità &amp; sicurezza sociale-italien','parent_id' => '0','rang' => '0','general' => '','global' => NULL),
            array('id' => '283','name' => 'Santé &amp; sécurité sociale','name_de' => 'Santé &amp; sécurité sociale-allemand','name_it' => 'Santé &amp; sécurité sociale-italien','parent_id' => '0','rang' => '0','general' => '','global' => NULL),
            array('id' => '284','name' => 'Finances publiques &amp; droit fiscal','name_de' => 'Finances publiques &amp; droit fiscal-allemand','name_it' => 'Finances publiques &amp; droit fiscal-italien','parent_id' => '0','rang' => '0','general' => '','global' => NULL)
        );

        \DB::table('categories')->insert($categories);

    }
}
