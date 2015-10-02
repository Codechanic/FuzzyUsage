<?php
/**
 * Created by PhpStorm.
 * User: firomero
 * Date: 10/2/2015
 * Time: 9:21 AM
 */

namespace Tests\Fuzyy;




use CubaExplorer\FuzzySearch\Fuzzy_Logic;

class Fuzzy_LogicTest extends \PHPUnit_Framework_TestCase{

    public function testClass(){
        $fuzzy = new Fuzzy_Logic();
        $class = get_class($fuzzy);
        $this->assertContains('Fuzzy_Logic',$class);
    }

    public function testFuzzify(){
        $fuzzy = new Fuzzy_Logic();
        $fuzzy->clearMembers();
        //input members
        $fuzzy->setInputNames(array('category'));
        $fuzzy->addMember($fuzzy->getInputName(0),'in_low',0,1.5 , 3 ,LINFINITY);
        $fuzzy->addMember($fuzzy->getInputName(0),'in_middle',2,4.5 , 6 ,TRIANGLE);
        $fuzzy->addMember($fuzzy->getInputName(0),'in_high',5,8.5 , 10 ,LINFINITY);
        //output members
        $fuzzy->setOutputNames(array('eval'));
        $fuzzy->addMember($fuzzy->getOutputName(0),'out_low',0, 0.2 ,0.5 ,TRIANGLE);
        $fuzzy->addMember($fuzzy->getOutputName(0),'out_middle',0.4, 0.6 ,0.8 ,TRIANGLE);
        $fuzzy->addMember($fuzzy->getOutputName(0),'out_high',0.7, 0.9,1 ,TRIANGLE);
        /* ---------- set rules table ------------ */
        $fuzzy->clearRules();

        $fuzzy->addRule('IF category.in_low THEN eval.out_low');
        $fuzzy->addRule('IF category.in_middle THEN eval.out_middle');
        $fuzzy->addRule('IF category.in_high THEN eval.out_high');
        /*------------ Remote parameters----------------*/
        $values = array();
        for($i = 1; $i<100; $i++)
        {
            $values[]=mt_rand(1,10);
        }

        $inference = array_map(
            function($item)use($fuzzy){
                $fuzzy->setRealInput('category',intval($item)) ;
                $val = $fuzzy->calcFuzzy();
                return round($val['eval'],3);
            },$values
        );

        $min = min($inference);
        $max = max($inference);
        $media = array_sum($inference)/count($inference);
        $this->assertGreaterThan(0,$min);
        $this->assertGreaterThan(0,$max);
        $this->assertGreaterThan(0,$media);
        $this->assertGreaterThan($min,$max);
        $this->assertGreaterThan(0,count($inference));

    }
}