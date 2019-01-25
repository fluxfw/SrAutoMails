<?php

namespace srag\Plugins\SrAutoMails\ObjectType\Object;

use ilLPStatusWrapper;
use ilObjCourse;
use srag\Plugins\SrAutoMails\ObjectType\ObjectTypes;

/**
 * Class CourseObjectType
 *
 * @package srag\Plugins\SrAutoMails\ObjectType\Object
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class CourseObjectType extends ObjObjectType {

	const OBJECT_TYPE = ObjectTypes::OBJECT_TYPE_COURSE;
	const OBJECT_PROPERTY_COUNT_COURSE_MEMBERS = "count_course_members";
	const OBJECT_PROPERTY_COUNT_COURSE_MEMBERS_COMPLETED = "count_course_members_completed";
	const OBJECT_PROPERTY_COUNT_COURSE_MEMBERS_NOT_COMPLETED = "count_course_members_not_completed";
	const OBJECT_PROPERTY_COURSE_START = "course_start";
	const OBJECT_PROPERTY_COURSE_END = "course_end";
	const RECEIVER_COURSE_ADMINISTRATORS = "course_administrators";
	const RECEIVER_COURSE_MEMBERS = "course_members";
	const RECEIVER_COURSE_SUPERIOR_OF_MEMBERS = "course_superior_of_members";
	const RECEIVER_COURSE_TUTORS = "course_tutors";


	/**
	 * @param ilObjCourse $object
	 * @param array       $placeholders
	 */
	protected function applyMailPlaceholders($object, array &$placeholders)/*: void*/ {

	}


	/**
	 * @inheritdoc
	 */
	protected function getObjectProperties(): array {
		return [
			self::OBJECT_PROPERTY_COUNT_COURSE_MEMBERS => self::OBJECT_PROPERTY_COUNT_COURSE_MEMBERS,
			self::OBJECT_PROPERTY_COURSE_START => self::OBJECT_PROPERTY_COURSE_START,
			self::OBJECT_PROPERTY_COURSE_END => self::OBJECT_PROPERTY_COURSE_END,
			self::OBJECT_PROPERTY_COUNT_COURSE_MEMBERS_COMPLETED => self::OBJECT_PROPERTY_COUNT_COURSE_MEMBERS_COMPLETED,
			self::OBJECT_PROPERTY_COUNT_COURSE_MEMBERS_NOT_COMPLETED => self::OBJECT_PROPERTY_COUNT_COURSE_MEMBERS_NOT_COMPLETED
		];
	}


	/**
	 * @param ilObjCourse $object
	 * @param string      $object_property
	 *
	 * @return string|int
	 */
	protected function getObjectProperty($object, string $object_property) {
		switch ($object_property) {
			case self::OBJECT_PROPERTY_COUNT_COURSE_MEMBERS:
				return $object->getMembersObject()->getCountMembers();

			case self::OBJECT_PROPERTY_COURSE_START:
				return $object->getCourseStart();

			case self::OBJECT_PROPERTY_COURSE_END:
				return $object->getCourseEnd();

			case self::OBJECT_PROPERTY_COUNT_COURSE_MEMBERS_COMPLETED:
				$completed = ilLPStatusWrapper::_lookupCompletedForObject($object->getId());

				$completed = array_filter($object->getMembersObject()->getMembers(), function (int $user_id) use ($completed): bool {
					return in_array($user_id, $completed);
				});

				return count($completed);

			case self::OBJECT_PROPERTY_COUNT_COURSE_MEMBERS_NOT_COMPLETED:
				$not_completed = ilLPStatusWrapper::_lookupCompletedForObject($object->getId());

				$not_completed = array_filter($object->getMembersObject()->getMembers(), function (int $user_id) use ($not_completed): bool {
					return !in_array($user_id, $not_completed);
				});

				return count($not_completed);

			default:
				return NULL;
		}
	}


	/**
	 * @inheritdoc
	 */
	public function getObjects(): array {
		return self::ilias()->courses()->getCourses();
	}


	/**
	 * @inheritdoc
	 */
	protected function getReceiverProperties(): array {
		return [
			self::RECEIVER_COURSE_ADMINISTRATORS => self::RECEIVER_COURSE_ADMINISTRATORS,
			self::RECEIVER_COURSE_MEMBERS => self::RECEIVER_COURSE_MEMBERS,
			self::RECEIVER_COURSE_SUPERIOR_OF_MEMBERS => self::RECEIVER_COURSE_SUPERIOR_OF_MEMBERS,
			self::RECEIVER_COURSE_TUTORS => self::RECEIVER_COURSE_TUTORS
		];
	}


	/**
	 * @param array       $receivers
	 * @param ilObjCourse $object
	 *
	 * @return int[]
	 */
	protected function getReceiverForObject(array $receivers, $object): array {
		$array = [];

		foreach ($receivers as $receiver) {
			switch ($receiver) {
				case self::RECEIVER_COURSE_ADMINISTRATORS:
					$array = array_merge($array, $object->getMembersObject()->getAdmins());
					break;

				case self::RECEIVER_COURSE_MEMBERS:
					$array = array_merge($array, $object->getMembersObject()->getMembers());
					break;

				case self::RECEIVER_COURSE_SUPERIOR_OF_MEMBERS:
					$array = array_merge($array, self::ilias()->orgUnits()->getSuperiorsOfUsers($object->getMembersObject()->getMembers()));
					break;

				case self::RECEIVER_COURSE_TUTORS:
					$array = array_merge($array, $object->getMembersObject()->getTutors());
					break;

				default:
					break;
			}
		}

		return $array;
	}
}
