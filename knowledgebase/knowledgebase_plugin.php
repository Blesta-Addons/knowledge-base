<?php
/**
 * Knowledge Base Plugin 
 * 
 * @package blesta
 * @subpackage blesta.plugins.Knowledgebase
 * @copyright Copyright (c) 2005, Naja7host SARL.
 * @link http://www.naja7host.com/ Naja7host
 */
 
class KnowledgebasePlugin extends Plugin {

	public function __construct() {
		Language::loadLang("knowledgebase", null, dirname(__FILE__) . DS . "language" . DS);
		
		// Load components required by this plugin
		Loader::loadComponents($this, array("Input", "Record"));
		
        // Load modules for this plugun
        Loader::loadModels($this, array("ModuleManager"));
		$this->loadConfig(dirname(__FILE__) . DS . "config.json");
	}
	
	/**
	 * Performs any necessary bootstraping actions
	 *
	 * @param int $plugin_id The ID of the plugin being installed
	 */
	public function install($plugin_id) {	
			
		// Add the system overview table, *IFF* not already added
		try {
			// Category  table
			$this->Record->
				setField("id", array('type'=>"int", 'size'=>10, 'unsigned'=>true, 'auto_increment'=>true))->		
				setField("parent_id", array('type'=>"int", 'size'=>10, 'unsigned'=>true, 'is_null'=>true, 'default'=>null))->				
				setField("name", array('type'=>"varchar", 'size'=>255))->
				setField("description", array('type'=>"varchar", 'size'=>255))->
				setField("icon", array('type'=>"varchar", 'size'=>255))->
				setField("company_id", array('type'=>"int", 'size'=>10, 'unsigned'=>true))->				
				setKey(array("id"), "primary")->
				setKey(array("parent_id"), "index")->
				setKey(array("company_id"), "index")->
				create("kb_categories", true);

			// articles  table
			$this->Record->
				setField("id", array('type'=>"int", 'size'=>10, 'unsigned'=>true, 'auto_increment'=>true))->		
				setField("category_id", array('type'=>"int", 'size'=>10, 'unsigned'=>true, 'is_null'=>true, 'default'=>null))->				
				setField("title", array('type'=>"varchar", 'size'=>255))->
				setField("body", array('type'=>"text"))->
				setField("icon", array('type'=>"varchar", 'size'=>255))->				
				setField("date_added", array('type'=>"datetime"))->				
				setField("views", array('type'=>"int", 'size'=>10, 'unsigned'=>true, 'default'=>0))->				
				setField("company_id", array('type'=>"int", 'size'=>10, 'unsigned'=>true))->				
				setKey(array("id"), "primary")->
				setKey(array("category_id"), "index")->
				setKey(array("company_id"), "index")->
				create("kb_articles", true);				
					
		}
		catch(Exception $e) {
			// Error adding... no permission?
			$this->Input->setErrors(array('db'=> array('create'=>$e->getMessage())));
			return;
		}
	}
	
    /**
     * Performs migration of data from $current_version (the current installed version)
     * to the given file set version
     *
     * @param string $current_version The current installed version of this plugin
     * @param int $plugin_id The ID of the plugin being upgraded
     */
	public function upgrade($current_version, $plugin_id) {
		
		// Upgrade if possible
		if (version_compare($this->getVersion(), $current_version, ">")) {
			// Handle the upgrade, set errors using $this->Input->setErrors() if any errors encountered
		}
	}
	
    /**
     * Performs any necessary cleanup actions
     *
     * @param int $plugin_id The ID of the plugin being uninstalled
     * @param boolean $last_instance True if $plugin_id is the last instance across all companies for this plugin, false otherwise
     */
	public function uninstall($plugin_id, $last_instance) {
		if (!isset($this->Record))
			Loader::loadComponents($this, array("Record"));
		
		// Remove all tables *IFF* no other company in the system is using this plugin
		if ($last_instance) {
			try {
				$this->Record->drop("kb_categories");
				$this->Record->drop("kb_articles");
			}
			catch (Exception $e) {
				// Error dropping... no permission?
				$this->Input->setErrors(array('db'=> array('create'=>$e->getMessage())));
				return;
			}
		}
 
	}

	
	/**
	 * Returns all actions to be configured for this widget (invoked after install() or upgrade(), overwrites all existing actions)
	 *
	 * @return array A numerically indexed array containing:
	 * 	-action The action to register for
	 * 	-uri The URI to be invoked for the given action
	 * 	-name The name to represent the action (can be language definition)
	 */
	public function getActions() {
		return array(
			array(
				'action'=>"nav_primary_client",
				'uri'=>"plugin/knowledgebase/client_main/",
				'name'=>Language::_("KnowledgebasePlugin.client_main", true)
			),
			array(
				'action' => "nav_secondary_staff",
				'uri' => "plugin/knowledgebase/admin_main/",
				'name' => Language::_("KnowledgebasePlugin.admin_main", true),
				'options' => array('parent' => "tools/")
			)			
		);
	}

	
	/**
	 * Execute the cron task
	 *
	 */

	public function cron($key) {
		// Todo a task 
	}

	
	/**
	 * Attempts to add new cron tasks for this plugin
	 *
	 */

	private function addCronTasks(array $tasks) {
		// TODO
	}	
	
}
?>