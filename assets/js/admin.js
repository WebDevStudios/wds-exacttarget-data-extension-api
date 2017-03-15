/**
 * file: admin.js
 *
 * Handles Admin Javascript.
 */

if ( ! window.hasOwnProperty( 'WDS_Exacttarget_Data_Extension_API_Admin' ) ) {

	// Init Window object.
	window.WDS_Exacttarget_Data_Extension_API_Admin = {};

	/**
	 * Application.
	 *
	 * @author Aubrey Portwood
	 * @since  NEXT
	 *
	 * @param  {object} window Window object.
	 * @param  {object} $      jQuery object.
	 * @param  {object} app    Application on window object.
	 */
	( function( window, $, app ) {

		// Constructor.
		app.init = function() {
			app.cached = {
				window:  $( window ),
				$inputs: $( '#cmb2-metabox-wds_exacttarget_data_extension_api_admin_metabox input' ),
			};

			if ( app.requirements() ) {
				app.bind();
			}
		};

		// Current AJAX requests.
		app.XHR = false;

		// Combine all events.
		app.bind = function() {

			// When any of the details change, check connection.
			app.cached.$inputs.on( 'change, keyup', app.checkConnection );

			// When the page loads check connection.
			$( document ).on( 'ready', app.checkConnection );
		};

		// Do we meet the requirements?
		app.requirements = function() {
			return true;
		};

		app.checkConnection = function( event ) {
			var clientId     = $( 'input[name="client_id"]' ).val();
			var clientSecret = $( 'input[name="client_secret"]' ).val();

			/**
			 * Mark inputs with success or error.
			 *
			 * @author Aubrey Portwood
			 * @since  NEXT
			 *
			 * @param  {boolean} success True will go with success, false with an error.
			 */
			var success = function( success ) {
				$( app.cached.$inputs ).each( function( i, input ) {
					var $description = $( '+ p.cmb2-metabox-description', $( input ) );
					var $span        = $( 'span', $description );

					// Add a check-mark.
					if ( 'loading' == success ) {

						// Loading.
						$span
							.html( '?' )
							.removeClass( 'error' )
							.removeClass( 'success' );
					} else if ( success ) {

						// Success!
						$span
							.html( '&#10003;' )
							.removeClass( 'error' )
							.addClass( 'success' );
					} else {

						// Error.
						$span
							.html( '!' )
							.removeClass( 'success' )
							.addClass( 'error' );
					}
				} );
			};

			// Before we try the AJAX request, show the icons as loading.
			success( 'loading' );

			if ( app.XHR !== false ) {

				// Abort any previous AJAX request.
				app.XHR.abort();
			}

			// AJAX Request.
			app.XHR = $.ajax( {
				method: 'post',
				url:    ajaxurl,

				// Send this data.
				data: {
					action: 'wds_exacttarget_data_extension_api_check_connection',

					// The client id and secret.
					client_id: clientId,
					client_secret: clientSecret
				},

				// Success.
				success: function( response, status, jqXHR ) {
					if ( response.success ) {

						// Make input look like we had a win!
						success( true );
					} else {

						// Make inputs look like crap because there's an error!
						success( false );
					}

					// Done XHR'ing.
					app.XHR = false;
				},

				// Failure.
				error: function( jqXHR, status, error ) {
					app.XHR = false;
				}
			} );
		};

		// Engage!
		$( app.init );

	} )( window, jQuery, window.WDS_Exacttarget_Data_Extension_API_Admin );
}
