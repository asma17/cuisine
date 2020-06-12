<?php
    namespace App\Service;
    use Symfony\Component\Form\FormError;

        class GestionForm
        {
            public function serializeFormErrors($form){
                $errors = array(); 
                foreach ($form->all() as $key => $child) {
                    if (!$child->isValid()) {
                      $dd = 0;
                        foreach ($child->getErrors() as $error) {
                          if($dd == 0 ){ 
                            $errors[$key] = $error->getMessage();
                            $form[$key]->clearErrors(true);
                            $form->get($key)->addError(new FormError( $error->getMessage())); 
                              $dd++;
                          }
                        }
                    }
                }
                return $errors;
            }  
           
        }