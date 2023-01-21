<?php

namespace reventz\Classes\Models;
use reventz\Classes\Models\BaseModel;
defined( 'ABSPATH' ) || exit();
class Event extends BaseModel
{
    /**
	 * The table associated with the model.
	 *
	 * @var string
	 */
    protected static $table = 're_events';
	
	protected $guarded = ['id'];

	public static function allWithBookings()
	{
		global $wpdb;
		$eventTableName = $wpdb->prefix . static::$table;
		$bookingsTableName = $wpdb->prefix . 're_bookings';

		$query = "SELECT ".$eventTableName.".id, ".$eventTableName.".name,  COUNT(".$bookingsTableName.".event_id) AS booking_count" .
			" FROM " . $bookingsTableName.
			" RIGHT JOIN ".$eventTableName." ON ".$eventTableName.".id=".$bookingsTableName.".event_id" .
			" GROUP BY ".$eventTableName.".id";


		$rows = $wpdb->get_results($query, ARRAY_A);

		return $rows;
	}
}