<?php

include_once 'DB.php';

class QuizTempDao {

    /**
     * 
     * @param int $trainer_id id of trainer
     * @return array $list list of quizzes that are created by given trainer or public
     */
    public static function getAvailableQuizzes($trainer_id) {
        $db = DB::getConnection();
        $sql = "select * from sql_quiz where is_public=1 or author_id=7";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(":trainer_id", $trainer_id);
        $stmt->execute();
        $list = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $list;
    }


    /**
     * @param string $quiz_name name of the quiz
     * @return int $id id of the quiz
     */
    public static function getQuizIdByName($quiz_name) {
        $db = DB::getConnection();
        $sql = "select quiz_id from sql_quiz where title=:name";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(":name", $quiz_name);
        $stmt->execute();
        $list = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $list[0]["quiz_id"];
    }
}
