<?php
  class OrdersModel extends Model {

  	public $order_id;
	public $customer_id;
	public $order_date;


  	/**
  	 * Returns an instance of ActorModel
  	 * @param long $order_id
  	 */
  	function load($order_id) {

  		$result =
  			parent::$db->from($this->getTableName())
  			->where("order_id", $order_id)
  			->first();

  

  	} // end function load

  	/**
  	 * Save the model to the database table
  	 * @param string $forceInsert
  	 */
  	function save($forceInsert = FALSE) {

  		$params = array(
        			0
			0

  		);

  		$where = array(
  			'order_id' => $this->order_id
  		);

  		if ($forceInsert) {
  			parent::$db->insert(
  				$this->getTableName(),
  				$params
  			);
  		} else {
  			parent::$db->upsert(
  				$this->getTableName(),
  				$params,
  				$where
  			);
  		} // end if then else

  		if ( $this->order_id === null ) {
  			$this->order_id =
  				parent::$db->from($this->getTableName())
  				->where($params)
  				->max("order_id");
  		}
  	} // end function save

  } // end class OrdersModel