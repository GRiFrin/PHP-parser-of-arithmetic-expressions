<?php

require_once('algo_core.php');

// 	Bauer & Samelson algorithm
class algorithmBauSam extends algorithmCore
{


    private $tranStack = array();
    private $intStack = array();

    private function calc()
    {
        if(!count($this->tranStack) || !count($this->intStack)){
            throw new Exception('Wrong number of operands or values');
        }

        $operand = array_shift($this->tranStack);
        $varSecond = array_shift($this->intStack);
        $varFirst = array_shift($this->intStack);

        switch($operand){
            case '+':
                return array_unshift($this->intStack, $varFirst + $varSecond);
            case '-':
                return array_unshift($this->intStack, $varFirst - $varSecond);
            case '*':
                return array_unshift($this->intStack, $varFirst * $varSecond);
            case '/':
                if($varSecond == 0){
                    throw new Exception('Division by zero');
                }
                return array_unshift($this->intStack, $varFirst / $varSecond);
            default:
                throw new Exception('Invalid character in the expression "' . $operand . '"');
        }
    }

    private function inStack($var)
    {
        return array_unshift($this->tranStack, $var);
    }

    private function outStack()
    {
        return array_shift($this->tranStack);
    }

    private function calcStack($var)
    {
        $this->calc();
        $this->inStack($var);
    }

    private function calcStackRecursive($var)
    {
        $this->calc();
        $action = $this->getOperation($var);
        if($action == 'finish'){
            return;
        }
        $this->$action($var);
    }

    private function raiseError(){
        throw new Exception('Wrong brackets');
    }

    private function getOperation($var)
    {
        $operationTable = array(
            'empty' => array(
                'empty' => 'finish',
                '('     => 'inStack',
                '+'     => 'inStack',
                '-'     => 'inStack',
                '*'     => 'inStack',
                '/'     => 'inStack',
                ')'     => 'raiseError'
            ),
            '('     => array(
                'empty' => 'raiseError',
                '('     => 'inStack',
                '+'     => 'inStack',
                '-'     => 'inStack',
                '*'     => 'inStack',
                '/'     => 'inStack',
                ')'     => 'outStack'
            ),
            '+'     => array(
                'empty' => 'calcStackRecursive',
                '('     => 'inStack',
                '+'     => 'calcStack',
                '-'     => 'calcStack',
                '*'     => 'inStack',
                '/'     => 'inStack',
                ')'     => 'calcStackRecursive'
            ),
            '-'     => array(
                'empty' => 'calcStackRecursive',
                '('     => 'inStack',
                '+'     => 'calcStack',
                '-'     => 'calcStack',
                '*'     => 'inStack',
                '/'     => 'inStack',
                ')'     => 'calcStackRecursive'
            ),
            '*'     => array(
                'empty' => 'calcStackRecursive',
                '('     => 'inStack',
                '+'     => 'calcStackRecursive',
                '-'     => 'calcStackRecursive',
                '*'     => 'calcStack',
                '/'     => 'calcStack',
                ')'     => 'calcStackRecursive'
            ),
            '/'     => array(
                'empty' => 'calcStackRecursive',
                '('     => 'inStack',
                '+'     => 'calcStackRecursive',
                '-'     => 'calcStackRecursive',
                '*'     => 'calcStack',
                '/'     => 'calcStack',
                ')'     => 'calcStackRecursive'
            )
        );
        $key = empty($this->tranStack) ? 'empty' : $this->tranStack[0];

        return $operationTable[$key][$var];
    }

    public function calculate($expression)
    {
        $doneExpression = $this->prepareExpression($expression);

        $this->tranStack = array();
        $this->intStack = array();
        foreach($doneExpression as $el){
            if($this->isOperator($el)){
                $action = $this->getOperation($el);
                $this->$action($el);
            } else {
                array_unshift($this->intStack, $el);
            }
        }
        while(count($this->tranStack)){
            $action = $this->getOperation('empty');
            $this->$action('empty');
        }

        return $this->intStack[0];
    }
}
?>