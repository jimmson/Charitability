<?php
namespace bootstrap\layouts;

use bones\base\layout;
use bones\containers\div;
use bones\containers\ul;
use bones\containers\li;
use bones\containers\a;

class panel extends layout
{
	public function render( $_container )
	{

		$_container->heading 	->render();
		$_container->body 		->render();

		if ($_container->footer->has_controls())
			$_container->footer->render();

	}
}