# WDS Exacttarget Data Extension API 

Activate this plugin to add a library you can use to write data to Data Extensions on Exacttarget. 

E.g.:

_______________

``` php
$api = new WDS_ET_DE_API(); // Note that connection details need to be setup via the Settings page for the plugin for the API to work.
```

## Add a row

``` php
// The email address returned here is the primary key in this case.
$email_address = $api->add( 'Data Extension Name', array(
    'Email Address'   => 'example@example.com',
    'first_name'      => 'First Name',
    'last_name'       => 'Last Name',
    'zip'             => '88888',
), 'Email Address' ); // Note this parameter tells it to return just this field, not the whole row after the add.
```

## Update a row

``` php
// Update row.
$email_address = $api->update( 'Data Extension Name', array(

    // Update by this primary key value.
    'Email Address'   => $email_address,

    // Update these fields.
    'first_name'      => 'First Name 2',
    'last_name'       => 'Last Name 2',
    'zip'             => '77777',
), 'Email Address' );
```

## Get a row

``` php
// The row after insertion.
$row = $api->get( 'Data Extension Name', array(

    // Get these fields.
    'Email Address',
    'first_name',
    'last_name',
    'zip',
), 'Email Address', $email_address ); // Get a row of data where the primary key Email Address is the value of $email_address
```

## Remove a row

``` php
$row_delete = $api->remove( 'Data Extension Name', 'Email Address', $email_address ); // Remove the row where this field is the value $email_address
```

## Get all rows

``` php
$all_rows = $api->get( 'Data Extension Name', array(

    // Get these fields.
    'Email Address',
    'first_name',
    'last_name',
    'zip',
) );
```

## Get rows by filter

``` php
$some_rows = $api->get( 'Data Extension Name', array(

    // Get these fields.
    'Email Address',
    'first_name',
    'last_name',
    'zip',
), 'zip', '77777', '=' ); // Gets all rows where zip = 77777 

$some_rows = $api->get( 'Data Extension Name', array(

    // Get these fields.
    'Email Address',
    'first_name',
    'last_name',
    'zip',
), 'zip', '77777', '!=' ); // Gets all rows where zip is not 77777

$some_rows = $api->get( 'Data Extension Name', array(

    // Get these fields.
    'Email Address',
    'first_name',
    'last_name',
    'zip',
), 'zip', '77777', '>' ); // Gets all rows where zip > 88888
```
