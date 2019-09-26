<?php


class LayoutView {
  
  private $message = '';

  public function render($viewToRender, DateTimeView $dateView, $isLoggedIn, $isRegister, $loginView) {
    //if($isRegister === false) {
     // $viewToRender = $loginView;
   // }
    echo '<!DOCTYPE html>
      <html>
        <head>
          <meta charset="utf-8">
          <title>Login Example</title>
        </head>
        <body>
          <h1>Assignment 2</h1>
          ' . $this->renderHomeOrRegisterTag($isRegister, $isLoggedIn) . '
          ' . $this->renderIsLoggedIn($isLoggedIn) . '
          ' . $this->renderRegisterTitle($isRegister) . '
          
          <div class="container">
              ' . $viewToRender->response() . '
              
              ' . $dateView->show() . '
          </div>
         </body>
      </html>
    ';
  }
  
  private function renderIsLoggedIn($isLoggedIn) {
    if ($isLoggedIn) {
      return '<h2>Logged in</h2>';
    }
    else {
      return '<h2>Not logged in</h2>';
    }
    
  }

  private function renderRegisterTitle($isRegister) {
    if ($isRegister) {
      return '<h2>Register new user</h2>';
    } else {
      return '';
    }
  }

  private function renderHomeOrRegisterTag($isRegister, $isLoggedIn) {
    if ($isRegister) {
      return '<a href="?">Back to login</a>';
    } else if (!$isLoggedIn){
      return '<a href="?register">Register a new user</a>';
    }
  }

 
}
