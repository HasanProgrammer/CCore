<?php

/**
 * @author  Hasan Karami
 * @version 1
 * @package CCore
 */
namespace Kernel
{

    use Libs\Finals\Session;
    use Kernel\Http\Request;
    use Kernel\Database\Pecod;
    use Kernel\Core\Classes\Interfaces\AuthSignIn;
    use Kernel\Core\Classes\Interfaces\AuthSignUp;

    class Authentication
    {
        private static Authentication $instance;
        private static string $targetTable;

        /**
         * @param  string $table
         * @return void
         */
        private function __construct(string $table)
        {
            self::$targetTable = strtolower($table);
            Session::init();
        }

        /**
         * @param  string $table
         * @return self
         */
        public static function target(string $table) : self
        {
            if(!isset(self::$instance))
                self::$instance = new Authentication($table);
            return self::$instance;
        }

        /**
         * @return self
         */
        public function beforeSignIn() : self
        {
            return $this;
        }

        /**
         * @param Request $request
         * @param AuthSignIn $authSignIn
         */
        public function doSignIn(Request $request, AuthSignIn $authSignIn)
        {
            if(count(Pecod::table(self::$targetTable)->select()->where($request->post()->all)->pull()->toArray()) == 1)
            {
                if(Session::set('UserSignedIn', $request->post()->username))
                    $authSignIn->onSignInSuccess();
            }
            else
            {
                $authSignIn->onSignInError();
            }
        }

        /**
         * @param  AuthSignIn $authSignIn
         * @return self
         */
        public function afterSignIn(AuthSignIn $authSignIn) : self
        {
            return $this;
        }

        /**
         * @return self
         */
        public function beforeSignUp() : self
        {
            return $this;
        }

        /**
         * @param  Request $request
         * @param  AuthSignUp $authSignUp
         * @return self
         */
        public function doSignUp(Request $request, AuthSignUp $authSignUp) : self
        {
            $request = $request->post()->all;
            Pecod::table(self::$targetTable, function (Pecod $pecod) use ($request , $authSignUp)
            {
                if(count($pecod->select()->where($request)->pull()->toArray()) == 1)
                {
                    $authSignUp->onSignUpError( config("Message")["Auth"]["Error-SignIn"] );
                }
                else
                {
                    $save = $pecod->push($request)->save()->getLastRecordId();
                    $authSignUp->onSignUpSuccess( config("Message")["Auth"]["Success-SignUp"] , $pecod->select()->where(['id' => $save])->pull()->toArray());
                }
            });

            return $this;
        }

        /**
         * @param  AuthSignUp $authSignUp
         * @return self
         */
        public function afterSignUp(AuthSignUp $authSignUp) : self
        {
            return $this;
        }
    }
}