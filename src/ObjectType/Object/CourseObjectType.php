<?php

namespace srag\Plugins\SrAutoMails\ObjectType\Object;

use ilObjCourse;
use ilObjUser;
use srag\Plugins\SrAutoMails\ObjectType\Repository;

/**
 * Class CourseObjectType
 *
 * @package srag\Plugins\SrAutoMails\ObjectType\Object
 */
class CourseObjectType extends ObjObjectType
{

    const OBJECT_PROPERTY_COUNT_COURSE_MEMBERS = "count_course_members";
    const OBJECT_PROPERTY_COUNT_COURSE_MEMBERS_COMPLETED = "count_course_members_completed";
    const OBJECT_PROPERTY_COUNT_COURSE_MEMBERS_NOT_COMPLETED = "count_course_members_not_completed";
    const OBJECT_PROPERTY_COURSE_END = "course_end";
    const OBJECT_PROPERTY_COURSE_START = "course_start";
    const OBJECT_TYPE = Repository::OBJECT_TYPE_COURSE;
    const RECEIVER_COURSE_ADMINISTRATORS = "course_administrators";
    const RECEIVER_COURSE_MEMBERS = "course_members";
    const RECEIVER_COURSE_MEMBERS_COMPLETED = "course_members_completed";
    const RECEIVER_COURSE_MEMBERS_NOT_COMPLETED = "course_members_not_completed";
    const RECEIVER_COURSE_SUPERIOR_OF_MEMBERS = "course_superior_of_members";
    const RECEIVER_COURSE_SUPERIOR_OF_MEMBERS_COMPLETED = "course_superior_of_members_completed";
    const RECEIVER_COURSE_SUPERIOR_OF_MEMBERS_NOT_COMPLETED = "course_superior_of_members_not_completed";
    const RECEIVER_COURSE_TUTORS = "course_tutors";


    /**
     * @inheritDoc
     */
    public function getMailPlaceholderKeyTypes() : array
    {
        return array_merge(parent::getMailPlaceholderKeyTypes(), [
            "object"                => "object " . ilObjCourse::class,
            "members"               => "array " . ilObjUser::class,
            "members_completed"     => "array " . ilObjUser::class,
            "members_not_completed" => "array " . ilObjUser::class
        ]);
    }


    /**
     * @inheritDoc
     */
    public function getObjects() : array
    {
        return self::srAutoMails()->ilias()->courses()->getCourses();
    }


    /**
     * @param ilObjCourse $object
     * @param array       $placeholders
     */
    protected function applyMailPlaceholders($object, array &$placeholders) : void
    {
        $members = array_map(function (int $user_id) : ilObjUser {
            return new ilObjUser($user_id);
        }, $object->getMembersObject()->getMembers());

        $completed = self::srAutoMails()->ilias()->courses()->getCompletedUsers($object->getId());

        $placeholders = array_merge($placeholders, [
            "members"               => $members,
            "members_completed"     => array_filter($members, function (ilObjUser $user) use ($completed) : bool {
                return in_array($user->getId(), $completed);
            }),
            "members_not_completed" => array_filter($members, function (ilObjUser $user) use ($completed) : bool {
                return !in_array($user->getId(), $completed);
            })
        ]);
    }


    /**
     * @inheritDoc
     */
    protected function getObjectProperties() : array
    {
        return [
            self::OBJECT_PROPERTY_COUNT_COURSE_MEMBERS               => self::OBJECT_PROPERTY_COUNT_COURSE_MEMBERS,
            self::OBJECT_PROPERTY_COURSE_START                       => self::OBJECT_PROPERTY_COURSE_START,
            self::OBJECT_PROPERTY_COURSE_END                         => self::OBJECT_PROPERTY_COURSE_END,
            self::OBJECT_PROPERTY_COUNT_COURSE_MEMBERS_COMPLETED     => self::OBJECT_PROPERTY_COUNT_COURSE_MEMBERS_COMPLETED,
            self::OBJECT_PROPERTY_COUNT_COURSE_MEMBERS_NOT_COMPLETED => self::OBJECT_PROPERTY_COUNT_COURSE_MEMBERS_NOT_COMPLETED
        ];
    }


    /**
     * @param ilObjCourse $object
     * @param string      $object_property
     *
     * @return string|int
     */
    protected function getObjectProperty($object, string $object_property)
    {
        switch ($object_property) {
            case self::OBJECT_PROPERTY_COUNT_COURSE_MEMBERS:
                return $object->getMembersObject()->getCountMembers();

            case self::OBJECT_PROPERTY_COURSE_START:
                return $object->getCourseStart();

            case self::OBJECT_PROPERTY_COURSE_END:
                return $object->getCourseEnd();

            case self::OBJECT_PROPERTY_COUNT_COURSE_MEMBERS_COMPLETED:
                $completed = self::srAutoMails()->ilias()->courses()->getCompletedUsers($object->getId());

                $completed = array_filter($object->getMembersObject()->getMembers(), function (int $user_id) use ($completed) : bool {
                    return in_array($user_id, $completed);
                });

                return count($completed);

            case self::OBJECT_PROPERTY_COUNT_COURSE_MEMBERS_NOT_COMPLETED:
                $completed = self::srAutoMails()->ilias()->courses()->getCompletedUsers($object->getId());

                $not_completed = array_filter($object->getMembersObject()->getMembers(), function (int $user_id) use ($completed) : bool {
                    return !in_array($user_id, $completed);
                });

                return count($not_completed);

            default:
                return null;
        }
    }


    /**
     * @param array       $receivers
     * @param ilObjCourse $object
     *
     * @return int[]
     */
    protected function getReceiverForObject(array $receivers, $object) : array
    {
        $completed = self::srAutoMails()->ilias()->courses()->getCompletedUsers($object->getId());

        $array = [];

        foreach ($receivers as $receiver) {
            switch ($receiver) {
                case self::RECEIVER_COURSE_ADMINISTRATORS:
                    $array = array_merge($array, $object->getMembersObject()->getAdmins());
                    break;

                case self::RECEIVER_COURSE_MEMBERS:
                    $array = array_merge($array, $object->getMembersObject()->getMembers());
                    break;

                case self::RECEIVER_COURSE_MEMBERS_COMPLETED:
                    $array = array_merge($array, array_filter($object->getMembersObject()
                        ->getMembers(), function (int $user_id) use ($completed) : bool {
                        return in_array($user_id, $completed);
                    }));
                    break;

                case self::RECEIVER_COURSE_MEMBERS_NOT_COMPLETED:
                    $array = array_merge($array, array_filter($object->getMembersObject()
                        ->getMembers(), function (int $user_id) use ($completed) : bool {
                        return !in_array($user_id, $completed);
                    }));
                    break;

                case self::RECEIVER_COURSE_SUPERIOR_OF_MEMBERS:
                    $array = array_merge($array, self::srAutoMails()->ilias()->orgUnits()->getSuperiorsOfUsers($object->getMembersObject()->getMembers()));
                    break;

                case self::RECEIVER_COURSE_SUPERIOR_OF_MEMBERS_COMPLETED:
                    $array = array_merge($array, self::srAutoMails()->ilias()->orgUnits()->getSuperiorsOfUsers(array_filter($object->getMembersObject()
                        ->getMembers(), function (int $user_id) use ($completed) : bool {
                        return in_array($user_id, $completed);
                    })));
                    break;

                case self::RECEIVER_COURSE_SUPERIOR_OF_MEMBERS_NOT_COMPLETED:
                    $array = array_merge($array, self::srAutoMails()->ilias()->orgUnits()->getSuperiorsOfUsers(array_filter($object->getMembersObject()
                        ->getMembers(), function (int $user_id) use ($completed) : bool {
                        return !in_array($user_id, $completed);
                    })));
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


    /**
     * @inheritDoc
     */
    protected function getReceiverProperties() : array
    {
        return [
            self::RECEIVER_COURSE_ADMINISTRATORS                    => self::RECEIVER_COURSE_ADMINISTRATORS,
            self::RECEIVER_COURSE_MEMBERS                           => self::RECEIVER_COURSE_MEMBERS,
            self::RECEIVER_COURSE_MEMBERS_COMPLETED                 => self::RECEIVER_COURSE_MEMBERS_COMPLETED,
            self::RECEIVER_COURSE_MEMBERS_NOT_COMPLETED             => self::RECEIVER_COURSE_MEMBERS_NOT_COMPLETED,
            self::RECEIVER_COURSE_SUPERIOR_OF_MEMBERS_COMPLETED     => self::RECEIVER_COURSE_SUPERIOR_OF_MEMBERS_COMPLETED,
            self::RECEIVER_COURSE_SUPERIOR_OF_MEMBERS_NOT_COMPLETED => self::RECEIVER_COURSE_SUPERIOR_OF_MEMBERS_NOT_COMPLETED,
            self::RECEIVER_COURSE_SUPERIOR_OF_MEMBERS               => self::RECEIVER_COURSE_SUPERIOR_OF_MEMBERS,
            self::RECEIVER_COURSE_TUTORS                            => self::RECEIVER_COURSE_TUTORS
        ];
    }
}
