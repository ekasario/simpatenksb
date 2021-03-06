<?php 

class kab_add_kecamatan_model extends CI_Model {


	function kab_add_kecamatan_model(){
		parent::__construct();
	}




 function data($param)
	{		

		// show_array($param);
		// exit;

		 extract($param);

		 $kolom = array(0=>"id",
							"nama",
							"username",
							"kabupaten",
							"kecamatan",							 
		 	);


		

		$this->db->select('a.*, kc.kecamatan as kecamatan')->from("admin a");
		 $this->db->join('tiger_kecamatan kc','a.kecamatan=kc.id', 'left');
		 
		 $this->db->where("level",5);


		 

		 if(!empty($nama)) {
		 	$this->db->like("nama",$nama);
		 }

		($param['limit'] != null ? $this->db->limit($param['limit']['end'], $param['limit']['start']) : '');
		//$this->db->limit($param['limit']['end'], $param['limit']['start']) ;
       
       ($param['sort_by'] != null) ? $this->db->order_by($kolom[$param['sort_by']], $param['sort_direction']) :'';
        
		$res = $this->db->get();
		// echo $this->db->last_query(); exit;
 		return $res;
	}

	function arr_dropdown($vTable, $vINDEX, $vVALUE, $vORDERBY, $vCONDITION, $vWHERE){
                $this->db->where($vCONDITION, $vWHERE);
                $this->db->order_by($vORDERBY);
                $res  = $this->db->get($vTable);
        //echo $this->db->last_query(); exit;

                $ret = array();
                $ret = array('' => '- Pilih '.$vVALUE.' -', );
                foreach($res->result_array() as $row) : 
                        $ret[$row[$vINDEX]] = $row[$vVALUE];
                endforeach;
                return $ret;

        }

	


}

?>