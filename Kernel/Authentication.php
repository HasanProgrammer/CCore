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

    final class Authentication
    {

        private static $instance    = null;
        private static $targetTable = null;

        /**
         * @param  string $table
         * @return void
         */
        private final function __construct(string $table)
        {
            self::$targetTable = strtolower($table);
            Session::init();
        }

        /**
         * @param  string $table
         * @return Authentication
         */
        public static function target(string $table)
        {
            if(!isset(self::$instance))
                self::$instance = new Authentication($table);
            return self::$instance;
        }

        public final function beforeSignIn()
        {
            return $this;
        }

        /**
         * @param Request $request
         * @param AuthSignIn $authSignIn
         */
        public final function doSignIn(Request $request, AuthSignIn $authSignIn)
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

        public final function afterSignIn(AuthSignIn $authSignIn)
        {
            return $this;
        }

        public final function beforeSignUp()
        {
            return $this;
        }

        /**
         * @param  Request $request
         * @param  AuthSignUp $authSignUp
         * @return Authentication
         */
        public final function doSignUp(Request $request, AuthSignUp $authSignUp)
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

        public final function afterSignUp(AuthSignUp $authSignUp) : self
        {
            return $this;
        }
    }
}