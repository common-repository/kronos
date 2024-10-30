<?php
/**
 * File: class-abbreviation.php
 *
 * @since 2024-09-30
 * @license GPL-3.0-or-later
 *
 * @package kronos/Models
 */

namespace kronos\App\Models;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Abbreviation
 *
 * Represents an abbreviation for a word or phrase.
 */
class Abbreviation {

	/**
	 * This variable is used to store the abbreviation value for a word or phrase.
	 *
	 * @var string $abbreviation The abbreviation of a word or phrase.
	 *
	 * @access private
	 */
	public string $abbreviation;

	/**
	 * This variable stores the name of the event that is being triggered.
	 *
	 * @var string $event The name of the event.
	 *
	 * @access private
	 */
	public string $event;

	/**
	 * This variable is used to store the ID of an entity.
	 *
	 * @var int $id The unique identifier for the entity.
	 *
	 * @access private
	 */
	public int $id;

	/**
	 * Constructs a new instance of the class.
	 *
	 * @param int    $id The ID of the instance.
	 * @param string $abbreviation The abbreviation for the instance.
	 * @param string $event_name The name of the event associated with the instance.
	 */
	public function __construct( int $id, string $abbreviation, string $event_name ) {
		$this->id           = $id;
		$this->abbreviation = $abbreviation;
		$this->event        = $event_name;
	}

	/**
	 * Converts the current object to an array representation.
	 *
	 * @return array The object converted to an array, with the following keys:
	 *   - 'id': The identification of the object.
	 *   - 'abbreviation': The abbreviation property of the object.
	 *   - 'event': The event property of the object.
	 */
	public function to_array(): array {
		return array(
			'id'           => $this->id,
			'abbreviation' => $this->abbreviation,
			'event'        => $this->event,
		);
	}

	/**
	 * Retrieves the abbreviation for a given event name.
	 *
	 * @param string $event_name The event name to retrieve the abbreviation for.
	 * @return string The abbreviation associated with the given event name. If the abbreviation is not found, the event name itself is returned.
	 */
	public static function get_for_event_name( string $event_name ): string {
		foreach ( self::list_all() as $abbreviation ) {
			if ( $abbreviation->event === $event_name ) {
				return $abbreviation->abbreviation;
			}
		}

		return $event_name;
	}

	/**
	 * Updates an abbreviation with the given ID, abbreviation and event name in the kronos_abbreviations option.
	 *
	 * @param int    $id The ID of the abbreviation to update.
	 * @param string $abbreviation The new abbreviation.
	 * @param string $event_name The new event name.
	 *
	 * @return void
	 */
	public static function update( int $id, string $abbreviation, string $event_name ) {
		$abbreviations = self::list_all();
		$data          = array();

		foreach ( $abbreviations as $abbreviation_object ) {
			if ( $abbreviation_object->id === $id ) {
				$abbreviation_object->abbreviation = $abbreviation;
				$abbreviation_object->event        = $event_name;
			}

			$data[] = $abbreviation_object->to_array();
		}

		update_option( 'kronos_abbreviations', $data );
	}

	/**
	 * Deletes an abbreviation from the kronos_abbreviations option based on provided id.
	 *
	 * @param int $id The id of the abbreviation to be deleted.
	 * @return void
	 */
	public static function delete( int $id ) {
		$abbreviations = self::list_all();
		$data          = array();

		foreach ( $abbreviations as $abbreviation_object ) {
			if ( $abbreviation_object->id !== $id ) {
				$data[] = $abbreviation_object->to_array();
			}
		}

		update_option( 'kronos_abbreviations', $data );
	}

	/**
	 * Creates a new abbreviation and stores it in the kronos_abbreviations option.
	 *
	 * @param string $abbreviation The abbreviation to be created.
	 * @param string $event_name The name of the event associated with the abbreviation.
	 * @return void
	 */
	public static function create_new( string $abbreviation, string $event_name ) {
		$new_id        = 0;
		$abbreviations = self::list_all();

		foreach ( $abbreviations as $existing_abbreviation ) {
			if ( $existing_abbreviation->id > $new_id ) {
				$new_id = $existing_abbreviation->id;
			}
		}

		++$new_id;
		$abbreviations[] = new Abbreviation( $new_id, $abbreviation, $event_name );
		$data            = array();
		foreach ( $abbreviations as $current_abbreviation ) {
			$data[] = $current_abbreviation->to_array();
		}

		update_option( 'kronos_abbreviations', $data );
	}

	/**
	 * Retrieves an Abbreviation object by its ID from the list of all abbreviations stored in the kronos_abbreviations option.
	 *
	 * @param int $id The ID of the abbreviation to retrieve.
	 * @return Abbreviation|null The Abbreviation object matching the given ID, or null if no match is found.
	 */
	public static function get_by_id( int $id ): ?Abbreviation {
		foreach ( self::list_all() as $abbreviation ) {
			if ( $abbreviation->id === $id ) {
				return $abbreviation;
			}
		}

		return null;
	}


	/**
	 * Retrieves a list of all abbreviations stored in the kronos_abbreviations option.
	 *
	 * @return array An array of Abbreviation objects representing all stored abbreviations.
	 */
	public static function list_all(): array {
		$abbreviations = array();

		foreach ( get_option( 'kronos_abbreviations', array() ) as $abbreviation_array ) {
			$abbreviations[] = new Abbreviation( $abbreviation_array['id'], $abbreviation_array['abbreviation'], $abbreviation_array['event'] );
		}

		return $abbreviations;
	}
}
