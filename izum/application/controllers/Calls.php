<?php

class Calls extends CI_Controller {

	public function index($manager = '')
	{
      if(!is_file('application/views/calls_stat.php')){
        show_404();
      }
      
      $this->load->model('M_Calls','calls');
      
      $data = [
        'description' => 'Описание страницы',
        'keywords' => 'Ключевые слова',
        'title' => 'Данные по звонкам менеджеров',
        'history_bonus' => $this->calls->get_history_bonus(),
        'stat_calls' => $this->calls->get_stat_calls(),
        'total_stat' => $this->calls->get_total_stat()
      ];
      $this->load->view('templates/header',$data);
      $this->load->view('calls_stat',$data);
      $this->load->view('templates/footer',$data);
	}
    
	public function add($manager = 0)
	{
      if(!is_file('application/views/calls_add.php')){
        show_404();
      }
      
      $this->load->model('M_Calls','calls');
      
      $res = $this->calls->add_call((int)$manager);
      
      $data = [
        'description' => 'Описание страницы',
        'keywords' => 'Ключевые слова',
        'title' => 'Добавление звонка',
        'res_insert' => $res
      ];
      $this->load->view('templates/header',$data);
      $this->load->view('calls_add',$data);
      $this->load->view('templates/footer',$data);
	}
}
