<?php

/***************************************** for radio ****************************************************************************/

if ( in_array( $field->type, $radio_fields ) && $radio_enabled) {

	$content_xpath = new DomXPath($dom);
	$radio_containers = $content_xpath->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' gfield_radio ')]");



	if ( $radio_containers->length ) {

		$choices = $content_xpath->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' gchoice ')]");

		if( $has_old_gf ){
			$choice_containers = $content_xpath->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' gfield_radio ')]");
			foreach( $radio_containers as $radio_container ){
				
				$choices = $radio_container->getElementsByTagName( 'li' );

			}
		}


		foreach ( $choices as $container_element ) {

			$container_element->setAttribute( 'class',
			$container_element->getAttribute( 'class' ) . ' pretty '. $radio_classes );

			$label_container = $dom->createElement( 'div' );
			$label_container->setAttribute( 'class', 'state' );
			$container_element->appendChild( $label_container );
			
			// if icons are enabled for radios
			if( $radio_type === 'icon' && $radio_icon !== 'none' ){
				$radio_icon = str_replace( 'fas-', 'fas ', $radio_icon );
				$radio_icon = str_replace( 'far-', 'far ', $radio_icon );
				$icon_element = $dom->createElement( 'i' );
				$icon_element->setAttribute( 'class', $radio_icon . ' icon' );
				$label_container->appendChild( $icon_element );
			}

			$labels = $container_element->getElementsByTagName( 'label' );
			foreach( $labels as $label ) {
				$label_container->appendChild( $label );
			}

			// Dealing with the other choice
			$input_elements = $container_element->getElementsByTagName( 'input' );

			//only use the first element .. if other choice is enable then it has 2 inputs
			if( isset( $input_elements[0] ) ) {
				$input_value = $input_elements[0]->getAttribute( 'value' );


				// other radio button 
				if ( strpos( $input_value, 'gf_other_choice' ) !== false ) { 

					// Add custom class to state
					$label_container->setAttribute( 'class', $label_container->getAttribute('class').' stla-radio-other-state' );

					// Create a label
					$label = $dom->createElement( 'label' );

					$input_elements[0]->setAttribute( 'onfocus', "jQuery(this).parent('li').find('.stla-radio-other-label input').focus();" );
					
					// Other input text field
					$defualt_onfocus = $input_elements[1]->getAttribute('onfocus') ;
					$new_onfocus = str_replace( "jQuery(this).prev(\"input\")[0].click();", "jQuery(this).closest('li').find('input[value=\"gf_other_choice\" ]').click();", $defualt_onfocus);

					$input_elements[1]->setAttribute( 'onfocus', $new_onfocus );
					$label_container->appendChild( $label );

					$brs = $container_element->getElementsByTagName( 'br' );


					$label->appendChild( $input_elements[1] );
					foreach( $brs as $br ){
						$container_element->appendChild( $br );

					}

				}
			}

		}

	}
	$content = utf8_decode($dom->saveHTML($dom->documentElement));

}
