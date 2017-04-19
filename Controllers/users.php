<?php
/**
 * Created by PhpStorm.
 * User: Sean Tucker
 * Student number 200352573
 * Date: 2017-04-19
 * Time: 3:45 PM
 * controls the users
 */
include_once("Config/database.php");

    function CheckIfAuthenticated() {
        ////////////////////////////////
        // FIX THIS - ENTER CODE HERE //
        ////////////////////////////////

        if(!isset($_SESSION["is_logged_in"])) {
            // if everything good go to index page
            header('Location: index.php?pageId=Login');
        }

        return true;

    }

    function Login($username, $password) {
        $messages = "";
        try{
            $db = DBConnection();
            $query = "SELECT password, displayName FROM users 
                  WHERE username = :username"; // SQL statement
            $statement = $db->prepare($query); // encapsulate the sql statement
            $statement->bindValue(':username', $username);
            $statement->execute(); // run on the db server

            ////////////////////////////////////
            // THE CODE BELOW NEEDS TO CHANGE //
            ////////////////////////////////////

            $db_password = $statement->fetch();
            if(password_verify($password, $db_password["password"])) {
                $statement->closeCursor(); // close the connection

                if(session_status() != PHP_SESSION_ACTIVE) {
                    session_start();
                }
                $_SESSION["is_logged_in"] = true;
                $_SESSION["displayName"] = $db_password["displayName"];
                // if everything good go to index page
                header('Location: index.php?pageId=Dashboard');
            }
            else {
                $statement->closeCursor(); // close the connection
                $messages = "Invalid Username or Password";
            }
        }
        catch(Exception $e) {
            $messages = $e->getMessage();
        }

        return $messages;
    }

    function Register($username, $password) {
        $messages = "";
        $isUserNameUnique = false;
        try {
            $db = DBConnection();
            $query = "SELECT * FROM users 
                  WHERE username = :username";
            $statement = $db->prepare($query); // encapsulate the sql statement
            $statement->bindValue(':username', $username);
            $statement->execute(); // run on the db server
            if($statement->rowCount() == 1) { // we have a match
                $messages="Invalid Username";
            }
            else {
                $isUserNameUnique = true;
            }
            $statement->closeCursor(); // close the connection
        }
        catch(Exception $e) {
            $messages = $e->getMessage();
        }

        if($isUserNameUnique) {
            try {
                $db = DBConnection();

                ////////////////////////////////////
                // THE CODE BELOW NEEDS TO CHANGE //
                ////////////////////////////////////
                $displayName = $_POST["displayName"];
                $db_password = password_hash($password, PASSWORD_BCRYPT);


                $query = "INSERT INTO users (username, password, displayName) VALUES (:username, :password, :displayName)";
                $statement = $db->prepare($query);
                $statement->bindValue(':username', $username);
                $statement->bindValue(':password', $db_password);
                $statement->bindValue(':displayName', $displayName);
                $statement->execute();
                $statement->closeCursor();

                // if everything good login
                $messages = Login($username, $password);
            } catch (Exception $e) {
                $messages = $e->getMessage();
            }
        }
        return $messages;
    }

    function Logout() {
        if(session_status() != PHP_SESSION_ACTIVE) {
            session_start();
        }
        if(isset($_SESSION["is_logged_in"])) {
            $_SESSION = Array();
            session_destroy();
            // if everything good go to index page
            header('Location: index.php?pageId=Login');
        }
        else {
            header('Location: index.php');
        }
    }
?>