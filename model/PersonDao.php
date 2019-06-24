<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Entity of a Person 
 *
 * @author Stéphane <jeanphilippe.st@gmail.com>
 */
include_once 'DB.php';

class PersonDao {

    /**
     * @author Stéphane <jeanphilippe.st@gmail.com>
     * @param string $firstname first name of a person
     * @param string $lastname last name of a person
     * @param string $email email of a person
     * @param string $password password of a person
     * @return int return 1 if the insert was succefull
     * 
     */
    public static function insert($firstname, $lastname, $email, $password, $token) {
        $db = DB::getConnection();
        $sql = "insert into person(first_name,name,email,pwd,is_trainer,created_at,token) values(:firstname,:name,:email,:password,false,now(),:token)";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(":firstname", $firstname);
        $stmt->bindValue(":name", $lastname);
        $stmt->bindValue(":email", $email);
        $stmt->bindValue(":password", $password);
        $stmt->bindValue(":token", $token);
        $ok = $stmt->execute();
        $user_id = $db->lastInsertId();
        $db = null;
        return $user_id;
    }

    /**
     * 
     * @param string $email email of a preson
     * @param string $password of a person
     * @return int return 1 if the interrogation request was succefull
     */
    public static function get($email, $password) {
        $db = DB::getConnection();
        $sql = "select person_id,first_name,name,email,is_trainer,validated_at from person where email=:email and pwd=:password";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(":email", $email);
        $stmt->bindValue(":password", $password);
        $ok = $stmt->execute();
        $db = null;
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * 
     * @param int $person_id id of a person
     * @param String $token token used to validate a person
     * @return int $ok return 1 if the update request was succefull
     */
    public static function valid($person_id, $token) {
        $db = DB::getConnection();
        $sql = "update person set validated_at=now() where person_id=:person_id and token=:token and validated_at is null";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(":person_id", $person_id);
        $stmt->bindValue(":token", $token);
        $ok = $stmt->execute();
        $db = null;
        return $ok;
    }

}
