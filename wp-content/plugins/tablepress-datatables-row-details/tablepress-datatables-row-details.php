<?php
/*
Plugin Name: TablePress Extension: Row Details
Plugin URI: https://tablepress.org/extensions/datatables-row-details/
Description: Extension for TablePress to add the DataTables Row Details functionality
Version: 1.1
Author: Tobias Bäthge
Author URI: https://tobias.baethge.com/
*/

/*
 * Inspired by http://datatables.net/release-datatables/examples/api/row_details.html
 */

/*
 * This Shortcode will transform the first column of the table into the details row:
 * [table id=1 datatables_row_details=true /]
 *
 * This Shortcode will transform the second to fifth column of the table into the details rows:
 * [table id=1 datatables_row_details=true datatables_row_details_columns=B-E /]
 */

/*
 * Register necessary Plugin Filters.
 */
add_filter( 'tablepress_shortcode_table_default_shortcode_atts', 'tablepress_add_shortcode_parameters_row_details' );
add_filter( 'tablepress_table_render_options', 'tablepress_add_extra_css_class', 10, 2 );
add_filter( 'tablepress_table_js_options', 'tablepress_add_row_details_js_options', 10, 3 );
add_filter( 'tablepress_datatables_parameters', 'tablepress_add_row_details_parameters', 10, 4 );
add_filter( 'tablepress_datatables_command', 'tablepress_add_row_details_js_command', 10, 5 );
if ( ! is_admin() ) {
	add_action( 'wp_enqueue_scripts', 'tablepress_enqueue_datatables_row_details_css' );
}

/**
 * Enqueue CSS file.
 *
 * @since 1.0
 */
function tablepress_enqueue_datatables_row_details_css() {
	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
	$css_url = plugins_url( "datatables-row-details{$suffix}.css", __FILE__ );
	wp_enqueue_style( 'datatables-row-details', $css_url, array( 'tablepress-default' ), '1.0' );
}

/**
 * Add "datatables_row_details" as a valid parameter to the [table /] Shortcode.
 *
 * @since 1.0
 *
 * @param array $default_atts Default attributes for the TablePress [table /] Shortcode.
 * @return array Extended attributes for the Shortcode.
 */
function tablepress_add_shortcode_parameters_row_details( $default_atts ) {
	$default_atts['datatables_row_details'] = false;
	$default_atts['datatables_row_details_columns'] = '1';
	return $default_atts;
}

/**
 * Add extra CSS class "tablepress-row-details".
 *
 * @since 1.0
 *
 * @param array $render_options Render options for the table.
 * @param array $table          The table.
 * @return array The modified render options.
 */
function tablepress_add_extra_css_class( $render_options, $table ) {
	if ( $render_options['datatables_row_details'] ) {
		if ( '' !== $render_options['extra_css_classes'] ) {
			$render_options['extra_css_classes'] .= ' ';
		}
		$render_options['extra_css_classes'] .= 'tablepress-row-details';
	}
	return $render_options;
}

/**
 * Pass "datatables_row_details" from Shortcode parameters to JavaScript arguments.
 *
 * @since 1.0
 *
 * @param array  $js_options    Current JS options.
 * @param string $table_id      Table ID.
 * @param array $render_options Render Options.
 * @return array Modified JS options.
 */
function tablepress_add_row_details_js_options( $js_options, $table_id, $render_options ) {
	$js_options['datatables_row_details'] = $render_options['datatables_row_details'];

	// The columns that shall be processed.
	$columns = $render_options['datatables_row_details_columns'];
	// Add all columns to array, if "all" or true value set for the parameter.
	if ( 'all' === $columns || true === $columns ) {
		$columns = '1-' . count( $table['data'][0] );
	}
	// We have a list of columns (possibly with ranges in it).
	$columns = explode( ',', $columns );
	// Support for ranges like 3-6 or A-BA.
	$range_cells = array();
	foreach ( $columns as $key => $value ) {
		$range_dash = strpos( $value, '-' );
		if ( false !== $range_dash ) {
			unset( $columns[ $key ] );
			$start = substr( $value, 0, $range_dash );
			if ( ! is_numeric( $start ) ) {
				$start = TablePress::letter_to_number( $start );
			}
			$end = substr( $value, $range_dash + 1 );
			if ( ! is_numeric( $end ) ) {
				$end = TablePress::letter_to_number( $end );
			}
			$current_range = range( $start, $end );
			$range_cells = array_merge( $range_cells, $current_range );
		}
	}
	$columns = array_merge( $columns, $range_cells );
	// Parse single letters.
	foreach ( $columns as $key => $value ) {
		if ( ! is_numeric( $value ) ) {
			$columns[ $key ] = TablePress::letter_to_number( $value );
		}
	}
	// Remove duplicate entries and sort the array.
	$js_options['datatables_row_details_columns'] = array_unique( $columns, SORT_NUMERIC );

	return $js_options;
}

/**
 * Evaluate "datatables_row_details" parameter and add corresponding JavaScript code, if needed.
 *
 * @since 1.0
 *
 * @param array  $parameters DataTables parameters.
 * @param string $table_id   Table ID.
 * @param string $html_id    HTML ID of the table.
 * @param array  $js_options JS options for DataTables.
 * @return array Extended DataTables parameters.
 */
function tablepress_add_row_details_parameters( $parameters, $table_id, $html_id, $js_options ) {
	if ( $js_options['datatables_row_details'] && ! empty( $js_options['datatables_row_details_columns'] ) ) {
		$columns = implode( ',', $js_options['datatables_row_details_columns'] ); // Subtracting 1 is not necessary, as we've added a new first column, so that the numbers match.
		$parameters['columnDefs'] = '"columnDefs":[{"orderable":false,"targets":[0]},{"visible":false,"targets":[' . $columns . ']}]';
	}
	return $parameters;
}

/**
 * Evaluate "datatables_row_details" parameter and add corresponding JavaScript command, if needed.
 *
 * @since 1.0
 *
 * @param string $command    DataTables command.
 * @param string $html_id    HTML ID of the table.
 * @param array  $parameters DataTables parameters.
 * @param string $table_id   Table ID.
 * @param array  $js_options DataTables JS options.
 * @return string Modified DataTables command.
 */
function tablepress_add_row_details_js_command( $command, $html_id, $parameters, $table_id, $js_options ) {
	if ( ! $js_options['datatables_row_details'] ) {
		return $command;
	}

	$name = str_replace( '-', '_', $html_id );
	$datatables_name = "DT_{$name}";

	$columns = $js_options['datatables_row_details_columns'];
	if ( count( $columns ) > 1 ) {
		// Multiple columns case.
		$row_details_output = '';
		foreach ( $columns as $column ) {
			$row_details_output .= '\'<span class="row-details-left row-details-left-column-' . $column . '">\' + headers[' . $column . '] + \': </span><span class="row-details-right row-details-right-column-' . $column . '">\' + row[' . $column . '] + \'</span><br />\' + ';
		}
		$row_details_output .= '""'; // for the last "+" in the JS
	} else {
		// Single column case.
		$row_details_output = 'row[' . $columns[0] . ']';
	}

	$command = <<<JS
function {$datatables_name}_row_details(row, headers) {
	return {$row_details_output};
}
var	{$name} = $('#{$html_id}'),
	{$datatables_name},
	{$datatables_name}_titles;
	{$name}.find('thead, tfoot').find('tr').prepend( $('<th class="column-1" />') );
	{$name}.find('tbody').find('tr').prepend( $('<td class="column-1 row-details-toggle"><div class="row-details-open" /></td>') );
	{$datatables_name} = {$name}.dataTable({$parameters});
	{$datatables_name}_titles = $.map({$datatables_name}.fnSettings().aoColumns, function(node) {
		var title = node.sTitle;
		if ( '&nbsp;' === title ) {
			title = '';
		}
		return title;
	});
	{$name}.find('tbody').on( 'click', '.row-details-toggle div', function() {
		var	row = $(this).toggleClass('row-details-open').toggleClass('row-details-close').parents('tr').toggleClass('row-details-row-open')[0];
		if ( {$datatables_name}.fnIsOpen( row ) ) {
			{$datatables_name}.fnClose( row );
		} else {
			{$datatables_name}.fnOpen( row, {$datatables_name}_row_details( {$datatables_name}.fnGetData( row ), {$datatables_name}_titles ), 'row-details' );
		}
	} );
JS;
	return $command;
}
