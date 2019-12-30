<?php

/**
 * @author  Hasan Karami
 * @version 1
 * @package CCore
 */
namespace Kernel\MVC
{

    use ReflectionException;
    use Kernel\Exceptions\ModelException;

    class Model
    {
        /**
         * @var string
         */
        private $area;

        /**
         * @param  string $area
         * @return self
         */
        public function area(string $area) : self
        {
            $this->area = $area;
            return $this;
        }

        /**
         * @param  string $logic
         * @param  array  $dataModels
         * @return mixed
         */
        public function run(string $logic, array $dataModels = [])
        {
            try
            {
                if(isset($dataModels) && count($dataModels) != 0)
                {
                    foreach($dataModels as $dataModel)
                    {
                        if(file_exists('MVC/Models/DataModels/'.$dataModel.'.php'))
                            include_once 'MVC/Models/DataModels/'.$dataModel.'.php';
                        else
                            throw new ModelException('File: '.__FILE__.' --Line: '.__LINE__);
                    }
                }
                if(file_exists('MVC/Models/Logic/'.$logic.'.php'))
                    include_once 'MVC/Models/Logic/'.$logic.'.php';
                else
                    throw new ModelException('File: '.__FILE__.' --Line: '.__LINE__);
                return (new \ReflectionClass('\\MVC\\Models\\Logic\\'.$logic))->newInstance();
            }
            catch (ModelException | ReflectionException $exception)
            {
                d( $exception->getMessage() );
            }

            return false;
        }
    }
}