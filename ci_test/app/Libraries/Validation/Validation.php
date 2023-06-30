<?php
namespace App\Libraries\Validation;

class Validation extends \githusband\Validation
{
    protected $customLangPath = __DIR__ . '/Language/';

    public function __construct(array $config = [])
    {
        parent::__construct($config);
        $this->customConfig();
    }

    protected function customConfig()
    {
        $this->config['lang_path'] = $this->customLangPath;
        $this->set_language('ApiTemplate');
    }

    public function getInvalidParameters()
    {
        $error = $this->get_error(false,true);
        $invalidParameters = [];
        foreach ($error as $key => $value) {
            $invalidParameters[] = [
                'parameter' => $key,
                'reason' => $value
            ];
        }
        return $invalidParameters;
    }

}