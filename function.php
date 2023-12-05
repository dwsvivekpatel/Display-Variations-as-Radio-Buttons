<?php 
//Add this code in the function.php file in your theme

add_filter( 'woocommerce_dropdown_variation_attribute_options_html', 'rudr_radio_variations', 20, 2 );
function rudr_radio_variations( $html, $args ) {

	// in wc_dropdown_variation_attribute_options() they also extract all the array elements into variables
	$options   = $args[ 'options' ];
	$product   = $args[ 'product' ];
	$attribute = $args[ 'attribute' ];
	$name      = $args[ 'name' ] ? $args[ 'name' ] : 'attribute_' . sanitize_title( $attribute );
	$id        = $args[ 'id' ] ? $args[ 'id' ] : sanitize_title( $attribute );
	$class     = $args[ 'class' ];

	if( empty( $options ) || ! $product ) {
		return $html;
	}
	
	// HTML for our radio buttons
	$radios = '<div class="rudr-variation-radios">';

	// taxonomy-based attributes
	if( taxonomy_exists( $attribute ) ) {

		$terms = wc_get_product_terms(
			$product->get_id(),
			$attribute,
			array(
				'fields' => 'all',
			)
		);

		foreach( $terms as $term ) {
			if( in_array( $term->slug, $options, true ) ) {
				$radios .= "<input type=\"radio\" id=\"{$name}-{$term->slug}\" name=\"{$name}\" value=\"{$term->slug}\"" . checked( $args[ 'selected' ], $term->slug, false ) . "><label for=\"{$name}-{$term->slug}\">{$term->name}</label><br />";
			}
		}
	// individual product attributes
	} else {
		foreach( $options as $option ) {
			$checked = sanitize_title( $args[ 'selected' ] ) === $args[ 'selected' ] ? checked( $args[ 'selected' ], sanitize_title( $option ), false ) : checked( $args[ 'selected' ], $option, false );
			$radios .= "<input type=\"radio\" id=\"{$name}-{$option}\" name=\"{$name}\" value=\"{$option}\" id=\"{$option}\" {$checked}><label for=\"{$name}-{$option}\">{$option}</label>";
		}
	}
  
	$radios .= '</div>';

	return $html . $radios;
	
}

