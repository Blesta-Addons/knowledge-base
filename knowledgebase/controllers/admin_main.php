<?php
/**
 * Knowledge Base Plugin 
 * 
 * @package blesta
 * @subpackage blesta.plugins.Knowledgebase
 * @copyright Copyright (c) 2005, Naja7host SARL.
 * @link http://www.naja7host.com/ Naja7host
 */
 
class AdminMain extends AppController {

    /**
     * Performs necessary initialization
     */
	
	/**
	 * Returns the view to be rendered when managing this plugin
	 */
	 
    private function init() {
        // Require login
        $this->requireLogin();

		Language::loadLang("knowledgebase_plugin", null, PLUGINDIR . "knowledgebase" . DS . "language" . DS);

		$this->uses(array("Knowledgebase.KnowledgebaseCategories", "Knowledgebase.KnowledgebaseArticles", "Knowledgebase.Knowledgebase"));
		
        // Set the company ID
        $this->company_id = Configure::get("Blesta.company_id");
		
		// Restore structure view location of the admin portal
		$this->structure->setDefaultView(APPDIR);
		$this->structure->setView(null, $this->structure->view);
		
		$this->total_articles = $this->Knowledgebase->getListCount($this->company_id, "kb_articles") ; 
		$this->total_categories = $this->Knowledgebase->getListCount($this->company_id, "kb_categories") ; 
		
		$language = Language::_("KnowledgebasePlugin." . Loader::fromCamelCase($this->action ? $this->action : "index") . ".page_title", true);
		$this->structure->set("page_title", $language);
		
    }
	
    public function index() {
		$this->init();
		$this->set("status", "settings");
		$this->set("articles", $this->total_articles);
		$this->set("categories", $this->total_categories);
		$this->view->setView(null, "Knowledgebase.default");		
    }

	/**
	 * Articles
	 */
	public function articles() {
	
		$this->init();
		$page = (isset($this->get[0]) ? (int)$this->get[0] : 1);
		$sort = (isset($this->get['sort']) ? $this->get['sort'] : "date_added");
		$order = (isset($this->get['order']) ? $this->get['order'] : "desc");
		
		$articles = $this->KnowledgebaseArticles->getAllArticles($this->company_id , $page ) ;

		$vars = array();
		
		$this->set("status", "articles");
		$this->set("lesarticles", $articles);
		$this->set("articles", $this->total_articles);
		$this->set("categories", $this->total_categories);		
		$this->set("vars", $vars);
		$this->view->setView(null, "Knowledgebase.default");

		// Set pagination parameters, set group if available
		$params = array('sort'=>$sort,'order'=>$order);
		
		// Overwrite default pagination settings
		$settings = array_merge(Configure::get("Blesta.pagination"), array(
				'total_results' => $this->total_articles,
				'uri'=> $this->base_uri . "plugin/knowledgebase/admin_main/articles/[p]/",
				'params'=>$params
			)
		);
		$this->helpers(array("Pagination"=>array($this->get, $settings)));
		$this->Pagination->setSettings(Configure::get("Blesta.pagination_ajax"));		
		
		if ($this->isAjax())
			return $this->renderAjaxWidgetIfAsync(isset($this->get[0]) || isset($this->get['sort']));
	
	}		
	
	/**
	 * Settings
	 */
	public function settings() {
		$this->init();
		$this->set("status", "settings");
		$this->set("articles", $this->total_articles);
		$this->set("categories", $this->total_categories);
		$this->view->setView(null, "Knowledgebase.default");			
	}	

	/**
	 * permissions
	 */
	public function permissions() {
		$this->init();
		$this->set("status", "permissions");
		$this->set("articles", $this->total_articles);
		$this->set("categories", $this->total_categories);
		$this->view->setView(null, "Knowledgebase.default");				
	}
	
	/**
	 * categories
	 */
	public function categories() {
		$this->init();
		
		$list_cat = $this->Knowledgebase->buildTree($this->KnowledgebaseCategories->getAllCategories($this->company_id)) ; 
		
		$this->set("lescategories", $list_cat);
		$this->set("status", "categories");
		$this->set("articles", $this->total_articles);
		$this->set("categories", $this->total_categories);
		$this->view->setView(null, "Knowledgebase.default");				
	}
	
	/**
	 * Add a category
	 */
	public function addCategory() {
		$this->init();
	
		$parent_id = $this->Form->collapseObjectArray($this->KnowledgebaseCategories->getAll($this->company_id, $current_category = null), "name", "id") ;
		$vars = array(
			'parent_id' => $parent_id ,
			'vars' => (object)array('parent_id' => (isset($current_category->id ) ? $current_category->id : null))
		);
		
		if (!empty($this->post)) {
			$data = array_merge($this->post, (array)$vars['vars']);
			$data['company_id'] = $this->company_id;

			$category = $this->KnowledgebaseCategories->add($data);
			if (($errors = $this->KnowledgebaseCategories->errors())) {
				// Error, reset vars
				$vars['vars'] = (object)$this->post;
				$this->setMessage("error", $errors, false, null, false);
			}
			else {
				// Success
				$this->flashMessage("message", Language::_("KnowledgebasePlugin.!success.category_added", true), null, false);
				$this->redirect($this->base_uri . "plugin/knowledgebase/admin_main/addcategory/");
			}
			
		}
		
		$this->set("category", $current_category);
		$this->set("parent_id", $parent_id);
		
		$this->set("vars", $vars);
		$this->view->setView(null, "Knowledgebase.default");

	}	

	/**
	 * Edit a category
	 */
	public function editCategory() {
		$this->init();
		
		if (!isset($this->get[0]) || !($category = $this->KnowledgebaseCategories->get($this->get[0])) ||
			($category->company_id != $this->company_id))
			$this->redirect($this->base_uri . "plugin/knowledgebase/admin_main/");
		
		$list_cat = $this->KnowledgebaseCategories->getAllCategories($this->company_id) ; 
		for($i = 0; $i < count($list_cat); $i++){
			if (!empty($list_cat[$i]->parent_id))
				$list_cat[$i]->name = " -- " .$list_cat[$i]->name  ." (sub) ";
		}			
	
		$parent_id = $this->Form->collapseObjectArray($list_cat, "name", "id") ;

		$vars = array(
			'parent_id' => $parent_id ,
			'vars' => $category
		);
		
		if (!empty($this->post)) {
			// Update the category
			
			// Set initial vars
			if (empty($this->post['parent_id']))
				$this->post['parent_id'] = null ;
			
			$data = $this->post;
			$data['company_id'] = $this->company_id;
			// print_r($data);
			$category = $this->KnowledgebaseCategories->edit($category->id, $data);

			if (($errors = $this->KnowledgebaseCategories->errors())) {
				// Error, reset vars
				$vars['vars'] = (object)$this->post;
				$this->setMessage("error", $errors, false, null, false);
				
			}
			else {
				// Success				
				$this->flashMessage("message", Language::_("KnowledgebasePlugin.!success.category_updated", true));
				$this->redirect($this->base_uri . "plugin/knowledgebase/admin_main/categories/");
			}
		}
		
		// Set initial vars
		if (empty($vars['vars']))
			$vars['vars'] = $category;
		
		// $this->set("parent_id", $parent_id);
		
		$this->set("vars", $vars);
		$this->view->setView(null, "Knowledgebase.default");
	}	

	/**
	 * Delete category
	 */
	public function deleteCategory() {
		$this->init();
		if (!isset($this->post['id']) || !($category = $this->KnowledgebaseCategories->get($this->post['id'])) ||
			($category->company_id != $this->company_id))
			$this->redirect($this->base_uri . "plugin/knowledgebase/admin_main/");

		$this->KnowledgebaseCategories->delete($category->id);
		
		$this->flashMessage("message", Language::_("KnowledgebasePlugin.!success.category_deleted", true));
		$this->redirect($this->base_uri . "plugin/knowledgebase/admin_main/categories/");		

		
	}		

	/**
	 * Add a Article
	 */
	public function addArticle() {
		$this->init();
		
		$list_cat = $this->KnowledgebaseCategories->getAllCategories($this->company_id) ; 
		for($i = 0; $i < count($list_cat); $i++){
			if (!empty($list_cat[$i]->parent_id))
				$list_cat[$i]->name = " -- " .$list_cat[$i]->name  ." (sub) ";
		}			
	
		$category_id = $this->Form->collapseObjectArray($list_cat, "name", "id") ;		
		
		// Set vars
		$vars = array(
			'category_id' => $category_id 
		);
		
		if (!empty($this->post)) {
			// Set the category this file is to be added in
			$data = array(
				'company_id' => $this->company_id
			);
			
			$data = array_merge($this->post, $data);

			$this->KnowledgebaseArticles->add($data);
			
			if (($errors = $this->KnowledgebaseArticles->errors())) {
				// Error, reset vars
				$vars['vars'] = (object)$this->post;
				$this->setMessage("error", $errors, false, null, false);
			}
			else {
				// Success
				$this->flashMessage("message", Language::_("KnowledgebasePlugin.!success.article_added", true));
				$this->redirect($this->base_uri . "plugin/knowledgebase/admin_main/articles/");
			}
		}
		

		
		// Set the view to render
		$this->set("vars", $vars);
		$this->view->setView(null, "Knowledgebase.default");

		// Include WYSIWYG
		$this->Javascript->setFile("ckeditor/ckeditor.js", "head", VENDORWEBDIR);
		$this->Javascript->setFile("ckeditor/adapters/jquery.js", "head", VENDORWEBDIR);
		
	}
	
	/**
	 * Edit Article
	 */
	public function editArticle() {
		$this->init();
		
		if (!isset($this->get[0]) || !($article = $this->KnowledgebaseArticles->get($this->get[0])) ||
			($article->company_id != $this->company_id))
			$this->redirect($this->base_uri . "plugin/knowledgebase/admin_main/");
			
		
		$list_cat = $this->KnowledgebaseCategories->getAllCategories($this->company_id) ; 
		for($i = 0; $i < count($list_cat); $i++){
			if (!empty($list_cat[$i]->parent_id))
				$list_cat[$i]->name = " -- " .$list_cat[$i]->name  ." (sub) ";
		}			
	
		$category_id = $this->Form->collapseObjectArray($list_cat, "name", "id") ;		
		
		// Set vars
		$vars = array(
			'category_id' => $category_id ,
			'vars' => $article
		);
		
		if (!empty($this->post)) {
			// Set the category this file is to be added in
			$data = array(
				'company_id' => $this->company_id
			);
			
			$data = array_merge($this->post, $data);

			$this->KnowledgebaseArticles->edit($article->id, $data);
			
			if (($errors = $this->KnowledgebaseArticles->errors())) {
				// Error, reset vars
				$vars['vars'] = (object)$this->post;
				$this->setMessage("error", $errors, false, null, false);
			}
			else {
				// Success
				$this->flashMessage("message", Language::_("KnowledgebasePlugin.!success.article_updated", true));
				$this->redirect($this->base_uri . "plugin/knowledgebase/admin_main/articles/");
			}
	
		}
		// Set initial vars
		if (empty($vars['vars']))
			$vars['vars'] = $article;
		
		$this->set("vars", $vars);
		$this->view->setView(null, "Knowledgebase.default");
		
		// Include WYSIWYG
		$this->Javascript->setFile("ckeditor/ckeditor.js", "head", VENDORWEBDIR);
		$this->Javascript->setFile("ckeditor/adapters/jquery.js", "head", VENDORWEBDIR);		
		
	}	
		
	/**
	 * Delete Article
	 */
	public function deleteArticle() {
		$this->init();

		if (!isset($this->post['id']) || !($article = $this->KnowledgebaseArticles->get($this->post['id'])) ||
			($article->company_id != $this->company_id))
			$this->redirect($this->base_uri . "plugin/knowledgebase/admin_main/");

		$this->KnowledgebaseArticles->delete($article->id);
		
		$this->flashMessage("message", Language::_("KnowledgebasePlugin.!success.article_deleted", true));
		$this->redirect($this->base_uri . "plugin/knowledgebase/admin_main/articles/");			
	}			
}
?>