<?php 

use bones\containers\div;
use bones\controls\h;

use bootstrap\controls\binput;

class ssUtility {

	public static function get_types( $_category_code )
	{
  		QUERY::QTABLE("ssc_type");
        QUERY::QWHERE(QUERY::condition("category_code", "=", QUERY::quote($_category_code)));

        return DB::select(QUERY::QSELECT());
	}

    public static function get_statuses( $_category_code )
    {
        QUERY::QTABLE("ssc_status");
        QUERY::QWHERE(QUERY::condition("category_code", "=", QUERY::quote($_category_code)));

        return DB::select(QUERY::QSELECT());
    }

    public static function get_reference( $_prefix, $_leading_character = "0", $_length = 12 )
    {
        $reference  =      date("y"); 
        $reference .=      date("n");
        $reference .=      date("j");
        $reference .=      date("G");
        $reference .= trim(date("i"), "0");
        $reference .= trim(date("s"), "0");

        $fill_length = $_length - strlen($reference);

        if ( $fill_length > 0 )
        {
            $reference = str_pad( $reference, $_length, $_leading_character, STR_PAD_LEFT);
        }

        return $_prefix . $reference;
    }

    public static function get_address_container()
    {
        $container  = new div();
        $title      = new h("", h::IMPORTANCE_2);
        $line1      = new binput("line1");
        $line2      = new binput("line2");
        $country    = new binput("country");
        $state      = new binput("state");
        $city       = new binput("city");
        $zip        = new binput("zip");

        $line1      ->set_class("form-control");
        $line2      ->set_class("form-control");
        $country    ->set_class("form-control");
        $state      ->set_class("form-control");
        $city       ->set_class("form-control");
        $zip        ->set_class("form-control");
        $title      ->set_class("page-header");
        $container  ->set_class("col-md-6", "form-horizontal");

        $line1      ->set_label("Line 1:");
        $line2      ->set_label("Line 2:");
        $country    ->set_label("Country:");
        $state      ->set_label("State:");
        $city       ->set_label("City:");
        $zip        ->set_label("ZIP:");

        $title      ->set_text("Address");

        $container  ->add( $title, $line1, $line2, $country, $state, $city, $zip);

        return $container;
    }

    public static function get_contact_container()
    {
        $container  = new div();
        $title      = new h("", h::IMPORTANCE_2);
        $telephone  = new binput("phone");
        $fax        = new binput("fax");
        $email      = new binput("email");

        $telephone  ->set_class("form-control");
        $fax        ->set_class("form-control");
        $email      ->set_class("form-control");
        $title      ->set_class("page-header");
        $container  ->set_class("col-md-6", "form-horizontal");

        $telephone  ->set_label("Telephone:");
        $fax        ->set_label("Fax:");
        $email      ->set_label("Email:");

        $title      ->set_text("Contact Details");

        $container  ->add( $title, $telephone, $fax, $email );

        return $container;
    }

    public static function upload_file()
    {
        $file_path = null;

        if ( isset($_FILES["file"]) )
        {
            if ($_FILES["file"]["error"] == 0 )    
            {
                $extension = pathinfo( $_FILES["file"]["name"], PATHINFO_EXTENSION );
                $temp_path = $_FILES['file']['tmp_name'];
                $file_path = sprintf('public/uploads/%s.%s', sha1_file($temp_path), $extension);

                move_uploaded_file( $temp_path, $file_path ); 

                $file_path = "/". $file_path;
            }
        }

        return $file_path;
    }

}