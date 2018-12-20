<?php

class TodoController {
	private $twig;
	private $get;
	private $post;

	public function __construct(Twig_Environment $twig, array $get, array $post) {
		$this->twig = $twig;
		$this->get = $get;
		$this->post = $post;
	}

	public function indexAction() {
		// Get all todos
		$todo = new todo;
		$todos = $todo->GetList();
		// Filter by passing an argument as array(array('done', '=', '0')) to
		// GetList. This would for example only select todos that are done.

		// Get template
		$template = $this->getTemplate('todo_index');

		// Render template
		return $template->render(array('todos' => $todos));
	}

	public function markAsDoneAction() {
		$todo = new todo;
		$todo = $todo->Get($this->get['id']);
		$todo->done = 1;
		$todo->Save();

		return $this->indexAction();
	}

	/* TODO: add methods for newAction, deleteAction, modifyAction ... */

	//Add new ToDo
	public function addNewToDoAction() {
		$todo = new todo();
		
		$todo->SaveNew($_POST['name']);
		return $this->indexAction();
	}

	//Delete Todo
	public function deleteAction() {
		$todo = new todo;
		$todo = $todo->Get($this->get['id']);
		$todo->Delete();

		return $this->indexAction();
		
	}


	protected function getTemplate($name) {
		return $this->twig->loadTemplate($name . '.twig');
	}
}

?>