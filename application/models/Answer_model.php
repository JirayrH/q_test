<?php

class Answer_model extends CI_Model {

    public $ip_address;
    public $question_id;
    public $answer;
    public $correct;

    public function __construct() {
        parent::__construct();
    }

	public function loadAnswers() {
    	return $this->db->from('answers')->get()->result_array();
    }

    public function saveAnswer($question_id, $answer, $ip_address, $correct = 1) {
        $this->question_id = $question_id;
        $this->answer = $answer;
        $this->ip_address = $ip_address;
        $this->correct = $correct;
		return $this->db->insert('answers', $this);
    }

	public function deleteAnswer($ip) {
		$this->db->where('ip_address', $ip);
		$this->db->delete('answers');
		return $this->db->affected_rows();
    }

}