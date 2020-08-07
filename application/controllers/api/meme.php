<?php
   
require APPPATH . 'libraries/REST_Controller.php';
     
class Meme extends REST_Controller {
    
	  /**
     * Get All Data from this method.
     *
     * @return Response
    */
    public function __construct() {
       parent::__construct();
       $this->load->database();
	   //Item_model
	   $this->load->model('meme_model');
    }
       
    /**
     * Get All Data from this method.
     *
     * @return Response
    */
	public function index_get($id = 0)
	{
		if(!empty($id))
		{
            $data = $this->db->get_where("items", ['id' => $id])->row_array();
			
			$items_count = $data['count']; // retrieve count in arr-data whr user request id 
			$input=array('count' => $items_count+1); //update count using arr 
			$this->db->update('items', $input, array('id'=>$id));
        }
		else
		{
            $data = $this->db->get("items")->result();
			
			foreach ($data as $items)
			{
				$items_id = $items -> id;
				$items_count = $items -> count;
				$input = array('count' => $items_count+1);
				$this->db->update('items', $input, array('id'=>$items_id));
			}
        }	
		//echo count($data);
		//echo $data[2]->count;  ---> stdClass object
		//print_r($data);
		
        $this->response($data, REST_Controller::HTTP_OK);
	}
	
	// get data by page
	public function page_get($pg_no = 0)
	{
        if(!empty($pg_no))
		{
            $data = $this->db->get_where("items", ['page' => $pg_no])->result();
			
			foreach ($data as $items)
			{
				$items_id = $items -> id;
				$items_count = $items -> count;
				$input = array('count' => $items_count+1);
				$this->db->update('items', $input, array('id'=>$items_id));
			}
			
			$this->response($data, REST_Controller::HTTP_OK);
        }
	}
	
	// find popular 
	public function popular_get()
	{
		$data = $this->meme_model->find_popular(); // define query for popular meme in model
		
		foreach ($data as $items)
		{
			$items_id = $items['id'];
			$items_count = $items ['count'];
			$input = array('count' => $items_count+1);
			$this->db->update('items', $input, array('id'=>$items_id));
		}
		
		$this->response($data, REST_Controller::HTTP_OK);
	}
	
	// create input
    public function create_post()
    {
		//$page_no, $no_images;
		
		$currPg_detail = $this->meme_model->find_curr_pg();
		
		foreach($currPg_detail as $pg_detail)
		{
			$page_no = $pg_detail['no_page'];
			$no_images = $pg_detail['no_images'];
		}
		
		$input_arr = $this->post()['data'];
		
		foreach($input_arr as $input)
		{
			if ($no_images < 6 && $no_images != 6)
			{
				$no_images++;
				
				$input = array_merge($input, array('page'=>$page_no));
				$this->db->insert('items',$input);
			}
			else  //($no_images == 6)
			{
				$page_no++;
				$no_images = 1;
				
				$input = array_merge($input, array('page'=>$page_no));
				$this->db->insert('items',$input);
			}
			//print_r($input);
		}
     
        $this->response(['Meme Added successfully.'], REST_Controller::HTTP_OK);
		
    } 
	
	
    	
}