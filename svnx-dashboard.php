<?php
/*
Plugin Name: 7x - Dashboard Info
Plugin URI: https://github.com/loschke/sevenX_Dashboard
Description: Zeigt die aktuelle Entwicklungsumgebung an und informiert über Kontakt/Supportmöglichkeit zum Dienstleister.
Version: 0.1
Author: sevenX - Rico Loschke
Author URI: http://sevenx.de
Author Email: hello@sevenx.de
*/

class svnx_Dashboard {

	private $_stageTitle,
			$_stageBGColor;

	public static function svnx_dashboardInit()
	{
		$self = new self();

		if (defined('STAGE_INFO') && (STAGE_INFO == true)) {
			switch (WP_ENV) {
				case "dev":
					$self->_stageTitle = "Developement";
					$self->_stageBGColor = "#E24D35";
					break;
				case "staging":
					$self->_stageTitle = "Staging/Beta";
					$self->_stageBGColor = "#4AAFCD";
					break;
				case "live":
					$self->_stageTitle = "Livesystem";
					$self->_stageBGColor = "#B3CC57";
					break;
			}

			add_action('admin_bar_menu', array( $self, 'svnx_addBtnToAdminBar' ));
			add_filter('login_message', array( $self, 'svnx_stageLoginMessage' ));
			add_action('wp_head', array( $self, 'svnx_addBtnStyle' ));
			add_action('admin_head', array( $self, 'svnx_addBtnStyle' ));
            add_action( 'admin_menu', array( $self, 'svnx_editAdminMenu' ));

		}
	}

	// add style for stage mode button to header
	public function svnx_addBtnStyle() {
		$inlineStyle = "<style>#wpadminbar .stage-mode-style {background-color:" . $this->_stageBGColor . " !important; min-width:160px};</style>";
		echo $inlineStyle;
	}

	// add stage mode button to admin bar
	public function svnx_addBtnToAdminBar($adminBar) {
		if ( is_admin_bar_showing() ) {
			$adminBar->add_menu( array(
				'id'     => 'dev-mode',
				'parent' => false,
				'title'  => $this->_stageTitle,
				'href'   => '#',
				'meta'   => array(
					'title' => __( $this->_stageTitle ),
					'class' => 'stage-mode-style'
				),
			) );
		}
	}

	//add stage mode info to login screen
	public function svnx_stageLoginMessage() {
		$loginMessage = "<p class='message' style='background-color:" . $this->_stageBGColor . " !important; color:#fff; margin-bottom:20px'>" . $this->_stageTitle . __(' Environment') . "</p>";
		echo $loginMessage;
	}

    public function svnx_editAdminMenu() {
        global $menu;
        $menu[5][0] = 'Aktuelles'; // Rename "Beiträge"/"Posts" to Aktuelles
        remove_menu_page('edit-comments.php'); // Remove the Comments Menu
    }

}
//add_action( 'init', array( 'svnx_Dashboard', 'svnx_dashboardInit' ) );
svnx_Dashboard::svnx_dashboardInit();
