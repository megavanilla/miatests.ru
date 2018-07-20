<?php
/**
 * Created by PhpStorm.
 * User: Mikhaylov I.A.
 * Date: 05.09.2017
 * Time: 2:22
 */

namespace mvc\models;

class Blog extends Model
{
  public function __construct()
  {
    parent::__construct('blog');
  }

  public function getList()
  {
    return $this->get();
  }

  public function add($description, $text, $href)
  {
    $data = [
      'description' => $description,
      'text' => $text,
      'href_img' => $href,
    ];
    return $this->insert($data);
  }

  public function edit($id, $description, $text, $href)
  {
    $data = [
      'description' => $description,
      'text' => $text,
      'datetime_update' => date("Y-m-d H:i:s", time())
    ];

    if($href){
      $data['href_img'] = $href;
    }

    return $this->update($data, 'id', (int)$id);
  }
}