<?php
/**
 * UniPg
 * @package Office
 */

/**
 * Class incapsulates parameters of Http-request.
 *
 * @package Office
 *
 */
class Miao_Office_Request
{
	static protected $_instance;

	protected $_varname_request_data_store;

	/**
	 * Находятся значения переменных _GET или _POST
	 *
	 * @var array()
	 */
	protected $_vars;
	/**
	 * Имя метода REQUEST_METHOD
	 *
	 * @var string
	 */
	protected $_method;


	/**
	 * Конструктор класса.
	 * Инициализирует свойства класса
	 *
	 */
	protected function __construct()
	{
		$this->_method = strtoupper( $_SERVER[ 'REQUEST_METHOD' ] );
		$this->resetVars();
		$this->_unsetDataStore();
	}

	/**
	 * Реализация паттерна Singleton
	 *
	 * @return Miao_Office_Request
	 */
	static public function getInstance()
	{
		if ( !isset( self::$_instance ) )
		{
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	static public function getServerHttpHost()
	{
		$result = $_SERVER['HTTP_HOST'];
		return $result;
	}

	public function getMethod()
	{
		return $this->_method;
	}

	/**
	 * Получить значение переменной.
	 * В свойстве класса _vars находятся значения переменных _GET или _POST
	 *
	 * @param string $varName Имя значения
	 * @param string $defaultValue Значение по умолчанию
	 * @param boolean $useNullAsDefault Подставлять значение по умолчанию
	 * @exception Miao_Office_Request_Exception_OnVarNotExists
	 * @return mixed
	 */
	public function getValueOf( $varName, $defaultValue = null, $useNullAsDefault = false )
	{
		if ( !array_key_exists( $varName, $this->_vars ) )
		{
			if ( ( null === $defaultValue ) && ( false === $useNullAsDefault ) )
			{
				throw new Miao_Office_Request_Exception_OnVarNotExists( __METHOD__, $varName );
			}
			$this->_vars[ $varName ] = $defaultValue;
		}
		else if( empty( $this->_vars[ $varName ] ) )
		{
			$this->_vars[ $varName ] = $defaultValue;
		}
		return $this->_vars[ $varName ];
	}

	public function setValueOf( $varName, $value )
	{
		$this->_vars[ $varName ] = $value;
	}

	/**
	 * Поместить в массив $this->_vars в ключ $this->_varname_request_data_store переменную.
	 *
	 * @param string $varName Имя значения
	 * @param mixed $varValue Значение
	 */
	public function setDataStoreValue( $varName, $varValue )
	{
		$this->_vars[ $this->_varname_request_data_store ][ $varName ] = $varValue;
	}

	/**
	 * Получает значение переменной по имени
	 *
	 * @param string $varName
	 * @return mixed
	 */
	public function getDataStoreValue( $varName )
	{
		if ( isset( $this->_vars[ $this->_varname_request_data_store ] )
			&& array_key_exists( $varName, $this->_vars[ $this->_varname_request_data_store ] )
		)
		{
			return $this->_vars[ $this->_varname_request_data_store ][ $varName ];
		}
		throw new Miao_Office_Request_Exception_OnVarNotExists( __METHOD__, $varName );
	}

	/**
	 * Преобразует специальные символы в HTML сущности и удаляет теги.
	 *
	 * @param string $data
	 * @param string $allowable_tags указания тэгов, которые не должны удаляться
	 * @return string
	 */
	public function stripRequestedString( $data, $allowable_tags = '' )
	{
		return htmlspecialchars( strip_tags( trim( $data ), $allowable_tags ) );
	}

	/**
	 * Получить значение свойства _vars
	 *
	 * @return array
	 */
	public function getVars()
	{
		return $this->_vars;
	}
	/**
	 * Переинициализация данных
	 */
	public function resetVars()
	{
		$method = $this->_method;
		if ( $method == 'HEAD' )
		{
			$method = 'GET';
		}
		$this->_vars = $GLOBALS[ '_' . $method ];
	}

	/**
	 * Удалить переменную из свойства класса $this->_vars[ $this->_varname_request_data_store ]
	 *
	 */
	protected function _unsetDataStore()
	{
		if ( isset( $this->_vars[ $this->_varname_request_data_store ] ) )
		{
			unset( $this->_vars[ $this->_varname_request_data_store ] );
		}
		$this->_vars[ $this->_varname_request_data_store ] = array();
	}

	/*
	 * Получение значений серверных переменных. В основном для HTTP_HOST/SERVER_NAME.
	 * */
	public function getServerVar($key)
	{
		$result = false;
		if (in_array($key, array('HTTP_HOST', 'SERVER_NAME')))
		{
			$result = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : false);
		}
		else
		{
			$result = isset($_SERVER[$key]) ? $_SERVER[$key] : false;
		}

		return $result;
	}
}
