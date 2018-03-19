<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('Answer_model');
	}

	public function index()	{
		$this->load->view('admin/index');
	}

	public function loadAnswers() {
		$response = [];
		$answers = $this->Answer_model->loadAnswers();
		foreach ($answers as $answer) {
			if(empty($response[$answer['ip_address']])) {
				$response[$answer['ip_address']] = ['count' => 1];
			} else {
				$response[$answer['ip_address']]['count'] += 1;
			}

			$response[$answer['ip_address']]['q'.$answer['question_id']][] = $answer['correct'];
		}

		foreach($response as $ip => &$data) {
			foreach($data as $key => &$question) {
				if($key == 'count') {
					$data['count'] /= 3;
				} else {
					$question = array_sum($question) / count($question);
				}
			}

			$data['ip_address'] = $ip;
		}

		$response = array_values($response);
		header('Content-Type: application/json');
		echo json_encode($response); exit;
	}

	public function removeAnswer() {
		$ip = $this->input->post('ip');
		$deleted = $this->Answer_model->deleteAnswer($ip);
		$response = ['success' => ($deleted > 1)];
		header('Content-Type: application/json');
		echo json_encode($response); exit;
	}

}
