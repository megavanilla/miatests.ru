<?php
class M_Calls extends CI_Model {

  function __construct() {
    parent::__construct();
  }
  function get_history_bonus()
  {
    return $this->db->get('history_bonus')->result();
  }
  function get_stat_calls()
  {
    return $this->db->get('stat_calls')->result();
  }
  function get_total_stat()
  {
    return $this->db->get('total_stat')->result();
  }
  function add_call($id_manager)
  {
    $data_insert = [
      'id_manager' => (int)$id_manager
    ];
    if($this->db->insert('calls',$data_insert)){
      return $this->db->affected_rows();
    }else{
      return 0;
    }
    
    return $insert_res;
  }
}