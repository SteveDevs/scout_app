<?php
declare(strict_types=1);

use Phalcon\Validation;

/**
 * Class to handle input validations
 *
 * MIT
 */ 
class Validations
{
    /**
     *
     * @var array
     */
    private $validation;

    /**
     *
     * @var string
     */
    private $valid_array = [];

    /**
     *
     * @var string
     */
    public $messages;

    /**
     *
     * @var string
     */
    public $valid;

    /**
     * Constructor
     * @author Steve Topkin <topkin.steve@gmail.com>
     */
    public function __construct()
    {
        $this->validation = new Validation();
    }

    /**
     * Checks all fields using call_user_func_array to call
     * appropriate functions againts provided fields
     *
     * @param Array $args
     * @author Steve Topkin <topkin.steve@gmail.com>
     * @return Void
     */
    public function checkFields($args){
        $vals = [
            'str_length_min',
            'str_length_max',
            'regex',
            'present',
            'email',
        ];
        foreach ($args as $function => $function_args) {
            foreach($vals as $val){
                $match = isset($function_args[$val]) ? $function_args[$val] : NULL;
                if($match){
                    $pass_args = [];
                    $pass_args[] = $function_args['field'];
                    if($val == 'regex'){
                        $pass_args[] = $function_args['regex'];
                    }
                    $pass_args[] = $function_args['message'];
                    call_user_func_array([$this, $val], $pass_args);
                }
            }            
        } 

        $this->set_props(); 
    }

    /**
     * Validates if present
     *
     * @param String $field
     * @param String $message
     * @author Steve Topkin <topkin.steve@gmail.com>
     * @return Void
     */
    public function present($field, $message){
        $this->validation->add(
            $field,
            new Phalcon\Validation\Validator\PresenceOf(
                [
                    'message' => $message,
                ]
            )
        );
        $this->validation->setFilters($field, 'trim');
        $this->valid_array[] = $this->validation->validate([$field]);
    }

    /**
     * Validates Email
     *
     * @param String $field
     * @param String $message
     * @author Steve Topkin <topkin.steve@gmail.com>
     * @return Void
     */
    public function email($field, $message){
        $this->validation->add(
            $field,
            new Phalcon\Validation\Validator\Email(
                [
                    'message' => $message,
                ]
            )
        );
        $this->validation->setFilters($field, 'trim');
        $this->valid_array[] = $this->validation->validate([$field]);
    }

    /**
     * Valid against regex
     *
     * @param String $field
     * @param String $pattern
     * @param String $message
     * @author Steve Topkin <topkin.steve@gmail.com>
     * @return Void
     */
    public function regex($field, $pattern, $message){
        $this->validation->add(
            $field,
            new Phalcon\Validation\Validator\Regex(
                [
                    'message' => $message,
                    'pattern' => $pattern,
                ]
            )
        );
        $this->validation->setFilters($field, 'trim');
        $this->valid_array[] = $this->validation->validate([$field]);
    }

    /**
     * Valid String min
     *
     * @param String $field
     * @param String $value
     * @param String $message
     * @author Steve Topkin <topkin.steve@gmail.com>
     * @return Void
     */
    public function str_length_min($field, $value, $message){
        $this->validation->add(
            $field,
            new Phalcon\Validation\Validator\StringLength(
                [
                    'messageMinimum' => $message,
                    'min' => $value,                ]
                )
        );
        $this->validation->setFilters($field, 'trim');
        $this->valid_array[] = $this->validation->validate([$field]);
    }

    /**
     * Valid String max
     *
     * @param String $field
     * @param String $value
     * @param String $message
     * @author Steve Topkin <topkin.steve@gmail.com>
     * @return Void
     */
    public function str_length_max($field, $value, $message){
        $this->validation->add(
            $field,
            new Phalcon\Validation\Validator\StringLength(
                [
                    'messageMinimum' => $message,
                    'max' => $value,                ]
                )
        );
        $this->validation->setFilters($field, 'trim');
        $this->valid_array[] = $this->validation->validate([$field]);
    }

    /**
     * Determins if all fields are valid
     * 
     * @author Steve Topkin <topkin.steve@gmail.com>
     * @return Void
     */
    private function set_props(){

        if (count($this->valid_array)) {
            foreach ($this->valid_array as $message) {
                $this->messages = $message['message'] . "\n";
            }
            $this->valid = false;
        }else{
            $this->valid = true;
        }
    }
}

