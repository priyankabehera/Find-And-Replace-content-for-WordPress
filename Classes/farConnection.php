<?php
	
class FARConnection{
	public function  __construct(){
		
	}
	public function Create(){
		global $wpdb;

		$sql  = "CREATE TABLE IF NOT EXISTS `wp_far` (
				  `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
				  `post_id` int(20) NOT NULL,
				  `post_title` varchar(30) NOT NULL,
				  `post_type` varchar(30) NOT NULL,
				  `post_content` varchar(50) NOT NULL,
				  `edited_content` varchar(30) NOT NULL,
				  `user_name` varchar(30) NOT NULL,
				  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=29";
	
			echo
		$wpdb->query($sql);
		
	}
	public function Select( $fieldname, $tablename, $condition ){
 		global $wpdb;
 		if(!empty($condition)){
			$query = "SELECT ".$fieldname." FROM ".$tablename." WHERE ".$condition;
 		}else{
 			$query = "SELECT ".$fieldname." FROM ".$tablename;
 		}
		$results = $wpdb->get_results( $query, OBJECT );	
		return $results;
	
	}
	public function Update( $data, $tablename, $condtion ){
		global $wpdb;
		$query = "UPDATE $tablename SET post_content='".$rplace_string."' WHERE $condtion";
		$wpdb->query($query);
	}
	public function Insert($data, $tableName){
		global $wpdb;
		 $fieldStr = "";
        $valueStr = "";
        foreach ($data as $field => $value) {
            $value = addslashes($value);
            $valuesStr .= "'$value',";
            $fieldsStr .= "$field,";
        }

        $valuesStr = rtrim($valuesStr, ",");
        $fieldsStr = rtrim($fieldsStr, ",");

        $queryStr = "INSERT INTO $tableName($fieldsStr) VALUES ($valuesStr)";

        $result = $wpdb->query($queryStr);

        if ($result) {
            return $wpdb->insert_id;
        } else {
             return false;
        }
    
	}
	public function Delete($tablename,$condition){
		global $wpdb;
		if(!empty($condition)){
			$query = "DELETE FROM $tablename WHERE $condition";
		}else{
			$query = "DELETE FROM ".$tablename;
		}
		$wpdb->query( $query );
	}
	public function Drop($tablename){
		global $wpdb;
		echo $query = "DROP TABLE ".$tablename;
		$wpdb->query( $query );
	}
}

