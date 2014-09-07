<?php
    require_once('classes/algorithm_bau_sam.php');
    require_once('classes/reverse_polish_notation.php');

    $examples = array('(3 + 4) * (2 / (1 - 5)) - 2', '3 / 0', '( 4 + 5 ) * 7', '2*', '3 + 4 * (2 / (1 - 5)');
    $algorithmBauSam = new algorithmBauSam();
    $algorithmRPN = new algorithmRPN();
?>
<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
        <meta name="format-detection" content="telephone=no">
        <meta name="format-detection" content="address=no">
        <title>Algorithm examples</title>
        <meta name="description" content="Algorithm examples">
        <meta name="keywords" content="Algorithm examples">
        <link href="styles/style.css" rel="stylesheet">
    </head>
    <body>
        <table>
            <thead>
                <tr>
                    <th rowspan="2">Example</th>
                    <th colspan="2">Result</th>
                </tr>
                <tr>
                    <th>Bauer & Samelson algorithm</th>
                    <th>Reverse polish notation (with shunting yard algorithm)</th>
                </tr>
            </thead>
            <tbody>
                <? foreach($examples as $example) :?>
                    <tr>
                        <td><?=$example?></td>
                        <td>
                            <?php
                                try{
                                    echo $algorithmBauSam->calculate($example);
                                } catch(Exception $e){
                                    echo $e->getMessage();
                                }
                            ?>
                        </td>
                        <td>
                            <?php
                            try{
                                echo $algorithmRPN->calculate($example);
                            } catch(Exception $e){
                                echo $e->getMessage();
                            }
                            ?>
                        </td>
                    </tr>
                <? endforeach; ?>
            </tbody>
        </table>
    </body>
</html>