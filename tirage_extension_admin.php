<?php

class TirageExtension_Admin
{
    public function __construct()
    {
        add_action('admin_menu', array($this, 'addAdminMenu'));
    }

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

    private function getAllContacts()
    {
        global $wpdb;

        //On recupère tout le contenu de la base
        $suscribers = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}tirage_au_sort");
        return $suscribers;
    }

    public function generateHomeHtml()
    {
        $suscribers = $this->getAllContacts();
        $html = "";
        if (count($suscribers) > 0) {
            $html .= '<h1>' . get_admin_page_title() .'</h1>
<p>Pour afficher la liste des inscrits, utilisez le shortcode suivant : <br><code>[tirage_formulaire][/tirage_formulaire]</code></p>
<h3>Liste des inscrit</h3>
<table class="my-formulaire-liste" style="border-collapse:collapse"><tbody>';
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

        } else {
            $html .= "<p>Not inscrit</p>";
        }
        echo $html;
    }

    public function generateTirageHtml(){

        echo 'coucou';

    }
}
