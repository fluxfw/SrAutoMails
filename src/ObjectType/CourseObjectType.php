<?php

namespace srag\Plugins\SrAutoMails\ObjectType;

/**
 * Class CourseObjectType
 *
 * @package srag\Plugins\SrAutoMails\ObjectType
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class CourseObjectType extends ObjectType {

	const OBJECT_TYPE = ObjectTypes::OBJECT_TYPE_COURSE;
	const OBJECT_PROPERTY_COUNT_COURSE_MEMBERS = "countCourseMembers";
	const RECEIVER_COURSE_ADMINISTRATORS = "course_administrators";
	const RECEIVER_COURSE_MEMBERS = "course_members";
	const RECEIVER_COURSE_SUPERIOR_OF_MEMBERS = "course_superior_of_members";
	const RECEIVER_COURSE_TUTORS = "course_tutors";


	/**
	 * @return array
	 */
	public function getObjectProperties(): array {
		return [
			self::OBJECT_PROPERTY_COUNT_COURSE_MEMBERS => self::OBJECT_PROPERTY_COUNT_COURSE_MEMBERS
		];
	}


	/**
	 * @return array
	 */
	public function getReceiverProperties(): array {
		return [
			self::RECEIVER_COURSE_ADMINISTRATORS => self::RECEIVER_COURSE_ADMINISTRATORS,
			self::RECEIVER_COURSE_MEMBERS => self::RECEIVER_COURSE_MEMBERS,
			self::RECEIVER_COURSE_SUPERIOR_OF_MEMBERS => self::RECEIVER_COURSE_SUPERIOR_OF_MEMBERS,
			self::RECEIVER_COURSE_TUTORS => self::RECEIVER_COURSE_TUTORS
		];
	}
}
