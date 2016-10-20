<?php 

class ssMail {

    private $from;
    private $to;
    private $subject;
    private $message;
    private $headers;

    public function __construct()
    {
        $this->reset_properties();
    }

    public function set_to( $_to )
    {
        $this->to = $_to;
    }

    public function get_to()
    {
        return $this->to;    
    }

    public function set_from( $_from )
    {
        $this->from = $_from;
    }

    public function get_from()
    {
        return $this->from;    
    }

    public function set_subject( $_subject )
    {
        $this->subject = $_subject;
    }

    public function get_subject()
    {
        return $this->subject;    
    }

    public function set_message( $_message )
    {
        $this->message = $_message;
    }

    public function get_message()
    {
        return $this->message;    
    }

    public function set_header( $_key, $_value)
    {
        $header = $_key . ":" . $_value;

        array_push($this->headers, $header);
    }

    private function get_headers()
    {
        return implode("\r\n", $this->headers);
    }

    public function send()
    {
        $this->set_default_headers();
/*
        $headers[] = "From: Sender Name <postmaster@localhost>";
        $headers[] = "Reply-To: Recipient Name <postmaster@localhost>";*/


        mail($this->to, $this->subject, $this->message, $this->get_headers());
    }


    private function set_default_headers()
    {
        $this->set_header("MIME-Version", ssConfig::get_parameter("MIME-Version"));
        $this->set_header("Content-type", ssConfig::get_parameter("Content-type"));
        $this->set_header("X-Mailer"    , "PHP/".phpversion());
        $this->set_header("Subject"     , $this->subject);
        $this->set_header("From"        , $this->from);
    }

    private function reset_properties()
    {
        $this->to        = 0;
        $this->from      = ssConfig::get_parameter("From");
        $this->subject   = "";
        $this->message   = "";
        $this->headers   = array();
    }
}