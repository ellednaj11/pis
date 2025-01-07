<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Check if user is logged in
        if (session()->has('isLoggedIn')) {
            // Redirect to home or any other page
            // return redirect()->to('/dashboard');
            // if (session()->get('role') != 1) {
            //     return redirect()->to('/client');
            // } else {
                return redirect()->to('/dashboard');
            // }
        }

        // $uri = service('uri');
        
        // // Allow access to the login and logout pages without redirection
        // if ($uri->getPath() === '/' || $uri->getPath() === 'logout') {
        //     return;
        // }
        
        // // Check if user is logged in
        // if (session()->get('isLoggedIn')) {
        //     // If user is logged in, redirect to dashboard if not already on the dashboard
        //     if ($uri->getPath() !== 'dashboard') {
        //         return redirect()->to('/dashboard');
        //     }
        // } else {
        //     // If user is not logged in, redirect to login page if not already there
        //     if ($uri->getPath() !== '/') {
        //         return redirect()->to('/');
        //     }
        // }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here if needed
    }
}
