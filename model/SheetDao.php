<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SheetDao
 *
 * @author Stéphane
 */
include_once 'DB.php';

class SheetDao {

  /**
   * 
   * @param type $trainee_id id of the trainee
   * @param type $eval_id id of the evaluation
   * @return type $ok return 1 if the update was succefull
   */
  public static function start($trainee_id, $eval_id) {
    $db = DB::getConnection();
    $sql = "update sheet set started_at=now() where evaluation_id=:eval_id and trainee_id=:trainee_id and started_at is null";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(":eval_id", $eval_id);
    $stmt->bindValue(":trainee_id", $trainee_id);
    $ok = $stmt->execute();
    $db = null;
    return $ok;
  }

  /**
   * 
   * @param type $trainee_id id of the trainee
   * @param type $eval_id id of the evaluation
   * @return type return a sheet if the interrogation request was succefull
   */
  public static function get($trainee_id, $eval_id) {
    $db = DB::getConnection();
    $sql = "select sql_question.question_id,question_text,answer from sql_question natural join sheet_answer where trainee_id=:trainee_id and evaluation_id=:eval_id";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(":eval_id", $eval_id);
    $stmt->bindValue(":trainee_id", $trainee_id);
    $stmt->execute();
    $db = null;
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }


   /**
   * 
   * @param type $trainee_id id of the trainee
   * @param type $eval_id id of the evaluation
   * @return array return an array of answers given for sheet
   */
  public static function getAnswers($trainee_id, $evaluation_id) {
    $db = DB::getConnection();
    $sql = "select * from sheet_answer where trainee_id=:trainee_id and evaluation_id=:evaluation_id";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(":evaluation_id", $evaluation_id);
    $stmt->bindValue(":trainee_id", $trainee_id);
    $stmt->execute();
    $db = null;
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
   * 
   * @param int $trainee_id  id of the trainee
   * @param int $eval_id id of the evaluation
   * @param int $question_id id of the question
   * @param String $answer answer of the question give by the trainee
   * @return int $ok return if the update request was succefull
   */
  public static function updateAnswer($trainee_id, $eval_id, $question_id, $answer) {
    $db = DB::getConnection();
    $sql = "update sheet_answer set answer=:answer,given_at=now() where evaluation_id=:eval_id and trainee_id=:trainee_id and question_id=:question_id";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(":eval_id", $eval_id);
    $stmt->bindValue(":trainee_id", $trainee_id);
    $stmt->bindValue(":question_id", $question_id);
    $stmt->bindValue(":answer", $answer);
    $ok = $stmt->execute();
    $db = null;
    return $ok;
  }

  /**
   * 
   * @param int $trainee_id id of the trainee
   * @param int $eval_id id of the evaluation
   * @return int $ok return if the update request was succefull
   */
  public static function setCompleted($trainee_id, $eval_id) {
    $db = DB::getConnection();
    $sql = "update sheet set ended_at=now() where evaluation_id=:eval_id and trainee_id=:trainee_id";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(":eval_id", $eval_id);
    $stmt->bindValue(":trainee_id", $trainee_id);
    $ok = $stmt->execute();
    $db = null;
    return $ok;
  }


  /**
   * 
   * @param int $evaluation_id id of evaluation to get sheets of
   * @return array list of sheets corresponds to given evaluation
   */
  public static function getSheetsByEvaluation($evaluation_id) {
    $db = DB::getConnection();
    $sql = "select * from sheet where evaluation_id=:evaluation_id";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(":evaluation_id", $evaluation_id);
    $ok = $stmt->execute();
    $db = null;
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }


  /**
   * 
   * @param int $evaluation_id id of evaluation to get sheets of
   * @return array list of sheets joined with name of the user for that sheet
   */
  public static function getAllSheetInfo($evaluation_id) {
    $db = DB::getConnection();
    $sql = "SELECT person.name, person.first_name, sheet.trainee_id, sheet.started_at, sheet.ended_at, sheet.corrected_at
            FROM person
            INNER JOIN sheet ON sheet.trainee_id = person.person_id
            WHERE sheet.evaluation_id=:evaluation_id";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(":evaluation_id", $evaluation_id);
    $ok = $stmt->execute();
    $db = null;
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}
