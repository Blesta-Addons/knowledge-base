<?php
/**
 * Knowledge Base Plugin 
 * 
 * @package blesta
 * @subpackage blesta.plugins.Knowledgebase
 * @copyright Copyright (c) 2005, Naja7host SARL.
 * @link http://www.naja7host.com/ Naja7host
 */

class KnowledgebaseCategories extends knowledgebaseModel {
	
	/**
	 * Initialize
	 */
	public function __construct() {
		parent::__construct();
		
		Language::loadLang("knowledgebase_categories", null, PLUGINDIR . "knowledgebase" . DS . "language" . DS);
	}
	
	/**
	 * Fetches a specific category
	 */
	public function get($category_id) {
		return $this->getCategories()->where("id", "=", $category_id)->fetch();
	}

	/**
	 * Partially constructs a Record object for fetching categories
	 */
	private function getCategories() {
		return $this->Record->select()->from("kb_categories");
	}	

	/**
	 * Fetches all categories
	 */
	public function getAll($company_id, $parent_id = null) {
		$this->Record = $this->getCategories();
			
		return $this->Record->where("parent_id", "=", $parent_id)->
			where("company_id", "=", $company_id)->
			order(array('id'=>"ASC"))->fetchAll();
	}
	
	/**
	 * Fetches all categories
	 */
	public function getAllCategories($company_id) {
		$this->Record = $this->getCategories();
			
		return $this->Record->where("company_id", "=", $company_id)->
			order(array('id'=>"ASC"))->fetchAll();
	}	
	
	/**
	 * Fetches all sub categories
	 */
	public function getAllSub($company_id, $parent_id = null) {
		$this->Record = $this->getCategories();
			
		return $this->Record->where("parent_id", "!=", null)->
			where("company_id", "=", $company_id)->
			order(array('name'=>"ASC"))->fetchAll();
	}		
	

	/**
	 * Creates a category
	 */
	public function add(array $vars) {
		$this->Input->setRules($this->getRules($vars));
		
		if ($this->Input->validates($vars)) {
			// Create the category
			$fields = array("parent_id", "company_id", "name", "description");
			$this->Record->insert("kb_categories", $vars, $fields);
			
			return $this->get($this->Record->lastInsertId());
		}
	}
	
	/**
	 * Updates a category
	 */
	public function edit($category_id, array $vars) {
		$vars['category_id'] = $category_id;
		$this->Input->setRules($this->getRules($vars, true));
		
		if ($this->Input->validates($vars)) {
			// Update the category
			$fields = array("parent_id", "company_id", "name", "description");
			$this->Record->where("id", "=", $category_id)->update("kb_categories", $vars, $fields);
			
			return $this->get($category_id);
		}
	}
	
	/**
	 * Deletes the category and moves all child categories to this categories' parent
	 * along with this categories' articles
	 */
	public function delete($category_id) {
		// Get the category
		$category = $this->get($category_id);
		
		// Delete the category
		if ($category) {
			// Begin a transaction
			$this->Record->begin();
			
			// Update all children of this category to be in the parent category
			$this->Record->where("parent_id", "=", $category->id)->
				update("kb_categories", array('parent_id'=>$category->parent_id));
			
			// Update files in this category to be in the parent category
			$this->Record->where("category_id", "=", $category->id)->
				update("kb_articles", array('category_id'=>$category->parent_id));
			
			// Finally, delete this category
			$this->Record->from("kb_categories")->where("id", "=", $category->id)->delete();
			
			// Commit the transaction
			$this->Record->commit();
		}
	}
	
	/**
	 * Retrieves all parent categories for the given category, including the given category
	 */
	public function getAllParents($category_id, array $exclude = array()) {
		// Get this category
		$category = $this->get($category_id);
		
		// Get parents for this category and avoid infinite loop
		if ($category->parent_id !== null && !in_array($category->parent_id, $exclude))
			return array_merge($this->getAllParents($category->parent_id, array_merge($exclude, array($category->id))), array($category));
		
		return array($category);
	}
	
	/**
	 * Retrieves a list of rules to validate add/editing categories
	 */
	private function getRules(array $vars, $edit = false) {
		$rules = array(
			'parent_id' => array(
				'exists' => array(
					'if_set' => true,
					'rule' => array(array($this, "validateExists"), "id", "kb_categories"),
					'message' => $this->_("KnowledgebasePlugin.!error.parent_id.exists")
				)
			),
			'company_id' => array(
				'exists' => array(
					'rule' => array(array($this, "validateExists"), "id", "companies"),
					'message' => $this->_("KnowledgebasePlugin.!error.company_id.exists")
				)
			),
			'name' => array(
				'empty' => array(
					'rule' => "isEmpty",
					'negate' => true,
					'message' => $this->_("KnowledgebasePlugin.!error.name.empty")
				)
			),
			'description' => array(
				'empty' => array(
					'rule' => "isEmpty",
					'negate' => true,
					'message' => $this->_("KnowledgebasePlugin.!error.description.empty")
				)
			)
		);
		
		if ($edit) {
			// Update rules
			$rules['category_id'] = array(
				'exists' => array(
					'rule' => array(array($this, "validateExists"), "id", "kb_categories"),
					'message' => $this->_("KnowledgebasePlugin.!error.category_id.exists")
				)
			);
			
			// Make sure the parent does not reference itself
			// $rules['parent_id']['loop'] = array(
				// 'if_set' => true,
				// 'rule' => array("matches", "!=", $this->ifSet($vars['category_id'])),
				// 'message' => $this->_("KnowledgebasePlugin.!error.parent_id.loop")
			// );
		}
		
		return $rules;
	}
}
?>