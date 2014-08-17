<?php
/**
 * Knowledge Base Plugin 
 * 
 * @package blesta
 * @subpackage blesta.plugins.Knowledgebase
 * @copyright Copyright (c) 2005, Naja7host SARL.
 * @link http://www.naja7host.com/ Naja7host
 */

class Knowledgebase extends knowledgebaseModel {
	
	/**
	 * Initialize
	 */
	public function __construct() {
		parent::__construct();
		
		Language::loadLang("knowledgebase_categories", null, PLUGINDIR . "knowledgebase" . DS . "language" . DS);
	}
	
	/**
	 * Return the total number of rows,
	 */
	public function getListCount($company_id , $table = null) {
		return $this->Record->select()->from($table)->
			// where("parent_id", "=", $parent_id)->
			where("company_id", "=", $company_id)->
			numResults();
	}	
	
	/**
	 * Fetches all sub categories
	 */
	public function getAllSub($company_id, $parent_id = null) {
		$this->Record = $this->Record->select()->from("kb_categories");
			
		return $this->Record->where("parent_id", "!=", $parent_id)->
			where("company_id", "=", $company_id)->
			order(array('name'=>"ASC"))->fetchAll();
	}		

	/**
	 * Fetches all categories
	 */
	public function getAll($company_id) {
		$fields = array("id" , "parent_id", "name");
		return $this->Record->select($fields)->from("kb_categories")->
			//where("parent_id", "=", $parent_id)->
			where("company_id", "=", $company_id)->
			order(array('id'=>"ASC"))->fetchAll();
	}

	/**
	 * Build Sub child Array
	 *
	 */	

	public function buildTree(array $elements, $parentId = 0) {
		$branch = array();

		foreach ($elements as $element) {
			if ($element->parent_id == $parentId) {
				$children = $this->buildTree($elements, $element->id);
				if ($children) {
					$element->children = $children;
				}
				$branch[] = $element;
			}
		}

		return $branch;
	}
	
}
?>