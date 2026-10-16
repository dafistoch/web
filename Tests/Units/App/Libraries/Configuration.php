<?php
namespace Test\Units\App\Libraries;

use App\Libraries\Configuration as _Configuration;

class Configuration extends \Tests\Units\TestUnit
{
    private $result;
    private $db;
    private $config;

    public function beforeTestMethod($method)
    {
        parent::beforeTestMethod($method);
        $this->result = new \mock\Mysqli\Result();
        $this->db = new \mock\includes\SQL();
        $this->calling($this->db)->query = $this->result;
        
        $this->calling($this->result)->fetch_array[1] =  [
                        'conf_nom' => 'installed_version',
                        'conf_valeur' => '1.9',
                        'conf_groupe' => '00_libertempo',
                        'conf_type' => 'texte',
                        'conf_commentaire' => 'config_comment_installed_version'
                    ];
        
        $this->calling($this->result)->fetch_array[2] = [
                        'conf_nom' => "user_saisie_demande",
                        'conf_valeur' => "TRUE",
                        'conf_groupe' => "05_Utilisateur",
                        'conf_type' => "boolean",
                        'conf_commentaire' => "config_comment_user_saisie_demande"
                    ];
        
        $this->calling($this->result)->fetch_array[3] = null;
        $this->config = new _Configuration($this->db);

    }

    public function testcanUserSaisieDemande()
    {
        $canDemande = $this->config->canUserSaisieDemande();
        $this->boolean($canDemande)->isTrue;
    }

    public function testgetInstalledVersion()
    {
        $version = $this->config->getInstalledVersion();
        $this->variable($version)->isIdenticalTo('1.9');
    }

    public function testgetLogoAssociationTailleParDefaut()
    {
        $this->string($this->config->getLogoAssociation())->isEmpty();
        $this->integer($this->config->getLogoAssociationLargeur())->isIdenticalTo(60);
        $this->integer($this->config->getLogoAssociationHauteur())->isIdenticalTo(60);
    }

    public function testgetLogoAssociationTaillePersonnalisee()
    {
        $this->result = new \mock\Mysqli\Result();
        $this->db = new \mock\includes\SQL();
        $this->calling($this->db)->query = $this->result;
        $this->calling($this->result)->fetch_array[1] = [
            'conf_nom' => 'logo_association',
            'conf_valeur' => 'Public/Assets/Img/logo_association.png',
            'conf_groupe' => '13_Divers',
            'conf_type' => 'path',
            'conf_commentaire' => 'config_comment_logo_association'
        ];
        $this->calling($this->result)->fetch_array[2] = [
            'conf_nom' => 'logo_association_largeur',
            'conf_valeur' => '120',
            'conf_groupe' => '13_Divers',
            'conf_type' => 'texte',
            'conf_commentaire' => 'config_comment_logo_association_largeur'
        ];
        $this->calling($this->result)->fetch_array[3] = [
            'conf_nom' => 'logo_association_hauteur',
            'conf_valeur' => 'abc',
            'conf_groupe' => '13_Divers',
            'conf_type' => 'texte',
            'conf_commentaire' => 'config_comment_logo_association_hauteur'
        ];
        $this->calling($this->result)->fetch_array[4] = null;
        $config = new _Configuration($this->db);

        $this->string($config->getLogoAssociation())->isIdenticalTo('Public/Assets/Img/logo_association.png');
        $this->integer($config->getLogoAssociationLargeur())->isIdenticalTo(120);
        $this->integer($config->getLogoAssociationHauteur())->isIdenticalTo(60);
    }

}