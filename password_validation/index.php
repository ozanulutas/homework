<?php

class Validation {

    protected $password;
    public ValidationRule $validationRule;


    public function __construct($rules = array()) {        
        $this->validationRule = new ValidationRule($rules);    
    }

    public function validate($password) {
        $this->validationRule->password = $password;
        return $this->validationRule->validateRules();
    }
}


class ValidationRule extends Validation {

    public $validationRules = [
        'minLength',
        'maxLength',
        'specialChar',
    ];


    public function __construct($rules = array()) {

        foreach($rules as $rule => $value) {
            $this->validationRules[$rule] = $value;
        }
    }

    public function validateRules() {

        foreach($this->validationRules as $rule => $value) {
            
            if(isset($value)) {

                $ruleMethod = $rule . "Rule";
                if(method_exists($this, $ruleMethod)) {                       
                    
                    $result = $this->$ruleMethod();  
                    if($result == false) return false;                        
                }
            }
        }
        return true;
    }

    private function minLengthRule() {               
        return strlen($this->password) >= $this->validationRules['minLength'] ? true : false;
    }

    private function maxLengthRule() {
        return strlen($this->password) <= $this->validationRules['maxLength'] ? true : false;
    }

    private function specialCharRule() {
        
        if($this->validationRules['specialChar'])
            return preg_match("/[^a-zA-Z0-9]+/", $this->password) ? true : false;
        else
            return true;
    }
}


$password = '12123-';

$validation = new Validation([
    'minLength' => 6,
    'maxLength' => 12,
    'specialChar' => true,
]);

if($validation->validate($password))
    echo "Good to go";
else
    echo "Invalid password";

