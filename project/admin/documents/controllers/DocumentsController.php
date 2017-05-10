<?php

namespace Jenga\MyProject\Documents\Controllers;

use Jenga\App\Core\App;
use Jenga\App\Request\Url;
use Jenga\App\Request\Input;
use Jenga\App\Views\Redirect;
use Jenga\App\Helpers\FileUpload;
use Jenga\App\Project\Core\Project;
use Jenga\App\Controllers\Controller;

use Jenga\MyProject\Documents\Views\DocumentsView;
use Jenga\MyProject\Elements;

/**
 * Class DocumentsController
 * @property-read DocumentsView $view
 * @package Jenga\MyProject\Documents\Controllers
 */
class DocumentsController extends Controller
{
    /**
     * @var
     */
    public $settings;
    /**
     * @var string Uploads folder
     */
    protected $upload_dir = PROJECT_PATH . DS . 'uploads/';

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
        $element = Input::post('element');
        $action = Input::post('action');
//        dd($action);
        $id = Input::post('id');
        $folder = Input::post('upload_folder');
        //get absolute folder path
        $path = Project::elements()[$element]['path'];
//        $elementpath = ABSOLUTE_PATH . DS . 'project' . DS . str_replace('/', DS, $path);
//        $folderpath = $this->uplelementpath . DS . $folder;
        //process file upload
        $handler = new FileUpload('file_import');
        $handler->setFilePrefix();

        if ($handler->handleUpload($this->upload_dir)) {

            $filename = $handler->getFileName();
            $docs = $this->model->find(['filename' => $filename]);

            $docs->filename = $filename;
            $docs->filepath = 'project' . DS . 'uploads' . DS . $filename;
            $docs->description = $element . ' document';
            $docs->doctype = $element;
            $docs->datetime = time();

            $docs->save();

            //get document id
            $docid = $docs->last_altered_row;
            $save = $this->model->saveDocumentEntry($element, $docid, $id);

            if (is_bool($save)) {

                Redirect::withNotice('The document has been saved', 'success')
                    ->to(Url::base() . '/admin/' . $element . '/' . $action . '/' . $id);
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
}