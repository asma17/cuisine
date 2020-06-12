<?php
    namespace App\Service;
    use Symfony\Component\HttpFoundation\Session\SessionInterface;
 
    class Session {

        protected $session;
      
        public function __construct(SessionInterface $session)
        { 
            $this->session = $session;
        }

          
        public function checkSession(){
            $session = $this->session;
            if ( $session->has('previous')){
              if (basename($_SERVER['PHP_SELF']) != $session->get('previous')) {
                    session_destroy();
              }
            }
            $session->set('previous', basename($_SERVER['PHP_SELF']));

          }  
           
        }