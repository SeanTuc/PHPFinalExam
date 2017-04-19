<?php
/**
 * Created by PhpStorm.
 * User: Sean Tucker
 * Student number 200352573
 * Date: 2017-04-19
 * Time: 3:45 PM
 * dashboard features
 */
    include_once('Controllers/users.php');
    CheckIfAuthenticated();
?>

<div class="container">
    <div class="row">
        <div class="col-md-offset-3 col-md-6">
            <h1>Dashboard</h1>

            <ul>

                <li><a href="index.php?pageId=GamesList"><i class="fa fa-gamepad fa-lg" ></i> Games List</a></li>
                <li>================</li>
                <li><a class="btn btn-primary" href="index.php?pageId=GameDetails&gameID=0">
                        <i class="fa fa-plus"></i> Add New Game</a></li>

            </ul>


        </div>
    </div>
</div>