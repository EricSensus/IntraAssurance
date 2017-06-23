<?php

namespace Jenga\MyProject\Documents\Controllers;

use Jenga\App\Core\App;
use Jenga\App\Request\Url;
use Jenga\App\Request\Input;
use Jenga\App\Views\Redirect;
use Jenga\App\Helpers\FileUpload;
use Jenga\App\Project\Core\Project;
use Jenga\App\Controllers\Controller;

use Jenga\MyProject\Claims\Controllers\ClaimsController;
use Jenga\MyProject\Documents\Views\DocumentsView;
use Jenga\MyProject\Elements;

/**
 * Class DocumentsController
 * @property-read DocumentsView $view
 * @package Jenga\MyProject\Documents\Controllers
 */
class DocumentsController extends Controller
{
    public $element, $id, $action;
    /**
     * @var
     */
    public $settings;
    /**
     * @var string Uploads folder
     */
    protected $upload_dir = PROJECT_PATH . DS . 'uploads/';
    /**
     * @var ClaimsController
     */
    private $claimsCtrl;
    /**
     * @param $element
     * @param $action
     * @param $id
     * @param $folder
     */
    public function upload($element, $action, $id, $folder)
    {
        $settings = [
            'element' => $element,
            'title' => ($element == 'policies') ? 'Upload Policy Documents' : 'Upload Document',
            'action' => $action,
            'id' => $id,
            'upload_folder' => $folder
        ];
        $this->view->uploadForm($settings);
    }

    public function processUpload()
    {
        //get folder information
        $this->element = Input::post('element');
        $this->action = Input::post('action');
        $this->id = Input::post('id');
        //get absolute folder path
        $path = Project::elements()[$this->element]['path'];
//        $elementpath = ABSOLUTE_PATH . DS . 'project' . DS . str_replace('/', DS, $path);
//        $folderpath = $this->uplelementpath . DS . $folder;
        //process file upload
        $handler = new FileUpload('file_import');
        $handler->setFilePrefix();

        if ($handler->handleUpload($this->upload_dir)) {

            $filename = $handler->getFileName();
            $docid = $this->saveDocument($filename);
            $save = $this->model->saveDocumentEntry($this->element, $docid, $this->id);

            if (is_bool($save)) {
                Redirect::withNotice('The document has been saved', 'success')
                    ->to(Url::base() . '/admin/' . $this->element . '/' . $this->action . '/' . $this->id);
            } else {
                throw App::exception($save->errors[0]);
            }
        } else {
            throw App::exception($handler->getErrorMsg());
        }
    }

    /**
     * Saves the sent document in the dbase
     * @param type $filename
     * @param type $filepath
     * @param type $doctype
     * @param type $description
     * @return boolean
     */
    public function saveDoc($filename, $filepath, $doctype, $description = '')
    {

        $doc = $this->model->find(['filename' => $filename]);

        $doc->filename = $filename;
        $doc->filepath = $filepath;
        $doc->doctype = $doctype;
        $doc->description = $description;
        $doc->datetime = time();

        $doc->save();

        if ($doc->hasNoErrors())
            return $doc->last_altered_row;
        else
            return false;
    }

    public function deleteDoc($id)
    {

        $doc = $this->model->find($id);
        $filepath = str_replace('/', DS, $doc->filepath);
        $del = $this->model->where('id', $id)->delete();

        if ($del) {
            @unlink(ABSOLUTE_PROJECT_PATH . DS . $filepath);
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * Uploads claims documents
     * name of the <input type="file" name="$name"/>
     * @param $name
     */
    public function uploadMultipleFiles($name, $id)
    {
        $this->element = Input::post('element');
        $this->claimsCtrl = Elements::call('Claims/ClaimsController');
        $this->id = $id;

        $count = count($_FILES[$name]['name']);
        for ($x = 0; $x < $count; $x++) {
            $_FILES['file' . $x] = [
                'name' => $_FILES[$name]['name'][$x],
                'type' => $_FILES[$name]['type'][$x],
                'tmp_name' => $_FILES[$name]['tmp_name'][$x],
                'error' => $_FILES[$name]['error'][$x],
                'size' => $_FILES[$name]['size'][$x],
            ];
            $handler = new FileUpload('file' . $x);
            $handler->setFilePrefix();

            if ($handler->handleUpload($this->upload_dir)) {
                $filename = $handler->getFileName();

                $docid =  $this->saveDocument($filename);

                // update claims timeline
                $this->claimsCtrl->updateTimeline($this->id, 'Uploaded a file -> '. $_FILES[$name]['name'][$x], $docid);
            }
        }
    }

    public function saveDocument($filename){

        $docs = $this->model->find(['filename', $filename]);
        $docs->filename = $filename;
        $docs->filepath = 'project' . DS . 'uploads' . DS . $filename;
        $docs->description = $this->element . ' document';
        $docs->doctype = $this->element;
        $docs->datetime = time();

        $docs->save();

        //get document id
        $docid = $docs->last_altered_row;
        return $docid;
    }
}