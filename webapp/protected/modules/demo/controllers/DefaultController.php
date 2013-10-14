<?php

class DefaultController extends Controller
{
    public $layout = '/layouts/demo-main';
	public function actionIndex()
	{
		$this->render('index');
	}
}