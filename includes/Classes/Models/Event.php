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
    protected $table = 're_events';

	protected $guarded = ['id'];
}