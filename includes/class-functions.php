<?php

/*
* @Author 		pluginbazar
* Copyright: 	2015 pluginbazar
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 

class class_mcq_functions{
	
		public function mcq_fn_get_area()
		{
			$mcq_area = array(
				''=>'Select Customer Area',
				101=>'Jahaj Company Moor',
				201=>'Shapla',
				202=>'Lalbag',
				203=>'Modern',
				204=>'Park Moor',
				205=>'Modern',
				206=>'Doshona',
			);
			return $mcq_area;
		}
		
		public function mcq_fn_get_customer_type() {
			return array(
				''=>'Select Customer Type',
				'7-days'=>'7 Days',
				'15-days'=>'15 Days',
				'30-days'=>'30 Days',
				'one-time'=>'One Time',
			);
		}
		
		public function mcq_fn_get_customer_status() {
			return array(
				''	=> 'Select Customer Status',
				9	=> 'Hold',
				1	=> 'Active',
				7	=> 'Suspend',
				
			);
		}
		
		public function mcq_delivery_time() {
			return array(
				''	 => 'Select Delivery Time',
				't1' => array (
					't_name' 	=> 'Schedule 1',
					't_start' 	=> 9,
					't_end' 	=> 11,
				),
				't2' => array (
					't_name' 	=> 'Schedule 2',
					't_start' 	=> 13,
					't_end' 	=> 15,
				),
				't3' => array (
					't_name' 	=> 'Schedule 3',
					't_start' 	=> 18,
					't_end' 	=> 20,
				),
			);
		}
		
		
		
	}
	new class_mcq_functions();