<?php

class TodoCommentsController {
	private $twig;
	private $get;
	private $post;

	public function __construct(Twig_Environment $twig, array $get, array $post) {
		$this->twig = $twig;
		$this->get = $get;
		$this->post = $post;
	}

	public function indexAction() {
		// Get selected todo
		$todo = new todo;
        $todo = $todo->Get($this->get['id']);
		
		//Get all Comments
        $todoComments = new todoComments;
        $todoComments= $todoComments->GetByTodoId($this->get['id']);

		// Get template
		$template = $this->getTemplate('todo_comments_index');

		// Render template
		return $template->render(array('todo' => $todo,'todocomments'=>$todoComments));
	}

	/* Comment: add methods for newAction, deleteAction, modifyAction ... */

	//Add new Comment
	public function addNewCommentAction() {
		$todoComments = new todoComments();
		$todoComments->SaveNew($_POST['comment'],$this->get['id']);
		return $this->indexAction();
	}

	//Delete Comment
	public function deleteAction() {
		$todoComments = new todoComments;

		$todoComments = $todoComments->Get($this->get['deleteId']);
		
		$todoComments->Delete();

		return $this->indexAction();
		
	}


	protected function getTemplate($name) {
		return $this->twig->loadTemplate($name . '.twig');
	}
}

?>