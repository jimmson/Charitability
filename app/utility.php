<?php 

use bones\containers\div;
use bones\controls\h;
use bootstrap\controls\binput;
use bootstrap\controls\bp;

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

    public static function get_reference( $_prefix = "", $_leading_character = "0", $_length = 12 )
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

    public static function get_address_container( $_container, $_view_only = false )
    {
        $control_class  = ( $_view_only ? "form-control-static" : "form-control");
        $number         = ( $_view_only ? new bp("number")  : new binput("number") );
        $street         = ( $_view_only ? new bp("street")  : new binput("street") );
        $country        = ( $_view_only ? new bp("country") : new binput("country"));
        $state          = ( $_view_only ? new bp("state")   : new binput("state")  );
        $city           = ( $_view_only ? new bp("city")    : new binput("city")   );
        $zip            = ( $_view_only ? new bp("zip")     : new binput("zip")    );

        $number     ->set_class($control_class);
        $street     ->set_class($control_class);
        $country    ->set_class($control_class);
        $state      ->set_class($control_class);
        $city       ->set_class($control_class);
        $zip        ->set_class($control_class);

        $number     ->set_label("Number:");
        $street     ->set_label("Street:");
        $country    ->set_label("Country:");
        $state      ->set_label("State:");
        $city       ->set_label("City:");
        $zip        ->set_label("ZIP:");

        $_container  ->add( $number, $street, $country, $state, $city, $zip);

        return $_container;
    }

    public static function get_contact_container(  $_container, $_view_only = false )
    {
        $control_class  = ( $_view_only ? "form-control-static" : "form-control");
        $telephone      = ( $_view_only ? new bp("phone")   : new binput("phone"));
        $fax            = ( $_view_only ? new bp("fax")     : new binput("fax")  );
        $email          = ( $_view_only ? new bp("email")   : new binput("email"));

        $telephone  ->set_class($control_class);
        $fax        ->set_class($control_class);
        $email      ->set_class($control_class);

        $telephone  ->set_label("Telephone:");
        $fax        ->set_label("Fax:");
        $email      ->set_label("Email:");

        $_container ->add( $telephone, $fax, $email );

        return  $_container;
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

    public static function upload_files()
    {
        $file_path = "";
        $extension = "";
        $num_files = count($_FILES["files"]["name"]);
        $directory = "/public/uploads/";
        $images    = array();

        for( $i = 0; $i < $num_files; ++$i )
        {
            $extension = "." . pathinfo( $_FILES["files"]["name"][$i], PATHINFO_EXTENSION );
            $temp_path = $_FILES['files']['tmp_name'][$i];
            $file_name = self::get_reference("IMG" . $i);
            $file_path = $directory . $file_name . $extension;
            move_uploaded_file( $temp_path, $file_path ); 

            $image = new ssImage();
            $image ->set_location($file_path);
            $image ->save();
            $images[] = $image;
        }   

        return $images;
    }
}