<?php

namespace reventz\Classes\Models;
defined( 'ABSPATH' ) || exit();

use Exception;
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
	protected $guarded = ['id'];


	public function create($data) : int
	{
		$data = $this->removeGuardedColumns($data);

		global $wpdb;
		$tableName = $wpdb->prefix . $this->table;
		$dataInserted =  $wpdb->insert(
			$tableName,
			$data
		);
		if(!$dataInserted){
			throw new Exception($wpdb->last_error);
		}
		return $wpdb->insert_id;;
	}

	private function removeGuardedColumns($data)
	{
		foreach ($this->guarded as $key => $columnName) {
			unset($data[$columnName]);
		}

		return $data;
	}

	private function prepareSQL($data)
	{
		global $wpdb;
        $tableName = $wpdb->prefix . $this->table ;

		$columns = $this->columnize(array_keys($data));
		$parameters = $this->parameterize(array_values($data));

		return "insert into $tableName ($columns) values ('$parameters')";
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

