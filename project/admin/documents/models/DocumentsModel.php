<?php
namespace Jenga\MyProject\Documents\Models;

use Jenga\App\Models\ORM;

use Jenga\MyProject\Elements;

class DocumentsModel extends ORM {
    
    public $table = 'documents';
    public $doctables = [
            'customer_quotes' => 'quotes_documents',
            'policies' => 'policies_documents',
            'customers' => 'customers_documents'
        ];
    
    /**
     * Saves the document entry in its respective table
     * @param type $element
     * @param type $documentid
     * @param type $tableid
     * @return boolean
     */
    public function saveDocumentEntry($element, $documentid, $tableid){
        $element_table = Elements::call(ucfirst($element).'/'.ucfirst($element).'Controller')->model->table;

        $element_docs_table = $this->table($this->doctables[$element_table], 'NATIVE');
        
        $document = $element_docs_table
                        ->where('documents_id', $documentid)
                        ->where($element_table.'_id', $tableid)
                    ->first();
        
        $record = $element_docs_table->find($document->id);
        $record->{$element_table.'_id'} = $tableid;
        $record->documents_id = $documentid;
        
        $record->save();
        
        if($record->hasNoErrors()){
            return TRUE;
        }
        else {
            return $this->errors;
        }
    }
}