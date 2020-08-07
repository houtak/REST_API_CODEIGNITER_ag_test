<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class Meme_model extends CI_Model
{
	public function __construct()
	{
	     parent::__construct();
	}
	
	
	public function find_popular()
	{
		$query = "SELECT * FROM test_rest_api.items WHERE count = (SELECT MAX(count) FROM test_rest_api.items)";
		$result_rw = $this->db->query($query);	
		
		return $result_rw->result_array();
	}
	
	public function find_curr_pg()
	{
		$query = "SELECT page as no_page, count(page) as no_images FROM test_rest_api.items WHERE page = (SELECT MAX(page) FROM test_rest_api.items)";
		$result_rw = $this->db->query($query);	
		
		return $result_rw->result_array();
	}
	
}