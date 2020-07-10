<?php
namespace bootstrap\layouts;

use bones\base\layout;
use bones\containers\div;

class grid extends layout
{
    const FULL_WIDTH 	= 12;
    const HALF_WIDTH 	= 6;
    const QUARTER_WIDTH = 3;
    
    public $rows;

    public function __construct()
    {
        parent::__construct();

        $this->rows = [];
    }
  
    public function add_row(...$collumns)
    {
        $row = array();
        
        foreach ($collumns as $collumn) {
            array_push($row, $collumn);
        }
        
        array_push($this->rows, $row);
    }

    public function render($_container)
    {
        $counter 	= 0;
        $controls 	= $_container->get_controls();

        $row_container;
        $col_container;

        foreach ($this->rows as $row) {
            $row_container = new div("row");
            $row_container->set_class("row");

            foreach ($row as $collumn) {
                $col_container = new div("col");
                $col_container->set_class("col-md-" . $collumn);
                $col_container->add($controls[ $counter++ ]);

                $row_container->add($col_container);
            }

            $row_container->render();
        }
    }
}
