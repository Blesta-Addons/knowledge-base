<?php
/**
 * Knowledge base manage plugin language
 */

// Global 
$lang['KnowledgebasePlugin.next_version'] = "This Feature is NOT available in this version."; 
$lang['KnowledgebasePlugin.select.please'] = " --- Please Select --- ";
$lang['KnowledgebasePlugin.select.none'] = "None";

// Success messages
$lang['KnowledgebasePlugin.!success.category_added'] = "The category has been successfully created.";
$lang['KnowledgebasePlugin.!success.category_updated'] = "The category has been successfully updated.";
$lang['KnowledgebasePlugin.!success.category_deleted'] = "The category has been successfully deleted.";
$lang['KnowledgebasePlugin.!success.article_added'] = "The article has been successfully added.";
$lang['KnowledgebasePlugin.!success.article_updated'] = "The article has been successfully updated.";
$lang['KnowledgebasePlugin.!success.article_deleted'] = "The article has been successfully deleted.";

// Modal
$lang['KnowledgebasePlugin.modal.delete_article'] = "Are you sure you want to delete this article?";
$lang['KnowledgebasePlugin.modal.delete_category'] = "Are you sure you want to delete this category? All subcategories and articles within this category will be moved to the parent category.";

// Index
$lang['KnowledgebasePlugin.index.page_title'] = "Knowledge Base > Manage";
$lang['KnowledgebasePlugin.index.boxtitle_Knowledgebase'] = "Knowledge Base";
$lang['KnowledgebasePlugin.index.add_category'] = "Add Category";
$lang['KnowledgebasePlugin.index.add_article'] = "Add Article";

$lang['KnowledgebasePlugin.index.root_categories'] = "Knowledge Base Home";

$lang['KnowledgebasePlugin.index.articles'] = "Articles";
$lang['KnowledgebasePlugin.index.categories'] = "Categories";
$lang['KnowledgebasePlugin.index.settings'] = "Settings";
$lang['KnowledgebasePlugin.index.permissions'] = "Permissions";


// Articles
$lang['KnowledgebasePlugin.articles.page_title'] = "Knowledge Base > Articles";
$lang['KnowledgebasePlugin.articles.boxtitle_articles'] = "Articles";
$lang['KnowledgebasePlugin.articles.no_articles'] = "There are no Article in KB database."; 
$lang['KnowledgebasePlugin.articles.heading_article'] = "ID";
$lang['KnowledgebasePlugin.articles.heading_title'] = "Title";
$lang['KnowledgebasePlugin.articles.heading_category_name'] = "Category";
$lang['KnowledgebasePlugin.articles.heading_date_added'] = "Date Added";
$lang['KnowledgebasePlugin.articles.heading_views'] = "Views";
$lang['KnowledgebasePlugin.articles.heading_options'] = "Options";
$lang['KnowledgebasePlugin.articles.edit'] = "Edit";
$lang['KnowledgebasePlugin.articles.delete'] = "Delete";

// Categories
$lang['KnowledgebasePlugin.categories.page_title'] = "Knowledge Base > Categories";
$lang['KnowledgebasePlugin.categories.boxtitle_categories'] = "Categories";
$lang['KnowledgebasePlugin.categories.no_categories'] = "There are no categories in this section."; 

$lang['KnowledgebasePlugin.categories.heading_category_id'] = "ID";
$lang['KnowledgebasePlugin.categories.heading_parent_id'] = "Parent Category";
$lang['KnowledgebasePlugin.categories.heading_name'] = "Name";
$lang['KnowledgebasePlugin.categories.heading_description'] = "Description";
$lang['KnowledgebasePlugin.categories.heading_options'] = "Options";
$lang['KnowledgebasePlugin.categories.edit'] = "Edit";
$lang['KnowledgebasePlugin.categories.delete'] = "Delete";

// Add category
$lang['KnowledgebasePlugin.addcategory.page_title'] = "Knowledge Base > Add Category";
$lang['KnowledgebasePlugin.addcategory.boxtitle_root'] = "Add Category"; // %1$s is the name of the root directory
$lang['KnowledgebasePlugin.addcategory.boxtitle_addcategory'] = "Add Category"; // %1$s is the name of the category that this category is to be nested under
$lang['KnowledgebasePlugin.addcategory.parent_id'] = "Parent Category";
$lang['KnowledgebasePlugin.addcategory.field_name'] = "Name";
$lang['KnowledgebasePlugin.addcategory.field_description'] = "Description";
$lang['KnowledgebasePlugin.addcategory.submit_add'] = "Create Category";
$lang['KnowledgebasePlugin.addcategory.submit_cancel'] = "Cancel";
$lang['KnowledgebasePlugin.addcategory.select.please'] = "Select Or leave Empty";

// Edit category
$lang['KnowledgebasePlugin.editcategory.page_title'] = "Knowledge Base > Update Category";
$lang['KnowledgebasePlugin.editcategory.boxtitle_editcategory'] = "Update Category [%1\$s]"; // %1$s is the name of the category
$lang['KnowledgebasePlugin.editcategory.field_name'] = "Name";
$lang['KnowledgebasePlugin.editcategory.field_description'] = "Description";
$lang['KnowledgebasePlugin.editcategory.submit_edit'] = "Update Category";
$lang['KnowledgebasePlugin.editcategory.submit_cancel'] = "Cancel";

// Add Article 
$lang['KnowledgebasePlugin.addarticle.page_title'] = "Knowledge Base > Add Article";
$lang['KnowledgebasePlugin.addarticle.boxtitle_addarticle'] = "Add Article"; 
$lang['KnowledgebasePlugin.addarticle.category_id'] = "Category"; 
$lang['KnowledgebasePlugin.addarticle.body'] = "Article Body"; 
$lang['KnowledgebasePlugin.addarticle.title'] = "Article Title"; 
$lang['KnowledgebasePlugin.addarticle.submit_add'] = "Add Article";
$lang['KnowledgebasePlugin.addarticle.submit_cancel'] = "Cancel";
$lang['KnowledgebasePlugin.addarticle.field_description_text'] = "Text";
$lang['KnowledgebasePlugin.addarticle.field_description_html'] = "HTML";

// Edit Article
$lang['KnowledgebasePlugin.editarticle.page_title'] = "Knowledge Base > Edit Article";
$lang['KnowledgebasePlugin.editarticle.boxtitle_editarticle'] = "Edit Article"; 
$lang['KnowledgebasePlugin.editarticle.category_id'] = "Category"; 
$lang['KnowledgebasePlugin.editarticle.body'] = "Article Body"; 
$lang['KnowledgebasePlugin.editarticle.title'] = "Article Title"; 
$lang['KnowledgebasePlugin.editarticle.submit_add'] = "Add Article";
$lang['KnowledgebasePlugin.editarticle.submit_cancel'] = "Cancel";
$lang['KnowledgebasePlugin.editarticle.field_description_text'] = "Text";
$lang['KnowledgebasePlugin.editarticle.field_description_html'] = "HTML";

$lang['KnowledgebasePlugin.editarticle.submit_edit'] = "Update Article";
$lang['KnowledgebasePlugin.editarticle.submit_cancel'] = "Cancel";

// Settings
$lang['KnowledgebasePlugin.settings.page_title'] = "Knowledge Base > Settings";
$lang['KnowledgebasePlugin.settings.boxtitle_settings'] = "Settings"; // %1$s is the name of the category that this category is to be nested under


// Permissions
$lang['KnowledgebasePlugin.permissions.page_title'] = "Knowledge Base > Permissions";
$lang['KnowledgebasePlugin.permissions.boxtitle_permissions'] = "Permissions"; // %1$s is the name of the category that this category is to be nested under


// Tooltips
// $lang['KnowledgebasePlugin.!tooltip.path_to_file'] = "Enter the absolute path to the file on the file system.";

?>