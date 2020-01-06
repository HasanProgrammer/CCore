<?php

/**
 * @author  Hasan Karami
 * @version 1
 * @package CCore
 */
namespace Kernel\MVC
{

    use Kernel\Exceptions\ViewException;

    final class View
    {
        /**
         * @var string $area
         */
        private string $area;

        /**
         * @var string $layout
         */
        private string $layout;

        /**
         * @var array $templateEngine
         */
        private array $templateEngine;

        /**
         * @var array $bufferElements
         */
        private array $bufferElements;

        /**
         * @return void
         */
        public function __construct()
        {
            $this->templateEngine = config('View')['TemplateEngine'];
        }

        /**
         * @param  string $area
         * @param  string $path
         * @return void
         */
        public final function setLayoutArea(string $area, string $path) : void
        {
            $path = trim($path);
            $path = str_replace('.', '/', $path);
            try
            {
                if(file_exists('Areas/'.$area.'/Views/Layout/'.$path.'.php'))
                    $this->layout = $path;
                else throw new ViewException("File: ".__FILE__." --Line: ".__LINE__);
            }
            catch (ViewException $viewException)
            {
                d( $viewException->getMessage() );
            }
        }

        /**
         * @param  string $path
         * @return void
         */
        public final function setLayout(string $path) : void
        {
            $path = trim($path);
            $path = str_replace('.', '/', $path);
            try
            {
                if(file_exists('MVC/Views/Layout/'.$path.'.php'))
                    $this->layout = $path;
                else throw new ViewException("File: ".__FILE__." --Line: ".__LINE__);
            }
            catch (ViewException $viewException)
            {
                d( $viewException->getMessage() );
            }
        }

        /**
         * @param  string $area
         * @return self
         */
        public final function area(string $area) : self
        {
            $this->area = $area;
            return $this;
        }

        /**
         * @param  string $path
         * @param  array  $data
         * @return self
         */
        public final function render(string $path, array $data = []) : ?self
        {
            $path = trim($path);
            $path = str_replace('.', '/', $path);
            try
            {
                if(isset($this->area))
                {
                    if(file_exists('Areas/'.$this->area.'/Views/Controllers/'.$path.'.php'))
                    {
                        $this->rendition('Areas/'.$this->area.'/Views/Controllers/'.$path.'.php'    , $data);
                        $this->rendition('Areas/'.$this->area.'/Views/Layout/'.$this->layout.'.php' , $data);
                        return $this;
                    } else throw new ViewException("File: ".__FILE__." --Line: ".__LINE__);
                }

                if(file_exists('MVC/Views/Controllers/'.$path.'.php'))
                {
                    $this->rendition('MVC/Views/Controllers/'.$path.'.php'    , $data);
                    $this->rendition('MVC/Views/Layout/'.$this->layout.'.php' , $data);
                    return $this;
                } else throw new ViewException("File: ".__FILE__." --Line: ".__LINE__);
            }
            catch (ViewException $viewException)
            {
                d( $viewException->getMessage() );
            }

            return null;
        }

        /**
         * @param  string $path
         * @param  array  $data
         * @return self
         */
        public final function renderAJAX(string $path, array $data = []) : ?self
        {
            $path = trim($path);
            $path = str_replace('.', '/', $path);
            try
            {
                if(isset($this->area))
                {
                    if(file_exists('Areas/'.$this->area.'/Views/AJAX/'.$path.'.php'))
                    {
                        $this->rendition('Areas/'.$this->area.'/Views/AJAX/'.$path.'.php', null);
                        return $this;
                    }
                    else throw new ViewException("File: ".__FILE__." --Line: ".__LINE__);
                }

                if(file_exists('MVC/Views/AJAX/'.$path.'.php'))
                {
                    $this->rendition('MVC/Views/AJAX/'.$path.'.php', null);
                    return $this;
                }
                else throw new ViewException("File: ".__FILE__." --Line: ".__LINE__);

            }
            catch (ViewException $viewException)
            {
                d( $viewException->getMessage() );
            }

            return null;
        }

        /**
         * @param  string $path
         * @param  array  $data
         * @return self
         */
        public final function renderPartials(string $path, array $data = []) : ?self
        {
            $path = trim($path);
            $path = str_replace('.', '/', $path);
            try
            {
                if(isset($this->area))
                {
                    if(file_exists('Areas/'.$this->area.'/Views/Partials/'.$path.'.php'))
                    {
                        $this->rendition('Areas/'.$this->area.'/Views/Partials/'.$path.'.php', $data);
                        return $this;
                    }
                    else throw new ViewException("File: ".__FILE__." --Line: ".__LINE__);
                }

                if(file_exists('MVC/Views/Partials/'.$path.'.php'))
                {
                    $this->rendition('MVC/Views/Partials/'.$path.'.php', $data);
                    return $this;
                }
                else throw new ViewException("File: ".__FILE__." --Line: ".__LINE__);
            }
            catch (ViewException $viewException)
            {
                d( $viewException->getMessage() );
            }

            return null;
        }

        /**
         * @param  string  $path
         * @param  integer $timeOut
         * @return void
         */
        public final function redirect(string $path, int $timeOut) : void
        {
            redirectOnTime($path, $timeOut);
        }

        /**
         * @param  string $mark
         * @param  string $value
         * @return void
         */
        public function section(string $mark, string $value = null) : void
        {
            $mark  = trim($mark);
            $value = trim($value);
            if(isset($value) && $value != null)
            {
                $this->bufferElements[$mark] = $value;
            }
            else
            {
                $this->bufferElements[$mark] = null;
                ob_start();
            }
        }

        /**
         * @param  string $mark
         * @return void
         */
        public function end(string $mark) : void
        {
            $this->bufferElements[trim($mark)] = ob_get_clean();
        }

        /**
         * @param  string $mark
         * @return string
         */
        public function content(string $mark) : ?string
        {
            $mark = trim($mark);
            try
            {
                if(isset($this->bufferElements[$mark]))
                    return $this->bufferElements[$mark];
                else throw new ViewException("File: ".__FILE__." --Line: ".__LINE__);
            }
            catch (ViewException $viewException)
            {
                d( $viewException->getMessage() );
            }

            return null;
        }

        /**
         * @param  string $pathView
         * @param  array  $data
         * @return void
         */
        private function rendition(string $pathView, array $data) : void
        {
            ob_start();
            extract($data);
            include_once $pathView;
            $content = ob_get_clean();
            foreach($this->templateEngine as $item => $value)
                $content = preg_replace("#$item#", $value, $content);
            eval(' ?>' . $content . '<?php ');
        }
    }
}