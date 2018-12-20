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
		// Get selected todos
		$todo = new todo;
        $todo = $todo->Get($this->get['id']);
        
        $todoComments = new todoComments;
        $todoComments= $todoComments->GetList();

		// Get template
		$template = $this->getTemplate('todo_comments_index');

		// Render template
		return $template->render(array('todo' => $todo,'todoComments'=>$todoComments));
	}

	/* TODO: add methods for newAction, deleteAction, modifyAction ... */

	//Add new ToDo
	public function addNewCommentAction() {
		$todoComments = new todoComments();
		
		$todoComments->SaveNew($_POST['comment'],$this->get['id']);
		return $this->indexAction();
	}

	//Delete Todo
	public function deleteAction() {
		$todo = new todo;
		$todo = $todo->Get($this->get['id']);
		$todo->done = 1;
		$todo->Delete();

		return $this->indexAction();
		
	}


	protected function getTemplate($name) {
		return $this->twig->loadTemplate($name . '.twig');
	}
}

?>