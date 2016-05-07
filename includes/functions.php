<?php

function displayError() {

    if (isset($_SESSION["ERROR"])) {
        echo "<div class=\"display_holder\">";
        echo "<ul class=\"errors_list\">";
        foreach ($_SESSION["ERROR"] as $error) {

            echo "<li>" . $error . "</li>";

        }

        echo "</ul>";
        echo "</div>";
        unset($_SESSION["ERROR"]);

    }

}

function resetGrid($grid) {


    foreach ($grid as $key => $row) {

        foreach ($row as $key => $cell) {

            $cell = 1;

        }

    }

    return $grid;

}

function beginGame() {

    $_SESSION["first_step"] = false;;
    $_SESSION["on_game"] = true;
    $_SESSION["second_player"] = $_POST["second_player_type"];

    switch ($_POST["first_player"]) {

        case '1':
        $_SESSION["turn"] = "1";
        break;

        case '2':
        $_SESSION["turn"] = "2";
        break;

        case 'random':
        $_SESSION["turn"] = ($rand = rand(0, 100) > 50) ? "1" : "2";
        break;

    }

}

function checkLoose($grid) {

    $i = 0;
    foreach ($grid as $key => $row) {

        foreach ($row as $key => $cell) {

            if ($cell === 1) {

                $i++;

            }

        }

    }

    if ($i === 0) {
        $other_player = ($_SESSION["turn"] === "1") ? "2" : "1";

        $_SESSION["endgame"] = true;
        $_SESSION["on_game"] = false;
        $_SESSION["ERROR"]["reason"] = "Mais tu est con ou quoi, tu as pris toi même les dernieres... Joueur n. " .  $other_player . " perd !";

        $_SESSION["leaderboard"][$_SESSION["turn"]] += 1;
        $_SESSION["game_count"] += 1;
        return true;

    } else if ($i === 1) {
        $other_player = ($_SESSION["turn"] === "1") ? "2" : "1";

        $_SESSION["endgame"] = true;
        $_SESSION["on_game"] = false;
        $_SESSION["ERROR"]["reason"] = "Arf, le Joueur n. " . $_SESSION["turn"] . " a perdu !";

        $_SESSION["leaderboard"][$other_player] += 1;
        $_SESSION["game_count"] += 1;

        return true;

    }

}

function calculateRatio($joueur) {

    $nbr_parties = $_SESSION["game_count"];

    if ($_SESSION["leaderboard"][$joueur] === 0) {

        return 0;

    } else {

        $ratio = ($_SESSION["leaderboard"][$joueur] / $nbr_parties) * 100;
        return floor($ratio);

    }

}

function makeMove($grid) {

    if ($_SESSION["on_game"]) {

        $move = [[],[],[],[]];
        $row = 0;
        $tmp_grid = $grid;



        // echo "<br />Le gars à joué : <br/>";
        foreach ($_POST as $key => $value) {

            for($i = 0; $i < 4; $i++) {

                $allu = substr($key, 3, 1);
                if (preg_match("/^r" . $i . "a/", $key) && $tmp_grid[$i][$allu] === 1) {


                    array_push($move[$i], $allu);
                    // echo "Il a pris une allumette dans la row " . $i . "<br />";
                    $tmp_grid[$i][$allu] = 0;

                }

            }

        }

        foreach ($move as $key => $value) {

            if (!empty($value)) {

                $row++;

            }
            if (count($value) > 3) {

                $_SESSION["ERROR"]["toomanyall"] = "Vous ne pouvez sélectionner que 3 allumettes par ligne !";
                return $grid;

            }

        }
        if ($row === 0) {

            $_SESSION["ERROR"]["nomove"] = "Vous devez choisir au moins une allumette !";
            return $grid;

        } else if ($row > 1) {

            $_SESSION["ERROR"]["toomanyrow"] = "Vous pouvez prendre des allumettes dans une seul ligne à la fois !";
            return $grid;

        }


        $_SESSION["turn"] = ($_SESSION["turn"] === "1") ? "2" : "1";
        if (checkLoose($tmp_grid)) {

            return $tmp_grid;

        } else {

            return $tmp_grid;
        }

    }

}