<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Question extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

	public function index()	{
		$this->load->helper('cookie');
		if(get_cookie('submitted')) {
			$this->load->view('submitted');
		} else {
			$this->load->view('home');
		}
	}

    public function submitAnswers() {
        $this->load->model('Answer_model');
        $response = [];
        $data = $this->input->post();
        $ip_address = $this->input->ip_address();
        $correct_answers = [
            '1' => 7,
            '2' => 365,
            '3' => ["Спонч Боб", "Русалка"]
        ];

        if(!empty($data['answers'])) {
            foreach($data['answers'] as $id => $answer) {
                if(is_array($correct_answers[$id])) {
                    $correct = (int) in_array($answer, $correct_answers[$id]);
                } else {
                    $correct = (int) $answer == $correct_answers[$id];
                }

                $this->Answer_model->saveAnswer($id, $answer, $ip_address, $correct);
            }

            $response['view'] = $this->load->view('submitted.php', null, true);
        } else {
            $response['error'] = 1;
            $response['message'] = 'Incorrect answers';
        }

        header('Content-Type: application/json');
        echo json_encode($response); exit;
    }

}
