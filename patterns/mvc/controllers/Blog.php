<?php
/**
 * Created by PhpStorm.
 * User: Mikhaylov I.A.
 * Date: 05.09.2017
 * Time: 14:21
 */

namespace mvc\controllers;

use mvc;
use mvc\libs\Request;


Class Blog extends Controller
{
  public function get($request = [])
  {
    $Request = new Request();
    $id = $Request->getVariable($request,['id'],0);
    $Views = new mvc\views\View();
    $mBlog = new mvc\models\Blog();
    $Views->showPage('blogs/get', $mBlog->get(null, $id));
  }

  public function getList()
  {
    $Views = new mvc\views\View();
    $mBlog = new mvc\models\Blog();
    $Views->showPage('blogs/get_list', $mBlog->get());
  }

  public function addNote($request = [])
  {
    $Views = new mvc\views\View();
    $Views->showPage('blogs/add');
  }

  public function editNote($request = [])
  {
    $Views = new mvc\views\View();
    $Request = new mvc\libs\Request();
    $mBlog = new mvc\models\Blog();

    $id = $Request->getVariable($request, ['id'], null);
    $data = $mBlog->get(null, $id);
    $Views->showPage('blogs/edit', $data);
  }

  public function add($request = [])
  {

    $Request = new mvc\libs\Request();
    $mBlog = new mvc\models\Blog();

    $text = $Request->getVariable($request, ['text'], null);
    $description = $Request->getVariable($request, ['description'], null);

    $href = self::uploadImg();

    $status = $mBlog->add($text, $description, $href);
    print(json_encode(['status'=>($status)?'ok':'error'], JSON_UNESCAPED_UNICODE));
  }

  public function edit($request = [])
  {
    $Request = new mvc\libs\Request();
    $mBlog = new mvc\models\Blog();

    $id = $Request->getVariable($request, ['id'], null);
    $text = $Request->getVariable($request, ['text'], null);
    $description = $Request->getVariable($request, ['description'], null);

    $href = self::uploadImg();

    $status = $mBlog->edit($id, $description, $text, $href);
    print(json_encode(['status'=>($status)?'ok':'error'], JSON_UNESCAPED_UNICODE));
  }

  public function del($request = [])
  {
    $Request = new mvc\libs\Request();
    $id = $Request->getVariable($request, ['id'], null);
    $mBlog = new mvc\models\Blog();
    $mBlog->del($id);
    print(json_encode(['status'=>'ok'], JSON_UNESCAPED_UNICODE));
//    self::redirect('Location: /');
  }

  private function uploadImg()
  {
    global $Configs;
    $Request = new mvc\libs\Request;
    $folderUpload = $Request->getVariable($Configs, [
      'conf',
      'main',
      'uploads'
    ], null);

    if ($folderUpload == null) {
      return '';
    }

    $href = '';
    $resultSave = [];
    foreach ($_FILES as $key => $params) {
      $Upload = new mvc\libs\Upload(
        $folderUpload,
        $key
      );
      $result = $Upload->save();
      $resultSave = (array_key_exists(0, $result)) ? $result[0] : null;
      break;
    }

    if (
      is_array($resultSave)
      && !empty($resultSave)
      && array_key_exists('path', $resultSave)
      && array_key_exists('state', $resultSave)
      && $resultSave['state'] == true
    ) {
      $href = $resultSave['path'];
    }

    return $href;
  }
}