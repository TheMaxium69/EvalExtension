<?php

class TirageExtension_Admin
{
    //Function qui ce lance au démarrage de la class 'TirageExtension_Admin'
    public function __construct()
    {
        //On créé un menu admin
        add_action('admin_menu', array($this, 'addAdminMenu'));
    }

    //Function ou on déclare le contenu du menu admin
    public function addAdminMenu()
    {
        add_menu_page(
            'Tirage Au Sort - Mon plugin',
            'Tirage Au Sort Extension',
            'manage_options',
            'TirageExtension',
            array($this, 'generateHomeHtml'),
            plugin_dir_url(__FILE__) . 'icon.png'
        );

        add_submenu_page(
            'TirageExtension',
            'Home',
            'Home',
            'manage_options',
            'TirageExtension',
            array($this, 'generateHomeHtml')
        );

        add_submenu_page(
            'TirageExtension',
            'Tirage',
            'Tirage',
            'manage_options',
            'TirageExtension_tirage',
            array($this, 'generateTirageHtml')
        );
    }

    private function getAllInscrit()
    {
        global $wpdb;

        //On recupère tout le contenu de la base
        $suscribers = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}tirage_au_sort");
        return $suscribers;
    }

    public function generateHomeHtml()
    {
        //On recupère tout le contenu de la base grace a la function 'getAllContects
        $suscribers = $this->getAllInscrit();
        //On Vide l'html
        $html = "";

        //Si il y a des inscrit
        if (count($suscribers) > 0) {
            $html .= '<h1>' . get_admin_page_title() .'</h1>
                      <p>Pour afficher la liste des inscrits, utilisez le shortcode suivant : <br><code>[tirage_formulaire][/tirage_formulaire]</code></p>
                      <h3>Liste des inscrit</h3>
                      <table class="tirage-formulaire-liste" style="border-collapse:collapse"><tbody>';
            //On fait un forEach pour afficher tout les inscrits dans un <td>
            foreach ($suscribers as $suscriber) {
                $html .= "<tr>
                            <td width='150' style='border:1px solid black;'>{$suscriber->last_name}</td>
                            <td width='150' style='border:1px solid black;'>{$suscriber->first_name}</td>
                            <td width='300' style='border:1px solid black;'>{$suscriber->email}</td>
                            <td width='150' style='border:1px solid black;'>{$suscriber->date}</td>
                            <td width='150' style='border:1px solid black;'>{$suscriber->city}</td>
                          </tr>";
            }
            $html .= '<tbody></table>';

        //Si il y a pas d'inscrit
        } else {
            $html .= "<p>Not inscrit</p>";
        }

        //On affiche le contenu de la page
        echo $html;
    }

    public function generateTirageHtml(){
        //On recupère tout le contenu de la base grace a la function 'getAllContects
        $suscribers = $this->getAllInscrit();
        //On Stocke le nombre de subriber
        $nbSucribers = count($suscribers);
        //On Vide l'html
        $html = "";

        //Si il y a des inscrit
        if ($nbSucribers > 0) {
            global $wpdb;
            //On selection dans un orde alléatoire nos donné dans la table
            $suscribersRandom = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}tirage_au_sort ORDER BY RAND()");

            //Et On affiche uniquement le premier inscrit recupere (rappel: le première inscrit donné dans un orde alléatoire par la requette sqlr)
            $html .= "<h1>" . get_admin_page_title() . "</h1>
                      <p>Pour afficher la liste des inscrits, utilisez le shortcode suivant : <br><code>[tirage_formulaire][/tirage_formulaire]</code></p>
                      <h2>La personne tiré au sort est :</h2> <table class='tirage-formulaire-liste' style='border-collapse:collapse'><tbody><tr>
                      <td width='150' style='border:1px solid black;'>{$suscribersRandom[0]->last_name}</td>
                      <td width='150' style='border:1px solid black;'>{$suscribersRandom[0]->first_name}</td>
                      <td width='150' style='border:1px solid black;'>{$suscribersRandom[0]->email}</td>
                      <td width='150' style='border:1px solid black;'>{$suscribersRandom[0]->date}</td>
                      <td width='150' style='border:1px solid black;'>{$suscribersRandom[0]->city}</td>
                      </tr><tbody></table>";
        //Si il y a pas d'inscrit
        } else {
            $html .= "<p>Tu n'a pas d'inscrits donc tu ne peut pas tiré au sort</p>";
        }

        //On affiche le contenu de la page
        echo $html;

    }
}
