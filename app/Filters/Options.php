<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;




class Options implements FilterInterface
{
  public function before(RequestInterface $request,$arguments = null)
  {
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: Authorization,Content-Type,X-Requested-With");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
    $method = $_SERVER['REQUEST_METHOD'];
    if ($method == "OPTIONS") {
      die();
    }
  }

  //--------------------------------------------------------------------

  public function after(RequestInterface $request, ResponseInterface $response,$arguments = null)
  {
    // Do something here
  }
}