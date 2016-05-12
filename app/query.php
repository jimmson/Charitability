<?php 

class QUERY {

	const LAST_INSERT_ID = "LAST_INSERT_ID()";

	private static $table;
	private static $columns = array();
	private static $values = array();
	private static $where   = array();
	private static $join   = array();

	public static function QTABLE( $_table )
	{
		self::reset();
		self::$table = $_table;
	}

	public static function QCOLUMNS( ...$_columns )
	{
		self::$columns = array_merge( self::$columns, $_columns );
	}

	public static function QVALUES( ...$_values )
	{
		self::$values = array_merge( self::$values, $_values );
	}

	public static function QWHERE( $_condition )
	{
		self::$where[] = " WHERE " . $_condition ;
	}

	public static function QAND( ...$_condition )
	{
		return self::clause( "AND", ...$_condition );
	}

	public static function QOR( ...$_condition )
	{
		return self::clause( "OR", ...$_condition );
	}

	public static function QJOIN( $_table, $_condition, $_table_name = "")
	{
		self::$join[] = " JOIN " . $_table . " " .  $_table_name . " ON " . $_condition ;
	}

	public static function QALIAS( $_table, $_alias )
	{
		return  $_table . self::space("AS") .  $_alias;
	}

	public static function QSELECT()
	{
		$query  = "SELECT ";
		$query .= ( empty( self::$columns ) ? self::space("*") : implode( ", ", self::$columns ) );
		$query .= self::space("FROM");
		$query .= self::$table;
		foreach ( self::$join  as $join  ) $query .= $join;
		foreach ( self::$where as $where ) $query .= $where;
		return $query;
	}

	public static function QINSERT()
	{
		$query  = "INSERT INTO";
		$query .= self::space( self::$table );
		$query .= self::bracket( implode( ",", self::$columns ) );
		$query .= self::space("VALUES");
		$query .= self::bracket( implode( ",", self::$values ) );

		return $query;
	}

	public static function QUPDATE()
	{
		$num_fields = max( count(self::$columns), count(self::$values) ) - 1 ;

		$query  = "UPDATE";
		$query .= self::space(self::$table);
		$query .= self::space("SET");

		for ($item = 0; $item <= $num_fields; $item++) { 
		    $query .= ($item == 0 ? "" : ", ") . self::$columns[$item] . "=" . self::$values[$item];
		} 

		foreach ( self::$where as $where ) $query .= $where;

		return $query;
	}

	public static function condition ( $_column, $_opetator, $_value )
	{
		return $_column . " " . $_opetator . " " . $_value;
	}

	public static function reset ( )
	{
		self::$columns = array();
		self::$values  = array();
		self::$where   = array();
		self::$join    = array();
	}

    public static function clause( $_operator, ...$_condition )
	{
		$comparison = "";

		if ( count( $_condition ) == 1 )
		{
			self::$where[] = self::space( $_operator ) . $_condition[0];
			return null;
		}

		foreach ( $_condition as $condition )
		{
			$comparison = $comparison . ( $comparison == "" ? $condition : self::space( $_operator ) . $condition );
		}

		return "(" . $comparison . ")";	
	}

	public static function quote( $_value ) 
	{
		return "'" . $_value . "'";
	}

	public static function space( $_value ) 
	{
		return " " . $_value . " ";
	}

	public static function bracket( $_value ) 
	{
		return "(" . $_value . ")";
	}

}