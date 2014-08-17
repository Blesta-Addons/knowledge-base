<?php
/**
 * Knowledge Base Plugin 
 * 
 * @package blesta
 * @subpackage blesta.plugins.Knowledgebase
 * @copyright Copyright (c) 2005, Naja7host SARL.
 * @link http://www.naja7host.com/ Naja7host
 */
class ClientMain extends knowledgebaseController {

	/**
	 * Pre-action
	 */
	public function preAction() {
		parent::preAction();

		$this->company_id = (isset($this->client->company_id) ? $this->client->company_id : Configure::get("Blesta.company_id"));
		
		Language::loadLang("client_main", null, PLUGINDIR . "knowledgebase" . DS . "language" . DS);

		$this->uses(array("Knowledgebase.KnowledgebaseCategories", "Knowledgebase.KnowledgebaseArticles", "Knowledgebase.Knowledgebase"));
		
		// Restore structure view location of the client portal
		$this->structure->setDefaultView(APPDIR);
		$this->structure->setView(null, $this->orig_structure_view);

		$this->total_articles = $this->Knowledgebase->getListCount($this->company_id, "kb_articles") ; 
		$this->total_categories = $this->Knowledgebase->getListCount($this->company_id, "kb_categories") ; 

	}


	/**
	 * List categories/files
	 */
	public function index() {
		
		$page = (isset($this->get[0]) ? (int)$this->get[0] : 1);
		
		$list_cat = $this->Knowledgebase->buildTree($this->KnowledgebaseCategories->getAllCategories($this->company_id)) ; 
		$latest = $this->KnowledgebaseArticles->getLatestPopular($this->company_id , $page , $order_by=array('date_added'=>"desc")) ;
		$popular = $this->KnowledgebaseArticles->getLatestPopular($this->company_id , $page , $order_by=array('views'=>"desc")) ;
		
		// Load the Text Parser
		$this->helpers(array("TextParser"));
		$this->helpers(array("Date"));

		$this->set("latestarticles", $latest);
		$this->set("populararticles", $popular);
		$this->set("lescategories", $list_cat);
		$this->set("articles", $this->total_articles);
		$this->set("categories", $this->total_categories);
		$this->view->setView(null, "Knowledgebase.default");	

	}
	
	/**
	 * list articles and categories
	 */
	public function category() {

		$parent_cat = null ;
		// Load the Text Parser
		$this->helpers(array("TextParser"));
		$this->helpers(array("Date"));
		
		$list_cat = $this->Knowledgebase->buildTree($this->KnowledgebaseCategories->getAllCategories($this->company_id)) ; 
		$sub_cat = $this->KnowledgebaseCategories->getAll($this->company_id , $this->get[0] ) ; //getAll sub categories($company_id, $parent_id = null)
		$category = $this->KnowledgebaseCategories->get($this->get[0] ) ; //getAll categories($company_id, $parent_id = null)
		$articles = $this->KnowledgebaseArticles->getAll($this->company_id , $this->get[0] ) ; //getAll articles($company_id, $parent_id = null)

		if (!empty($category->parent_id))
			$parent_cat = $this->KnowledgebaseCategories->get($category->parent_id ) ; //getAll categories($company_id, $parent_id = null)
		
		$this->set("lesarticles", $articles);
		$this->set("sub_cat", $sub_cat);
		$this->set("parent_cat", $parent_cat);
		$this->set("category", $category);
		$this->set("lescategories", $list_cat);

		$this->view->setView(null, "Knowledgebase.default");	
		$this->structure->set("page_title", Language::_("ClientMain.category.page_title", true , $this->Html->ifSet($category->name)));	
	}
	
	/**
	 * Download a file
	 */
	public function article() {
		// Ensure a file ID was provided
		$parent_cat = null ;
		// Load the Text Parser
		$this->helpers(array("TextParser"));
		$this->helpers(array("Date"));
		
		$list_cat = $this->Knowledgebase->buildTree($this->KnowledgebaseCategories->getAllCategories($this->company_id)) ; 
		$sub_cat = $this->KnowledgebaseCategories->getAll($this->company_id , $this->get[0] ) ; //getAll sub categories($company_id, $parent_id = null)

		$article = $this->KnowledgebaseArticles->get($this->get[0] ) ; //getAll articles($company_id, $parent_id = null)
		$category = $this->KnowledgebaseCategories->get($article->category_id ) ; //getAll categories($company_id, $parent_id = null)
		
		if (!empty($category->parent_id))
			$parent_cat = $this->KnowledgebaseCategories->get($category->parent_id ) ; //getAll categories($company_id, $parent_id = null)
			
		$this->KnowledgebaseArticles->view($article->id) ;
		
		$this->set("article", $article);
		$this->set("sub_cat", $sub_cat);
		$this->set("parent_cat", $parent_cat);
		$this->set("category", $category);
		$this->set("lescategories", $list_cat);

		$this->view->setView(null, "Knowledgebase.default");
		$this->structure->set("page_title", Language::_("ClientMain.article.page_title", true , $this->Html->ifSet($article->title)));	
	}

}
?>