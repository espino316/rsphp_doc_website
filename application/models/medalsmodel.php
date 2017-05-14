<?php
      class MedalsModel extends Model {

      	public $id;
	public $country;
	public $event_name;
	public $golds;


        /**
         * Returns an instance of ActorModel
         * @param long $id
         */
        function load($id) {

          $result =
            parent::$db->from($this->getTableName())
            ->where("id", $id)
            ->first();

      		$this->id = $result['id'];
		$this->country = $result['country'];
		$this->event_name = $result['event_name'];
		$this->golds = $result['golds'];


        } // end function load

        /**
         * Save the model to the database table
         * @param string $forceInsert
         */
        function save($forceInsert = FALSE) {

          $params = array(
            			'id' => $this->id
			'country' => $this->country
			'event_name' => $this->event_name
			'golds' => $this->golds
          );

          $where = array(
            'id' => $this->id
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

          if ( $this->id === null ) {
            $this->id =
              parent::$db->from($this->getTableName())
              ->where($params)
              ->max("id");
          }
        } // end function save

      } // end class MedalsModel