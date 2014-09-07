<?php

require_once('algo_core.php');

// 	Reverse Polish Notation
class algorithmRPN extends algorithmCore
{

    private function priority($el, $seEl){
        return (($el == '*' || $el == '/') && ($seEl == '+' || $seEl == '-'));
    }

    private function shuntingYardAlgorithm($expression){
        $output = array();
        $stack = array();

        foreach($expression as $el){
            if($this->isOperator($el)){
                switch($el){
                    case '(':
                        array_unshift($stack, $el);
                        break;
                    case ')':
                        do{
                            if(!count($stack)){
                                throw new Exception('Wrong brackets');
                            }
                            if($stack[0] == '('){
                                break;
                            }
                            $output[] = array_shift($stack);
                            if(!count($stack)){
                                throw new Exception('Wrong brackets');
                            }
                        } while($stack[0] != '(');
                        array_shift($stack);
                        break;
                    default:
                        while(count($stack)){
                            if($stack[0] == '(' || $stack[0] == ')'){
                                break;
                            }
                            if($this->priority($el, $stack[0])){
                                break;
                            }
                            $output[] = array_shift($stack);
                        }
                        array_unshift($stack, $el);
                }
            } else {
                $output[] = $el;
            }
        }

        foreach($stack as $el){
            if($el =='(' || $el == ')'){
                throw new Exception('Wrong brackets');
            }
            $output[] = $el;
        }
        return $output;
    }


    public function calculate($expression)
    {
        $doneExpression = $this->prepareExpression($expression);
        $rpnStack = $this->shuntingYardAlgorithm($doneExpression);

        do {
            $resultStack = array();
            foreach($rpnStack as $el){
                if($this->isOperator($el)){
                    $sEl = array_pop($resultStack);
                    $fEl = array_pop($resultStack);
                    switch($el){
                        case '+':
                            array_push($resultStack, $fEl + $sEl);
                            break;
                        case '-':
                            array_push($resultStack, $fEl - $sEl);
                            break;
                        case '*':
                            array_push($resultStack, $fEl * $sEl);
                            break;
                        case '/':
                            if($sEl == 0){
                                throw new Exception('Division by zero');
                            }
                            array_push($resultStack, $fEl / $sEl);
                            break;
                    }
                } else {
                    $resultStack[] = $el;
                }
            }
            $rpnStack = $resultStack;
        } while(count($resultStack) > 1);

        return $resultStack[0];
    }


}
?>