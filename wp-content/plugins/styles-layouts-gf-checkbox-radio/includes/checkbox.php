<?php

/***************************************** for checkbox ****************************************************************************/
if ( in_array( $field->type, $checkbox_fields ) && $checkbox_enabled ) {


	$content_xpath = new DomXPath($dom);
	$checkbox_containers = $content_xpath->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' gfield_checkbox ')]");
	if ( $checkbox_containers->length ) {

		$choices = $content_xpath->query("//*[contains(concat(' ', normalize-space(@class), ' '), 'gchoice')]");
		if( $has_old_gf ){
			$choice_containers = $content_xpath->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' gfield_checkbox ')]");
			foreach( $checkbox_containers as $checkbox_container ){
				
				$choices = $checkbox_container->getElementsByTagName( 'li' );

			}
		}
		foreach ( $choices as $container_element ) {
			$container_element->setAttribute( 'class',
			$container_element->getAttribute( 'class' ) . ' pretty'.$classes);

			$label_container = $dom->createElement( 'div' );
			$label_container->setAttribute( 'class', 'state' );
			$container_element->appendChild( $label_container );
			// if icons are enabled for checkboxes
			if( $checkbox_type === 'icon' && $checkbox_icon !== 'none' ){
				$checkbox_icon = str_replace( 'fas-', 'fas ', $checkbox_icon );
				$checkbox_icon = str_replace( 'far-', 'far ', $checkbox_icon );
				$icon_element = $dom->createElement( 'i' );
				$icon_element->setAttribute( 'class', $checkbox_icon . ' icon' );
				$label_container->appendChild( $icon_element );
			}

			// images are enabled for checkboxes
			if( $checkbox_type === 'image' && $checkbox_icon !== 'none' ){
				$image_element = $dom->createElement( 'img' );
				$image_element->setAttribute( 'class', 'image' );
				$image_element->setAttribute( 'src', $checkbox_image );
				$label_container->appendChild( $image_element );
			}

			$label_container->appendChild( $container_element->getElementsByTagName( 'label' )->item( 0 ) );
		}
		$content = utf8_decode( $dom->saveHTML( $dom->documentElement ) );
	}

}
