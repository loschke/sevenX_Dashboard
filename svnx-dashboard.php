<?php
/*
Plugin Name: 7x - Stage Info
Plugin URI: https://bitbucket.org/svnx/wp-plugin-kit
Description: Zeigt die aktuelle Entwicklungsumgebung in der AdminBar an.
Version: 1.0
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

			if ( is_admin_bar_showing() ) {
				add_action('wp_head', array( $self, 'svnx_addBtnStyle' ));
				add_action('admin_head', array( $self, 'svnx_addBtnStyle' ));

			}
		}
	}

	// add style for stage mode button to header
	public function svnx_addBtnStyle() {
		$inlineStyle = "<style>#wpadminbar .stage-mode-style {background-color:" . $this->_stageBGColor . " !important; min-width:160px};</style>";
		echo $inlineStyle;
	}

	// add stage mode button to admin bar
	public function svnx_addBtnToAdminBar($adminBar) {
		$adminBar->add_menu( array(
			'id'    => 'dev-mode',
			'parent' => false,
			'title' => $this->_stageTitle,
			'href'  => '#',
			'meta'  => array(
				'title' => __($this->_stageTitle),
				'class' => 'stage-mode-style'
			),
		) );
	}

	//add stage mode info to login screen
	public function svnx_stageLoginMessage() {
		$loginMessage = "<p class='message' style='background-color:" . $this->_stageBGColor . " !important; color:#fff; margin-bottom:20px'>" . $this->_stageTitle . __(' Environment') . "</p>";
		echo $loginMessage;
	}

}
//add_action( 'init', array( 'svnx_Dashboard', 'svnx_dashboardInit' ) );
svnx_Dashboard::svnx_dashboardInit();
