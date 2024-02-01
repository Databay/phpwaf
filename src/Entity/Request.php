<?php

namespace App\Entity;

class Request
{
    private $request;

    private $get;

    private $post;

    private $files;

    private $cookie;

    private $session;

    private $server;

    private $headers;

    public function __construct(
        $request,
        $get,
        $post,
        $files,
        $cookie,
        $session,
        $server,
        $headers
    ) {
        $this->request = $request;
        $this->get = $get;
        $this->post = $post;
        $this->files = $files;
        $this->cookie = $cookie;
        $this->session = $session;
        $this->server = $server;
        $this->headers = $headers;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function setRequest($request): Request
    {
        $this->request = $request;
        return $this;
    }

    public function getGet()
    {
        return $this->get;
    }

    public function setGet($get): Request
    {
        $this->get = $get;
        return $this;
    }

    public function getPost()
    {
        return $this->post;
    }

    public function setPost($post): Request
    {
        $this->post = $post;
        return $this;
    }

    public function getFiles()
    {
        return $this->files;
    }

    public function setFiles($files): Request
    {
        $this->files = $files;
        return $this;
    }

    public function getCookie()
    {
        return $this->cookie;
    }

    public function setCookie($cookie): Request
    {
        $this->cookie = $cookie;
        return $this;
    }

    public function getSession()
    {
        return $this->session;
    }

    public function setSession($session): Request
    {
        $this->session = $session;
        return $this;
    }

    public function getServer()
    {
        return $this->server;
    }

    public function setServer($server): Request
    {
        $this->server = $server;
        return $this;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function setHeaders($headers): Request
    {
        $this->headers = $headers;
        return $this;
    }
}