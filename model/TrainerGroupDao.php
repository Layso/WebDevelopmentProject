<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


/**
 * Entity of a group 
 *
 * @author Kumanan
 * 
 * 
 * 
 */
include_once 'DB.php';

class TrainerGroupDao {

  /**
   * 
   * 
   * @param int $trainer_id is id of trainer
   * @return array $list of open group
   * 
   */
  public static function findOpenTrainerGroups($trainer_id) {

    $db = DB::getConnection();
    $sql = "select u.* from usergroup u WHERE creator_id =:trainer_id and closed_at is null  ";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(":trainer_id", $trainer_id);
    $stmt->execute();
    $db = null;
    $list = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $list;
  }

  /**
   * 
   * @param int $trainer_id is id of trainer
   * @return array $list list of closed group
   * 
   */
  public static function findCloseTrainerGroups($trainer_id) {

    $db = DB::getConnection();
    $sql = "select u.* from usergroup u WHERE creator_id =:trainer_id and closed_at is not null  ";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(":trainer_id", $trainer_id);
    $stmt->execute();
    $db = null;
    $list = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $list;
  }

  /**
   * 
    @param int $group_id
   * @return array $list the number of not validated trainee for group
   * 
   */
  public static function getNumberOfValidatedTrainee($group_id) {

    $db = DB::getConnection();
    $sql = "SELECT COUNT(person_id)as ValidateNumber FROM group_member g where group_id=:group_id AND validated_at is not null ";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(":group_id", $group_id);
    $stmt->execute();
    $db = null;
    $list = $stmt->fetch(PDO::FETCH_ASSOC);
    return $list;
  }

  /**
   * 
   * @param int $group_id
   * @return array $list the number of not validated trainee for group
   */
  public static function getNumberOfNotValidatedTrainee($group_id) {

    $db = DB::getConnection();
    $sql = "SELECT COUNT(person_id)as NotValidateNumber FROM group_member g where group_id=:group_id AND validated_at is null ";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(":group_id", $group_id);
    $stmt->execute();
    $db = null;
    $list = $stmt->fetch(PDO::FETCH_ASSOC);
    return $list;
  }

  /**
   * 
   * @param int $trainer_id is id of trainer
   * @param int $group_id is id of group
   * @return int $ok return 1 if the update was succefull
   * 
   */
  public static function OpenGroup($trainer_id, $group_id) {
    $db = DB::getConnection();
    $sql = "UPDATE usergroup SET closed_at= null WHERE group_id=:group_id AND creator_id=:trainer_id";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(":group_id", $group_id);
    $stmt->bindValue(":trainer_id", $trainer_id);
    $ok = $stmt->execute();
    $db = null;
    return $ok;
  }

  /**
   * 
   * @param int $trainer_id is id of trainer
   * @param type $group_id is id of group
   * @return int $ok return 1 if the update was succefull
   * 
   */
  public static function CloseGroup($trainer_id, $group_id) {
    $db = DB::getConnection();
    $sql = "UPDATE usergroup SET closed_at=now() WHERE group_id=:group_id AND creator_id=:trainer_id";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(":group_id", $group_id);
    $stmt->bindValue(":trainer_id", $trainer_id);
    $ok = $stmt->execute();
    $db = null;
    return $ok;
  }

  /**
   * 
   * @param int $trainer_id id of a trainer
   * @param String $name name choiced for a group
   * @return string $ok return 1 if the insert request is succefull 
   */
  public static function create($trainer_id, $name) {
    $db = DB::getConnection();
    $sql = "insert into usergroup(name,creator_id,created_at) values( :name, :creator_id, now() )";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(":name", $name);
    $stmt->bindValue(":creator_id", $trainer_id);
    $ok = $stmt->execute();
    $db = null;
    return $ok;
  }

  /**
   * 
   * @param int $group_id  id of a group
   * @return array $list return the list of candidates and members for a group
   */
  public static function getMembers($group_id) {
    $db = DB::getConnection();
    $sql = "SELECT person.person_id as trainee_id,name,first_name,group_member.validated_at as member_validated_at FROM group_member JOIN person on group_member.person_id=person.person_id where group_id=:group_id and is_trainer=false";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(":group_id", $group_id);
    $stmt->execute();
    $db = null;
    $list = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $list;
  }

  /**
   * 
   * @param array $members list of members and candidates for a group
   * @return array $filtred list of members and candidates seperated for a group 
   */
  public static function getMembersFiltred($members) {
    $filtred = array();
    $validated = array();
    $candidates = array();
    if (count($members) > 0) {
      foreach ($members as &$member) {
        if ($member['member_validated_at'] === null)
          array_push($candidates, $member);
        else
          array_push($validated, $member);
      }
      $filtred = array("candidates" => $candidates, "validated" => $validated);
    }
    return $filtred;
  }

  /**
   * 
   * @param type $group_id id of a group
   * @param type $trainee_ids ids of trainees
   * @return array $ok  return 1  foreach insert succefull
   */
  public static function validateCandidates($group_id, $trainee_ids) {

    $db = DB::getConnection();
    $sql = "update group_member set validated_at=now() where group_id=:group_id and person_id=:trainee_id";
    $stmt = $db->prepare($sql);
    $ok = array();
    $i = 0;
    foreach ($trainee_ids as $trainee_id) {
      $stmt->bindValue(":group_id", $group_id);
      $stmt->bindValue(":trainee_id", $trainee_id);
      $ok[$i] = $stmt->execute();
      $i++;
    }
    $db = null;
    return $ok;
  }

  /**
   * 
   * @param int $trainee_id
   * @param int $group_id
   * @return int $ok return 1 foreach insert succefull
   */
  public static function remove($trainee_id, $group_id) {
    $db = DB::getConnection();
    $sql = "delete from group_member where group_id=:group_id and person_id=:trainee_id";
    $stmt = $db->prepare($sql);
    $ok = array();
    $i = 0;
    foreach ($trainee_ids as $trainee_id) {
      $stmt->bindValue(":group_id", $group_id);
      $stmt->bindValue(":trainee_id", $trainee_id);
      $ok[$i] = $stmt->execute();
      $i++;
    }
    $db = null;
    return $ok;
  }

}
