<?php

namespace reventz\Classes\Models;

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
        
		$create_statement = $this->prepareSQL($data);		
		return $wpdb->query($create_statement);

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

