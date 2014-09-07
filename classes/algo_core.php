<?php

class algorithmCore
{

    public $operations = array('(','+','-','*','/',')');

    public function isOperator($var){
        return in_array($var, $this->operations);
    }

    public function prepareExpression($expression)
    {
        $expression = preg_replace('/\s/', '', $expression);
        $doneExpression = array();
        $numeric = '';
        $numericCount = 0;
        for($i = 0, $j = strlen($expression); $i < $j; $i++){
            $char = $expression[$i];
            if($this->isOperator($char)){
                if($numeric > ''){
                    if(!is_numeric($numeric)){
                        throw new Exception('Invalid numeric in the expression "' . $numeric . '"');
                    }
                    $doneExpression[] = $numeric;
                    $numericCount++;
                }
                $doneExpression[] = $char;
                $numeric = '';
            } else {
                $numeric .= $char;
            }
        }
        if($numeric > ''){
            $doneExpression[] = $numeric;
            $numericCount++;
        }
        if($numericCount < 2){
            throw new Exception('Invalid expression');
        }
        return $doneExpression;
    }
}

?>