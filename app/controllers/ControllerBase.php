<?php
declare(strict_types = 1);

use Phalcon\Mvc\Controller;

/**
 * Controller housing common functions for controllers
 * MIT
 */
class ControllerBase extends Controller
{
    /**
     *
     * @var string
     */
    private $fields;

    /**
     * Has the array for validation conditions
     * @author Steve Topkin <topkin.steve@gmail.com>
     * @return Array validation conditions for fields
     */
    private function config_validations()
    {
        return [
            'min_chars' => [
                'telephone' => '12', 
                'password' => '6', 
                'valid_string' => '1', 
                'valid_long_string' => '20', 
            ], 
            'max_chars' => [
                'telephone' => '12', 
                'password' => '15', 
                'valid_string' => '25', 
                'valid_long_string' => '200', 
            ], 
            'regex' => [
                'telephone' => '/\+44 [0-9]+/', 
                'password' => '^(((?=.*[a-z])(?=.*[A-Z]))|((?=.*[a-z])(?=.*[0-9]))|((?=.*[A-Z])(?=.*[0-9])))(?=.{6,})', 
                'valid_string' => '/[\'\/~`\!@#\$%\^&\*\(\)_\-\+=\{\}\[\]\|;:"\<\>,\.\?\\\]/'], 
            'present' => [
                'username' => 'Username required', 
                'email' => 'Email required', 
                'password' => 'Password required', 
            ], 
            'valid' => [
                'email' => 'Invalid Email', 
                'telephone' => "Invalid Telephone", 
                'username' => "Invalid Username", 
                'password' => "Invalid Password", 
                'name' => "Invalid Name", 
                'surname' => "Invalid Surname", 
                'job_title' => "Invalid Job Title", 
                'bio' => "Invalid Bio",
            ]
        ];
    }

    /**
     * Assigns validations to fields
     * @author Steve Topkin <topkin.steve@gmail.com>
     * @return Array validation conditions for fields
     */
    private function load_fields()
    {
        return [
            'username' => [
                'id' => 'username', 
                'field' => null, 
                'regex' => $this->config_validations() ['regex']['valid_string'], 
                'present' => $this->config_validations() ['present']['username'], 
                'message' => $this->config_validations() ['valid']['username'], 
            ], 
            'email' => [
                'id' => 'email', 
                'field' => null, 
                'email' => $this->config_validations() ['valid']['email'], 
                'message' => $this->config_validations() ['valid']['username'], 
            ], 
            'password' => [
                'id' => 'password', 
                'field' => null, 
                'present' => $this->config_validations() ['present']['password'], 
                'message' => $this->config_validations() ['valid']['password'], 
            ], 
            'telephone' => [
                'id' => 'telephone', 
                'field' => null, 
                'required' => false, 
                'message' => $this->config_validations() ['valid']['telephone'], 
            ], 
            'name' => [
                'id' => 'name', 
                'field' => null, 
                'required' => false, 
                'regex' => $this->config_validations() ['regex']['valid_string'], 
                'message' => $this->config_validations() ['valid']['name'], 
            ], 
            'surname' => [
                'id' => 'surname', 
                'field' => null, 
                'required' => false, 
                'regex' => $this->config_validations() ['regex']['valid_string'], 
                'message' => $this->config_validations() ['valid']['surname'], 
            ], 
            'job_title' => [
                'id' => 'job_title', 
                'field' => null, 
                'required' => false, 
                'regex' => $this->config_validations() ['regex']['valid_string'], 
                'message' => $this->config_validations() ['valid']['job_title'], 
            ], 
            'bio' => [
                'id' => 'bio', 
                'field' => null, 
                'required' => false, 
                'regex' => $this->config_validations() ['regex']['valid_string'], 
                'message' => $this->config_validations() ['valid']['bio'], 
            ], 
        ];
    }

    /**
     * NOT IN USE, NOT WORKING
     * Tests if a singular field is unique
     *
     * @param \Phalcon\Mvc\Model\ $model
     * @param String $field
     * @param String $value
     * @author Steve Topkin <topkin.steve@gmail.com>
     * @return Phalcon\Http\Response::redirect
     * @return \Phalcon\Mvc\Model\$Model::findFirst
     */
    public function uniqueFields($model, $field, $value)
    {
        $condition = [$field => $value];
        $exists = $model::findFirst(['conditions' => "{$field}=:{$value}:", 'bind' => $condition]);

        return $exists;
    }

    /**
     * Validates fields
     * Inserts, updates db tables
     *
     * Validations not in use
     *
     * Improvments:
     * - Could update function to insert update
     *   accross multiple tables at once
     *
     * @param \Phalcon\Http\Request $request
     * @param \Phalcon\Mvc\Model\ $model
     * @param Any $field_functions DEFAULT NULL
     * @param String $success_redirect
     * @param String $error_redirect
     * @author Steve Topkin <topkin.steve@gmail.com>
     * @return Phalcon\Http\Response::redirect
     */
    //
    public function insertUpdate($request, $model, $field_functions = NULL, $success_redirect, $error_redirect)
    {

        //NB Validations not used, needs more time to get to work
        try
        {
            $validations = new Validations();
            $posts = $request->getPost();

            //Could optimize to load only fields needed
            $this->fields = $this->load_fields();
            $check_vals = [];

            foreach ($posts as $key => $value)
            {
                $this->fields[$key]['field'] = $value;
                $check_vals[] = $this->fields[$key];
            }

            $validations->checkFields($check_vals);

            //Could have validation be specific to table.fields
            /*if(!$validations->valid){
            return $this->response->redirect($redirect);
            }*/

            // Avoid unessesary proccessing if field_functions is NULL
            if (isset($field_functions))
            {
                foreach ($posts as $key => $value)
                {
                    /*
                    Check if the key on field_functions matches field to
                    query in database, then the field gets a modified input
                    */
                    $model->$key = isset($field_functions[$key]) ? $field_functions[$key] : $value;
                }
            }
            else
            {
                foreach ($posts as $key => $value)
                {
                    $model->$key = $value;
                }
            }

            if ($model->save())
            {
                $this
                    ->flashSession
                    ->success("Success");
                return $this
                    ->response
                    ->redirect($success_redirect);
            }
            else
            {
                $this
                    ->flashSession
                    ->error('DB error');
                return $this
                    ->response
                    ->redirect($success_redirect);
            }
        }
        catch(\Exception $e)
        {
            $this
                ->flashSession
                ->error($e);
            return $this
                ->response
                ->redirect($error_redirect);
        }

    }
}

