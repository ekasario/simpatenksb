<?php 


class kab_imb extends adminkab_controller {
	
	var $controller;
	public function kab_imb(){
		parent::__construct();
		$this->controller = get_class($this);
        $this->load->model('coremodel','cm');
		$this->load->model($this->controller.'_model','dm');
		$this->load->helper("tanggal");
	}
	
		function index(){
		



		$data_array=array();

        $data_array['curPage'] = 'imb_satu';

        $data_array['arr_status'] = array('' => '- Pilih Status -',
                                            '1' => 'Dalam Proses',
                                            '2' => 'Disetujui',
                                            '3' => 'Tidak Disetujui', );

		$data_array['arr_kecamatan'] = $this->cm->arr_dropdown3("tiger_kecamatan", "id", "kecamatan", "kecamatan", "id_kota", "52_7");

		$content = $this->load->view($this->controller."_view",$data_array,true);

		$this->set_subtitle("IMB Dibawah 250");
		$this->set_title("IMB Dibawah 250");
		$this->set_content($content);
		$this->cetak();

	}


		 function get_data() {

    	
    	// show_array($userdata);

    	$draw = $_REQUEST['draw']; // get the requested page 
    	$start = $_REQUEST['start'];
        $limit = $_REQUEST['length']; // get how many rows we want to have into the grid 
        $sidx = isset($_REQUEST['order'][0]['column'])?$_REQUEST['order'][0]['column']:0; // get index row - i.e. user click to sort 
        $sord = isset($_REQUEST['order'][0]['dir'])?$_REQUEST['order'][0]['dir']:"asc"; // get the direction if(!$sidx) $sidx =1;  
        
  
        $nama_pemohon = $_REQUEST['columns'][1]['search']['value'];
        $no_regis = $_REQUEST['columns'][2]['search']['value'];
        $kecamatan = $_REQUEST['columns'][3]['search']['value'];
        $status = $_REQUEST['columns'][4]['search']['value'];
        



      //  order[0][column]
        $req_param = array (
				"sort_by" => $sidx,
				"sort_direction" => $sord,
				"limit" => null,
				"no_regis" => $no_regis,
                "nama_pemohon" => $nama_pemohon,
				"kecamatan" => $kecamatan,
                "status" => $status,
				 
		);   

		// show_array($req_param);
  //       exit();  
           
        $row = $this->dm->data($req_param)->result_array();


		
        $count = count($row); 
       
        
        $req_param['limit'] = array(
                    'start' => $start,
                    'end' => $limit
        );
          
        
        $result = $this->dm->data($req_param)->result_array();
        

       
        $arr_data = array();
        foreach($result as $row) : 
		$id = $row['no_regis'];

        $action = "<div class='btn-group'>
                              <button type='button' class='btn btn-primary'>Action</button>
                              <button type='button' class='btn btn-primary dropdown-toggle' data-toggle='dropdown'>
                                <span class='caret'></span>
                                <span class='sr-only'>Toggle Dropdown</span>
                              </button>
                              <ul class='dropdown-menu' role='menu'>
                              	<li><a href='kab_imb/status?id=$id'><i class='fa fa-eye'></i> Status</a></li>
                              </ul>
                            </div>";

         if ($row['status']=='1') {
            $status = '<span class="label label-info"> Dalam Proses</span>';
        }else if ($row['status']=='2') {
            $status = '<span class="label label-success"> Disetujui</span>';
        }else if ($row['status']=='3') {
            $status = '<span class="label label-danger"> Tidak Disetujui</span>';
        }
        	 
        	$arr_data[] = array(
        		$row['no_regis'],
        		$row['nama_pemohon'],
        		flipdate($row['tgl_verifikasi']),
                $row['nm_kecamatan'],
        		$row['nama_petugas_verifikasi'],
                $status,
        	   $action
        		
         			 
        		  				);
        endforeach;

         $responce = array('draw' => $draw, // ($start==0)?1:$start,
        				  'recordsTotal' => $count, 
        				  'recordsFiltered' => $count,
        				  'data'=>$arr_data
        	);
         
        echo json_encode($responce); 
    }


    function status(){


        
         $get = $this->input->get(); 
         $no_regis = $get['id'];
         
         $this->db->where('no_regis',$no_regis);
         $imb = $this->db->get('imb');
         $data_array = $imb->row_array();

         $data_array['tgl_verifikasi'] = flipdate($data_array['tgl_verifikasi']);
         $data_array['tgl_surat'] = flipdate($data_array['tgl_surat']);
         $data_array['tgl_rekom_desa'] = flipdate($data_array['tgl_rekom_desa']);
         $data_array['tgl_rekom_uptd'] = flipdate($data_array['tgl_rekom_uptd']);
         $data_array['tgl_skgr'] = flipdate($data_array['tgl_skgr']);

         $data_array['action'] = 'update';
         $data_array['curPage'] = 'imb_satu';


         $data_array['arr_status'] = array('1' => "- Pilih status -",
                                            '2' => "Disetujui",
                                            '3' => "Tidak Disetujui" );
         // show_array($data); exit;
         // show_array($data_array);
      //    exit();
        

        // $data_array=array(
        //      'id' => $data->id,
        //      'nama' => $data->nama,
        //      'no_siup' => $data->no_siup,
        //      'no_npwp' => $data->no_npwp,
        //      'no_tdp' => $data->no_tdp,
        //      'telp' => $data->telp,
        //      'alamat' => $data->alamat,
        //      'email' => $data->email,
        //      'hp' => $data->hp,

        //  );

        

        $content = $this->load->view($this->controller."_status_view",$data_array,true);

        $this->set_subtitle("Status IMB");
        $this->set_title("Status IMB");
        $this->set_content($content);
        $this->cetak();

    }


    function update(){


    $post = $this->input->post();

    // show_array($post);
    // exit();
       


        $this->load->library('form_validation');
        $this->form_validation->set_rules('no_regis','Nomor registrasi','required');
        $this->form_validation->set_rules('nama_pemohon','Nama Pemohon','required');
        $this->form_validation->set_rules('alamat','Alamat','required');
        $this->form_validation->set_rules('pimb','Syarat umum pertama','required');
        $this->form_validation->set_rules('ktp','Syarat umum kedua','required');
        $this->form_validation->set_rules('foto','Syarat umum ketiga','required');
        $this->form_validation->set_rules('sertifikat_tanah','Syarat umum keempat','required');
        $this->form_validation->set_rules('pbb','Syarat umum kelima','required');
        $this->form_validation->set_rules('bap','Syarat umum keenam','required');
        $this->form_validation->set_rules('penelitian_tanah','Syarat umum ketujuh','required');
        $this->form_validation->set_rules('setuju_sempada_tanah','Syarat umum kedelapan','required');
        $this->form_validation->set_rules('rekom_dishub','Syarat umum kesembilan','required');
        $this->form_validation->set_rules('tek_gamabar_rencana','Syarat teknis pertama','required');
        $this->form_validation->set_rules('tek_instalasi_air','Syarat teknis kedua','required');
        $this->form_validation->set_rules('tek_penelitian_tanah','Syarat teknis ketiga','required');
        $this->form_validation->set_rules('tek_pengaman','Syarat teknis keempat','required');
        $this->form_validation->set_rules('sistem_drainase','Syarat teknis kelima','required');
        $this->form_validation->set_rules('nama_petugas_verifikasi','Syarat teknis kelima','required');
        $this->form_validation->set_rules('tgl_verifikasi','Syarat teknis kelima','required');
          
         
        $this->form_validation->set_message('required', ' Harap isi semua data');
        
        $this->form_validation->set_error_delimiters('', '<br>&nbsp;<br>&nbsp;<br>');

     

        // show_array($post);
        // exit();

if($this->form_validation->run() == TRUE ) { 

        $data = array('status' => $post['status'], );

        

    
        $this->db->where('no_regis', $post['no_regis']);
        $res = $this->db->update('imb', $data); 
        if($res){
            $arr = array("error"=>false,'message'=>"BERHASIL DIUPDATE");
        }
        else {
             $arr = array("error"=>true,'message'=>"GAGAL  DIUPDATE");
        }
}
else {
    $arr = array("error"=>true,'message'=>validation_errors());
}
        echo json_encode($arr);
}


}
?>