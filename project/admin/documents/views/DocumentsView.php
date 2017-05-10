<?php
namespace Jenga\MyProject\Documents\Views;

use Jenga\App\Views\View;
use Jenga\App\Request\Url;
use Jenga\App\Views\Overlays;

class DocumentsView extends View {
    
    public function uploadForm(array $settings) {
        
        $uploadform = '<form id="importform" enctype="multipart/form-data" action="'.Url::route('/admin/documents/processupload').'" method="POST" >'
                    . '<input type="hidden" name="element" value="'.$settings['element'].'">'
                    . '<input type="hidden" name="action" value="'.$settings['action'].'">'
                    . '<input type="hidden" name="id" value="'.$settings['id'].'">'
                    . '<input type="hidden" name="upload_folder" value="'.$settings['upload_folder'].'">'
                    . '<input id="input-id" name="file_import" type="file" class="file" >'
                . '</form>';
        
        $modal_settings = [
            'id' => 'adddocument',
            'formid' => 'importform',
            'role' => 'dialog',
            'title' => $settings['title'],
            'buttons' => [
                'Cancel' => [
                    'class' => 'btn btn-default',
                    'data-dismiss' => 'modal'
                ]
            ]
        ];
        
        $form = Overlays::ModalDialog($modal_settings, $uploadform);        
        $this->set('uploadform', $form);
    }
}

