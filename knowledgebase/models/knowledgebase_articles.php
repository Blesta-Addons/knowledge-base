<?php
/**
 * Knowledge Base Plugin 
 * 
 * @package blesta
 * @subpackage blesta.plugins.Knowledgebase
 * @copyright Copyright (c) 2005, Naja7host SARL.
 * @link http://www.naja7host.com/ Naja7host
 */

class KnowledgebaseArticles extends knowledgebaseModel {
	
	/**
	 * Initialize
	 */
	public function __construct() {
		parent::__construct();
		
	
		Language::loadLang("knowledgebase_articles", null, PLUGINDIR . "knowledgebase" . DS . "language" . DS);
	}
	
	/**
	 * Retrieves a list of rules to validate add/editing files
	 */
	private function getRules(array $vars, $edit = false) {
		$rules = array(
			'category_id' => array(
				'exists' => array(
					'if_set' => true,
					'rule' => array(array($this, "validateExists"), "id", "kb_categories"),
					'message' => $this->_("knowledgebaseArticles.!error.category_id.exists")
				)
			),
			'company_id' => array(
				'exists' => array(
					'rule' => array(array($this, "validateExists"), "id", "companies"),
					'message' => $this->_("knowledgebaseArticles.!error.company_id.exists")
				)
			),
			'title' => array(
				'empty' => array(
					'rule' => "isEmpty",
					'negate' => true,
					'message' => $this->_("knowledgebaseArticles.!error.title.empty")
				)
			),
			'body' => array(
				'empty' => array(
					'rule' => "isEmpty",
					'negate' => true,
					'message' => $this->_("knowledgebaseArticles.!error.body.empty")
				)
			)
		);
		
		if ($edit) {
			// Update rules, check that the file exists
			$rules['id'] = array(
				'exists' => array(
					'rule' => array(array($this, "validateExists"), "id", "kb_articles "),
					'message' => $this->_("knowledgebaseArticles.!error.file_id.exists")
				)
			);
		}
		
		return $rules;
	}

	/**
	 * Fetches the article
	 */ 
	public function get($article_id) {
		$article = $this->Record->select()->from("kb_articles")->where("id", "=", $article_id)->fetch();
				
		return $article;
	}
	
	/**
	 * Fetches all articles within a specific category
	 */
	public function getAll($company_id, $category_id = null) {
		$articles = $this->Record->select()->from("kb_articles")->
			where("category_id", "=", $category_id)->fetchAll();
		
		return $articles;
	}
	
	/**
	 * Fetches all articles
	 */
	public function getAllArticles($company_id , $page=1 , $order_by=array('date_added'=>"ASC")) {
	
		$fields = array(
			"kb_articles.*",
			"kb_categories.name"=>"category_name" , "kb_categories.id"
		);
		
		return $this->Record->select($fields)->from("kb_articles")->
			leftJoin("kb_categories", "kb_articles.category_id", "=", "kb_categories.id", false)->
			where("kb_articles.company_id", "=", $company_id)->
			order($order_by)->
			limit($this->getPerPage(), (max(1, $page) - 1)*$this->getPerPage())->fetchAll();
	}

	/**
	 * Fetches latest & popular 
	 */
	public function getLatestPopular($company_id , $page=1 , $order_by) {
	
		return $this->Record->select()->from("kb_articles")->
			where("company_id", "=", $company_id)->
			order($order_by)->
			limit(10)->fetchAll();
	}	
	
	/**
	 * Create a Article in KB 
	 */
	public function add(array $vars) {
		
		$vars['date_added'] = date("c");			
		
		$this->Input->setRules($this->getRules($vars));
		
		
		
		if ($this->Input->validates($vars)) {		
			$fields = array("category_id", "company_id", "title", "body", "date_added");
			$this->Record->insert("kb_articles", $vars, $fields);
		}
	}
	
	/**
	 * Edit a Article in KB 
	 */
	public function edit($article , array $vars) {
		// $vars['date_added'] = date("Y-m-d H:i:s");			
		$this->Input->setRules($this->getRules($vars));
				
		if ($this->Input->validates($vars)) {		
			$fields = array("category_id", "company_id", "title", "body");
			$this->Record->where("id", "=", $article)->update("kb_articles", $vars, $fields);
		}
	}	
				
	/**
	 * Deletes a Article in KB 
	 */
	public function delete($id) {
		$article = $this->get($id);
		
		// Delete the category
		if ($article) 		
			$this->Record->from("kb_articles")->where("id", "=", $id)->delete();
	}
	
	/**
	 * Edit a Article in KB 
	 */
	public function view($article) {
		$this->Record->set("views","views+1", false, false)->where("id", "=", $article)->update("kb_articles");
	}	
}
?>