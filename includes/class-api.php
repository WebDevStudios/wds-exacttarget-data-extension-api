<?php
/**
 * Exacttarget Data Extension API.
 *
 * @since NEXT
 * @package WDS Exacttarget Data Extension API
 */

/**
 * Exacttarget Data Extension API API.
 *
 * This class is the primary class for communicating with the API,
 * you can create a new one and use all the public methods to
 * add/update/remove/get data.
 *
 * All instances of this class will always connect to the connection
 * details saved via the Admin options page, so you cannot pass any
 * connection details to this class; this is because of how the fuelSDK
 * library is designed.
 *
 * Note, the instantiation of this class has to happen after this plugin is
 * loaded, e.g. init, admin_init, wp_loaded, or plugins_loaded or after.
 *
 * @since  NEXT
 * @author Aubrey Portwood, Kellen Mace
 */
class WDS_ET_DE_API {

	/**
	 * The FuelSDK Client.
	 *
	 * @author Aubrey Portwood
	 * @since  NEXT
	 *
	 * @var boolean|ET_Client
	 */
	protected $ET_Client = false;

	/**
	 * Errors.
	 *
	 * This ends up being a new WP_Error object where errors
	 * are tracked. After you create an instance of this class,
	 * you can examine most API errors here.
	 *
	 * @author Aubrey Portwood
	 * @since  NEXT
	 *
	 * @var boolean|WP_Error
	 */
	protected $errors = false;

	/**
	 * Magic getter for properties.
	 *
	 * @since  NEXT
	 * @author Kellen Mace
	 *
	 * @param  string    $field Field to get.
	 *
	 * @throws Exception        Throws an exception if the field is invalid.
	 * @return mixed            The field value.
	 */
	public function __get( $field ) {

		if ( property_exists( $this, $field ) ) {
			return $this->$field;
		}

		throw new Exception( 'Invalid '. __CLASS__ .' property: ' . $field );
	}

	/**
	 * Constructor.
	 *
	 * Creates a place to track errors and connects to the API
	 * using the connection details saved in the WP Admin.
	 *
	 * @since  NEXT
	 * @author Aubrey Portwood
	 */
	public function __construct() {

		// A place to put errors.
		$this->errors = new WP_Error();

		// Connect.
		$this->connect();
	}

	/**
	 * Connect to ExactTarget.
	 *
	 * This will fire off a new ET_Client which will use
	 * the saved connection details in WP Admin. If we create a new
	 * ET_Client without any exceptions being thrown, we connected!
	 *
	 * @author Aubrey Portwood
	 * @since  NEXT
	 *
	 * @return boolean True if we connected, false if not.
	 */
	public function connect() {

		if ( $this->ET_Client && is_a( $this->ET_Client, 'ET_Client' ) ) {

			// We've already connected this.
			return true;
		}

		// Try connecting it.
		try {

			// Try and connect.
			$this->ET_Client = new ET_Client();

			// We connected it.
			return true;

		} catch ( Exception $exception ) {

			// Add the error.
			$this->errors->add( __METHOD__, $exception->getMessage(), $exception );

			// Something went wrong, WDS_ET_DE_API::$ET_Client is still false.
			return false;
		}
	}

	/**
	 * Are we ready to perform a request?
	 *
	 * We need to make sure that the extension we want to work with is
	 * correct and that we are able to connect before we perform any deeper
	 * API operations. This validates the extension name and attempts a
	 * connection.
	 *
	 * Note, if the connection previously failed at __construct, we ensure
	 * that we attempt again here before we try any API operations.
	 *
	 * @author Aubrey Portwood
	 * @since  NEXT
	 *
	 * @param  string $extension_name The Extension Name.
	 * @return boolean                True if we are connected and have a valid extension name.
	 */
	private function ready( $extension_name ) {

		// The Extension Name should be a string!
		if ( ! $extension_name || ! is_string( $extension_name ) ) {

			// Error.
			$this->errors->add( __METHOD__, __( 'Invalid Extension Key.', 'wds-exacttarget-data-extension-api' ), $extension_name );

			// We need a proper extension name.
			return false;
		}

		if ( ! $this->connect() ) {

			// Error.
			$this->errors->add( __METHOD__, __( 'We could not connect to the API.', 'wds-exacttarget-data-extension-api' ), $this->ET_Client );

			// We weren't able to connect a client to Exacttarget.
			return false;
		}

		// We're ready, h@ck the planet!
		return true;
	}

	/**
	 * New Row.
	 *
	 * This ultimatly creates a new ET_DataExtension_Row,
	 * but validates the data we intend to use with it before creating it.
	 *
	 * Also assigns the needed properties needed to use it, which happens
	 * in all CRUD operations in this class.
	 *
	 * @author Aubrey Portwood
	 * @since  NEXT
	 *
	 * @param  string $extension_name The Extension Name.
	 * @param  array $columns         The columns of data.
	 * @return ET_DataExtension_Row   The row.
	 */
	private function row( $extension_name, $columns ) {

		// Validate that we have data that allows us to create a new ET_DataExtension_Row to work with.
		if ( ! is_array( $columns ) || ! is_string( $extension_name ) || ! is_a( $this->ET_Client, 'ET_Client') ) {

			// Add information about why this data does not work.
			$this->errors->add( __METHOD__, __( 'Could not construct row from given data.', 'wds-exacttarget-data-extension-api' ), array(
				__( 'Expecting String.', 'wds-exacttarget-data-extension-api' )           => $extension_name,
				__( 'Expecting Array.', 'wds-exacttarget-data-extension-api' )            => $columns,
				__( 'Expecting ET_Client Object.', 'wds-exacttarget-data-extension-api' ) => $this->ET_Client,
			) );

			// We cannot create a new with this kind of data.
			return false;
		}

		// Create a new row.
		$row = new ET_DataExtension_Row();

		// Auth with our ET_Client.
		$row->authStub = $this->ET_Client;

		// The data.
		$row->props = $columns;

		// The extension we push to.
		$row->Name = $extension_name;

		return $row;
	}

	/**
	 * Remove a row.
	 *
	 * Removed a row from a Data Extension by the primary key.
	 * E.g.: Remove from [this extension] where [primary key column] has [value].
	 *
	 * @author Aubrey Portwood
	 * @since  NEXT
	 *
	 * @param  string $extension_name The Extension Name (Data Extension).
	 * @param  string $primary_key    The primary key.
	 * @param  string $primary_value  The primary key value.
	 *
	 * @return string|bool            False if we weren't able to remove the row,
	 *                                or the primary key of the row if it was removed.
	 */
	public function remove( $extension_name, $primary_key, $primary_value ) {

		if ( ! $this->ready( $extension_name ) ) {

			// We're not ready to continue, nothing to get.
			return false;
		}

		if ( ! is_string( $primary_key ) ) {

			// Add an error, the primary key has to be a string!
			$this->errors->add( __METHOD__, __( 'Primary key must be a string.', 'wds-exacttarget-data-extension-api' ), $primary_key );

			// Nothing to give back.
			return false;
		}

		// Only remove where the primary key column is equal to the primary value.
		$columns = array( $primary_key => $primary_value );

		// New row.
		$row = $this->row( $extension_name, $columns );

		if ( ! $row ) {

			// Can't create row.
			return false;
		}

		// Try to update the row (remember your primary key).
		try {

			// Delete the row.
			$response = method_exists( $row, 'delete' ) ? $row->delete() : false;

			// We had a good status!
			if ( $response && isset( $response->status ) && $response->status ) {

				// Send back the primary key that was removed.
				return $this->get_response_column_value( $response, $primary_key );

			} else {

				// Add the error, the status was bad.
				$this->errors->add( __METHOD__, $response->message, $response );

				// We did not complete the request.
				return false;
			}

		// Catch any issues.
		} catch ( Exception $exception ) {

			// Add the exception as an error.
			$this->errors->add( __METHOD__, $exception->getMessage(), $exception );

			// We did not complete the request.
			return false;
		}
	}

	/**
	 * Sanitize columns of a row.
	 *
	 * Because the API returns an array of objects, we
	 * go through each of them and ensure the data is an
	 * array of arrays with key/value pairs set.
	 *
	 * This ensures that the developer using the self::get() method
	 * can always trust each column's data. This way, when a developer
	 * loops through the columns, they can always expect to get the column
	 * name (key) and the column value (value).
	 *
	 * @author Aubrey Portwood, Kellen Mace
	 * @since  NEXT
	 *
	 * @param  array $columns  The columns.
	 *
	 * @return array           The columns formatted as arrays with key/value pairs.
	 */
	private function sanitize_row( $columns ) {

		if ( ! is_array( $columns ) ) {

			// Nothing here to sanitize, but it should be an array.
			return array();
		}

		// New row.
		$new_row = array();

		foreach ( $columns as $column ) {
			if ( is_object( $column ) && isset( $column->Name, $column->Value ) ) {
				$new_row[] = array(
					'key'   => $column->Name,
					'value' => $column->Value,
				);
			} else {

				// This column seems to not follow the formatting we expect, so throw an error.
				$this->errors->add( __METHOD__, __( 'Column data does not have Name or Value properties.', 'wds-exacttarget-data-extension-api' ), array(
					__( 'Column', 'wds-exacttarget-data-extension-api' ) => $column,
					__( 'Row', 'wds-exacttarget-data-extension-api' )    => $columns,
				) );
			}
		}

		// Send back the new row with the formatted columns.
		return $new_row;
	}

	/**
	 * Get row(s) from a Data Extension.
	 *
	 * If <code>$filter_key</code> and <code>$filter_value</code> are not set,
	 * will return <strong>ALL</strong> the rows for the Extension name given.
	 *
	 * But, if you pass these, it will only return rows where the
	 * column value matches matches the <code>$filter_value</code>.
	 *
	 * E.g.:
	 *
	 *     $row = $api->get( 'DE1', array(
	 *        'Email Address',
	 *        'Phone Number',
	 *     ), 'Primary Key', 1 );
	 *
	 * This would get the columns Email Address and Phone Number from the row(s)
	 * that has the column "Primary Key" set to 1. This is the common usage when
	 * when trying to get a specific unique row.
	 *
	 * E.g.:
	 *
	 *     $all_rows = $api->get( 'DE1', array(
	 *        'Email Address',
	 *     ) );
	 *
	 * This would get the "Email Address" values from all rows in the
	 * "DE1" Data Extension (do not use on large Data Extension tables).
	 *
	 * E.g.:
	 *
	 *     $all_rows = $api->get( 'DE1', array(
	 *        'Email Address',
	 *     ), 'Points', '20', '>' );
	 *
	 * This would get all the "Email Address" values from all rows where the
	 * "Points" columns has a value greater than 20.
	 *
	 * @author Aubrey Portwood
	 * @since  NEXT
	 *
	 * @param  string  $extension_name The Extension Name (Data Extension).
	 * @param  array   $columns        The columns of data to get from the row.
	 * @param  string  $filter_key     The column key, e.g. the primary key column name.
	 * @param  string  $filter_value   The column value, e.g. the primary key value.
	 * @param  string  $clause         Clause for data, e.g.:
	 *                                 Property (equals) Value; Defaults to '='.
	 * @param  boolean $raw            Whether to use API raw data, or sanitized data for columns.
	 *
	 * @return array                   The rows of data, even if none are found.
	 *
	 * @see https://developer.salesforce.com/docs/atlas.en-us.mc-sdks.meta/mc-sdks/data-extension-row-retrieve.htm
	 */
	public function get( $extension_name, $columns, $filter_key = false, $filter_value = false, $clause = '=', $raw = false ) {

		if ( ! $this->ready( $extension_name ) ) {

			// We're not ready to continue, nothing to get.
			return array();
		}

		// New row.
		$row = $this->row( $extension_name, $columns );

		// Rows (empty at first).
		$rows = array();

		if ( ! $row ) {

			// Can't create row.
			return $rows;
		}

		try {

			// Use simple math for different clauses.
			$clause_map = array(

				// equals.
				'equals' => 'equals', // Why not, they may say equals.
				'='      => 'equals',
				'=='     => 'equals', // Lots of ways to test using =
				'==='    => 'equals', // Lots of ways to test using =

				// notEquals.
				'!='     => 'notEquals',
				'!=='    => 'notEquals', // Lots of ways to test using =
				'!==='   => 'notEquals', // Lots of ways to test using =

				// lessThan and greaterThan.
				'<'      => 'lessThan',
				'>'      => 'greaterThan',
			);

			// If they want to filter the data, and the clause is allowed.
			if ( is_string( $filter_key ) && is_string( $filter_value ) && is_string( $clause ) && in_array( $clause, array_keys( $clause_map ) ) ) {

				// Set the filter, which will only get rows where Property (clause) Value.
				$row->filter = array(
					'Property'       => $filter_key,
					'SimpleOperator' => isset( $clause_map[ $clause ] ) ? $clause_map[ $clause ] : 'equals',
					'Value'          => $filter_value,
				);
			}

			// Try and get the rows.
			$get      = $row->get();
			$response = isset( $get->results ) ? $get->results : false;

			// We have an array of data!
			if ( is_array( $response ) ) {

				// Each row.
				foreach ( $response as $row_object ) {

					// The row data.
					$row = isset( $row_object->Properties ) && isset( $row_object->Properties->Property ) ? $row_object->Properties->Property : false;

					// We have columns!
					if ( $row ) {

						// Add the columns of data to a row.
						$rows[] = $raw ? $row : $this->sanitize_row( $row );
					}
				}

				// We have rows!
				if ( ! empty( $rows ) ) {

					// Send back the rows with column data.
					return $rows;
				}

				// We could not get to the data, add an error.
				$this->errors->add( __METHOD__, __( 'We could not get to the ET data.', 'wds-exacttarget-data-extension-api' ), $response );

				// We could not return any data.
				return $rows;

			} else {

				// Add the error, the status was bad.
				$this->errors->add( __METHOD__, __( 'Response returned no array of data.', 'wds-exacttarget-data-extension-api' ), $response );

				// We did not complete the request.
				return $rows;
			}
		} catch ( Exception $exception ) {

			// Add the exception as an error.
			$this->errors->add( __METHOD__, $exception->getMessage(), $exception );

			// We did not complete the request.
			return $rows;
		}
	}

	/**
	 * Update a Data Extension row of data.
	 *
	 * To specify what row (you can only modify one), set the primary key to the
	 * value in the Data Extension, e.g.:
	 *
	 *     // Update "First Name" to "Jane Doe" on Data Extension "DE1" where "Primary Key" is 1.
	 *     $result = $api->update( 'DE1', array(
	 *         'Primary Key'   => '1',
	 *         'First Name'    => 'Jane Doe',
	 *     ) );
	 *
	 * If you specify the <code>$return_column</code>,
	 * e.g.,
	 *
	 *     $primary_key = $api->update( 'DE1', array(), 'Primary Key' );
	 *
	 * This will send the new value set for 'Primary Key' when
	 * completing the request back, eg:
	 *
	 *     $first_name = $api->update( 'DE1', array(), 'First Name' );
	 *
	 * This would return "Jame Doe" when the update is complete. This is primarily
	 * used to get the primary key value when updating a row more easily.
	 *
	 * @author Aubrey Portwood
	 * @since  NEXT
	 *
	 * @param  string $extension_name Extension key (Data Extension).
	 * @param  array $columns         The keys (column) and their values.
	 * @param  string $return_column  The column to get new value from when the update is successful.
	 *
	 * @return mixed                  The response, the primary key value, or false if we could not update the row.
	 *
	 * @see https://developer.salesforce.com/docs/atlas.en-us.mc-sdks.meta/mc-sdks/data-extension-row-update.htm
	 */
	public function update( $extension_name, $columns, $return_column = false ) {

		if ( ! $this->ready( $extension_name ) ) {

			// We're not ready to continue.
			return false;
		}

		// New row.
		$row = $this->row( $extension_name, $columns );

		if ( ! $row ) {

			// Can't create row.
			return false;
		}

		// Try to update the row (remember your primary key).
		try {

			// Try and update the row.
			$response = method_exists( $row, 'patch') ? $row->patch() : false;

			// We had a good status!
			if ( $response && isset( $response->status ) && $response->status ) {

				// If the dev wants the primary key value.
				if ( $return_column ) {

					// Send it back.
					return $this->get_response_column_value( $response, $return_column );
				}

				// Send back the response.
				return $response;

			} else {

				// Add the error, the status was bad.
				$this->errors->add( __METHOD__, $response->message, $response );

				// We did not complete the request.
				return false;
			}

		// Catch any issues.
		} catch ( Exception $exception ) {

			// Add the exception as an error.
			$this->errors->add( __METHOD__, $exception->getMessage(), $exception );

			// We did not complete the request.
			return false;
		}
	}

	/**
	 * Add a row to a Data Extension.
	 *
	 * E.g.:
	 *
	 *     $result = $api->add( 'DE2', array(
	 *         'Primary Key'     => 1,
	 *         'First Name'      => 'Jack',
	 *     ) );
	 *
	 * This would add a row to the "DE2" Data Extension table and set the
	 * column "Primary Key" to 1, which also happens to be the primary key
	 * in Exacttarget, and "First Name" to "Jack".
	 *
	 * @author Aubrey Portwood
	 * @since  NEXT
	 *
	 * @param string      $extension_name The Extension NAme (Data Extension).
	 * @param array       $columns        The keys (column) and their values.
	 * @param string      $return_column  The column to get new value from when the add is successful;
	 *                                    see documentation on WDS_ET_DE_API::update(), as it is similar.
	 *
	 * @return mixed                      The response, primary key value, or false on failure.
	 *
	 * @see https://developer.salesforce.com/docs/atlas.en-us.mc-sdks.meta/mc-sdks/data-extension-row-create.htm
	 * @see WDS_ET_DE_API::update() See documentation here for $return_column for information as they work similarly.
	 */
	public function add( $extension_name, $columns, $return_column = false ) {
		if ( ! $this->ready( $extension_name ) ) {

			// We're not ready to continue.
			return false;
		}

		// New row.
		$row = $this->row( $extension_name, $columns );

		if ( ! $row ) {

			// Have to have a row to work with.
			return false;
		}

		// Try to perform the request.
		try {

			// Post a row.
			$response = method_exists( $row, 'post' ) ? $row->post() : false;

			// Good status.
			if ( $response && isset( $response->status ) && $response->status ) {

				// We completed the request, the status was good!
				if ( $return_column ) {

					// The primary key.
					return $this->get_response_column_value( $response, $return_column );

				// They don't want the primary key (or one is not set).
				} else {

					// The response.
					return $response;
				}
			} else {

				// Add the error, the status was bad.
				$this->errors->add( __METHOD__, $response->message, $response );

				// We did not complete the request.
				return false;
			}

		// Catch any exceptions (failures).
		} catch ( Exception $exception ) {

			// Add the exception as an error.
			$this->errors->add( __METHOD__, $exception->getMessage(), $exception );

			// We did not complete the request.
			return false;
		}

		return false;
	}

	/**
	 * Get the column value from a response.
	 *
	 * This is primarily used in the CRUD methods above so we can
	 * get a column value from a response of rows and columns since
	 * the object structure can be quite complex.
	 *
	 * @author Aubrey Portwood
	 * @since  NEXT
	 *
	 * @param  object $response    Response.
	 * @param  string $primary_key The name of the column.
	 *
	 * @return mixed               The value of the column.
	 */
	private function get_response_column_value( $response, $primary_key ) {

		// We can only get primary keys from these response types.
		$allowed_responses = array( 'ET_Patch', 'ET_Post', 'ET_Delete' );

		if ( ! in_array( get_class( $response ), $allowed_responses ) ) {

			// We can't work with anything other than the $allowed_responses.
			return false;
		}

		// Delete works differently (has different object format).
		if ( is_a( $response, 'ET_Delete' ) ) {
			if ( ! isset( $response->results ) ) {

				// No results.
				return false;
			}

			// The result.
			$result = isset( $response->results[0] ) ? $response->results[0] : false;

			if ( ! $result ) {

				// Still no result.
				return false;
			}

			// The object.
			$object = isset( $result->Object ) ? $result->Object : false;

			if ( ! $object ) {

				// No object.
				return false;
			}

			// The key.
			$key = isset( $object->Keys ) && isset( $object->Keys->Key ) ? $object->Keys->Key : false;

			if ( ! $key ) {

				// No way get primary value w/out key!
				return false;
			}

			if ( isset( $key->Name ) && isset( $key->Value ) && $primary_key == $key->Name ) {

				// The primary key value was found.
				return $key->Value;
			}

			// We did not find a key for the delete response.
			return false;
		}

		// Results (for add and update responses).
		$results = isset( $response->results ) ? $response->results : false;

		if ( ! $results ) {

			// No result to get primary key from.
			return false;
		}

		// Single result.
		$result = isset( $results[0] ) ? $results[0] : false;

		if ( ! $result ) {

			// No single result to get key from.
			return false;
		}

		// Object.
		$object = isset( $result->Object ) ? $result->Object : false;

		if ( ! $object ) {

			// No object.
			return false;
		}

		// The properties (column data) we want to search for a value.
		$properties = isset( $object->Properties ) && isset( $object->Properties->Property ) ? $object->Properties->Property : false;

		if ( ! $properties ) {

			// No properties.
			return false;
		}

		// Validate the properties are an array.
		if ( is_array( $properties ) ) {

			// Loop through the columns.
			foreach ( $properties as $property ) {

				// If the key is set to the key we are asking for, and the value is set.
				if ( isset( $property->Name ) && isset( $property->Value ) && $property->Name == $primary_key ) {

					// This is the value of the primary key we're asking for.
					return $property->Value;
				}
			}
		}

		// No primary key found in this data.
		return false;
	}
}
