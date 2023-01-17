<?php

namespace reventz\Classes\Models;
defined( 'ABSPATH' ) || exit();
use reventz\Classes\Traits\DBTraits;
class BaseModel
{
	use DBTraits;
    /**
	 * The table associated with the model.
	 *
	 * @var string
	 */
    protected $table;

	protected $fillable;

	public function create($data)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . 're_events';
		$id =  $wpdb->insert(
			$table_name,
			$data
		);

		if(is_wp_error($id)){
			throw new \Exception($id->get_error_message());
		}
		return $id;
	}

	private function prepareSQL($data)
	{
		global $wpdb;
        $table_name = $wpdb->prefix . 're_events';

		$columns = $this->columnize(array_keys($data));
		$parameters = $this->parameterize(array_values($data));

		return "insert into $table_name ($columns) values ('$parameters')";
	}

	public function columnize(array $columns)
    {
        return implode(', ', $columns);
    }

	public function parameterize(array $values)
    {
        return implode("', '", $values);
    }
}

