<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Entity of a group 
 *
 * @author Stéphane <jeanphilippe.st@gmail.com>
 */
include_once 'DB.php';

class UserGroupDao {

  /**
   * @param int $trainee_id id of a trainee
   * @return Array $list  return the list of all groups where the trainee is  
   */
  public static function findTraineeGroups($trainee_id) {
    $db = DB::getConnection();
    $sql = "select u.*,g.validated_at from usergroup u join group_member g on g.group_id=u.group_id where closed_at is null and g.person_id=:trainee_id";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(":trainee_id", $trainee_id);
    $stmt->execute();
    $db = null;
    $list = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $list;
  }

  /**
   * 
   * @param int $trainee_id
   * @return Array $list return the list of all avalaible usergroups for a trainee
   */
  //select DISTINCT(u.group_id),u.name,u.creator_id,u.created_at from usergroup u  left  JOIN group_member g on u.group_id=g.group_id WHERE g.person_id!=3 and u.closed_at is null
  public static function findAvalaibleGroups($trainee_id) {
    $db = DB::getConnection();
    $sql = "select u.* from usergroup u where not exists(select 1 from group_member g where g.group_id=u.group_id and g.person_id=:trainee_id)and u.closed_at is null";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(":trainee_id", $trainee_id);
    $stmt->execute();
    $db = null;
    $list = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $list;
  }

  /**
   * 
   * @param int $trainee_id id of a trainee
   * @param type $group_id id of a group
   * @return int $ok return 1 if the insert request was succefull
   */
  public static function join($trainee_id, $group_id) {
    $db = DB::getConnection();
    $sql = "insert into group_member(person_id,group_id) values(:trainee_id,:group_id)";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(":trainee_id", $trainee_id);
    $stmt->bindValue(":group_id", $group_id);
    $ok = $stmt->execute();
    return $ok;
  }

}
