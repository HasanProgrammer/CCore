<?php

/**
 * @author  Hasan Karami
 * @version 1
 * @package CCore
 */
namespace Kernel\Database
{

    use PDO;
    use Kernel\MVC\View;
    use Kernel\Http\Request;
    use PHPMailer\PHPMailer\Exception;
    use Kernel\Core\Classes\Interfaces\Database\ORM as IORM;

    class Pecod
    {
        public const FETCH_SIMPLE = "fetch_simple";
        public const FETCH_OBJECT = "fetch_object";
        public const RESULT_ON    = 1;
        public const RESULT_OFF   = 0;

        /**
         * Class Fields
         */
        private static Pecod  $instance;
        private static PDO    $database;
        private static string $targetTable;

        /**
         * Object Fields
         */
        private Request $request;
        private bool    $whereKey;
        private bool    $groupByKey;
        private bool    $orderByKey;
        private array   $executeValue;
        private string  $queryStatement;

        /**
         * @return void
         */
        private function __construct()
        {
            self::$database = Database::getNewInstance()->getConnectionPDO();
            $this->request  = new Request();
        }

        /**
         * @return PDO
         */
        public static function rowQuery() : PDO
        {
            new Pecod();
            return self::$database;
        }

        /**
         * @param  string   $name
         * @param  callable $callBack
         * @return mixed
         */
        public static function table(string $name, callable $callBack = null)
        {
            self::$targetTable = strtolower($name);
            if(!isset(self::$instance)) self::$instance = new Pecod();
            if(isset($callBack))
                return call_user_func($callBack, self::$instance);
            else
                return self::$instance;
        }

        /**
         * @param  mixed $reference
         * @return void
         */
        public function transaction($reference) : void
        {
            try
            {
                self::$database->beginTransaction();
                if(is_callable($reference))
                    call_user_func($reference, $this);
                else if($reference instanceof IORM)
                    $reference->transaction($this);
                self::$database->commit();
            }
            catch (Exception $exception)
            {
                self::$database->rollBack();
            }
        }

        /**
         * @param  mixed $column
         * @return self
         */
        public function select($column = null) : self
        {
            $this->queryStatement = null;
            $this->executeValue   = [];
            if(isset($column))
            {
                $this->queryStatement .= "SELECT ";
                if(is_array($column) && !isAssoc($column))
                {
                    foreach($column as $item)
                        $this->queryStatement .= $item." , ";
                    $this->queryStatement = rtrim($this->queryStatement, ' ,')." FROM ".self::$targetTable;
                }
            } else $this->queryStatement .= "SELECT * FROM ".self::$targetTable;
            return $this;
        }

        /**
         * @return self
         */
        public function selectBySelect() : self
        {
            return $this;
        }

        /**
         * @param  mixed $condition
         * @return self
         */
        public function push($condition) : self
        {
            $this->queryStatement  = null;
            $this->executeValue    = [];
            if(is_array($condition) && isAssoc($condition))
            {
                $this->queryStatement .= "INSERT INTO ".self::$targetTable." ( ";
                foreach($condition as $item => $value)
                    $this->queryStatement .= $item." , ";
                $this->queryStatement = rtrim($this->queryStatement, ' ,')." ) VALUES ( ";
                foreach($condition as $item => $value)
                {
                    $this->queryStatement         .= " :$item , ";
                    $this->executeValue[":$item"]  = $value;
                }
                $this->queryStatement = rtrim($this->queryStatement, ' ,')." ) ";
            }
            return $this;
        }

        /**
         * @param  mixed $condition
         * @return self
         */
        public function fresh($condition) : self
        {
            $this->queryStatement  = null;
            $this->executeValue    = [];
            if(is_array($condition) && isAssoc($condition))
            {
                $this->queryStatement .= "UPDATE ".self::$targetTable." SET ";
                foreach($condition as $item => $value)
                {
                    $this->queryStatement         .= $item." = :$item , ";
                    $this->executeValue[":$item"]  = $value;
                }
                $this->queryStatement = rtrim($this->queryStatement, " ,");
            }
            return $this;
        }

        /**
         * @return self
         */
        public function remove() : self
        {
            $this->queryStatement  = null;
            $this->executeValue    = [];
            $this->queryStatement .= "DELETE FROM ".self::$targetTable;
            return $this;
        }

        /**
         * @param  mixed  $column
         * @param  string $operator
         * @param  string $value
         * @return self
         */
        public function where($column, string $operator = null, string $value = null) : self
        {
            if($this->whereKey != true)
            {
                $this->whereKey = true;
                $this->queryStatement .= " WHERE ";
            }
            else $this->queryStatement .= " AND ";
            if(is_array($column) && isAssoc($column))
            {
                foreach($column as $item => $value)
                {
                    $this->queryStatement         .= $item." = :$item AND ";
                    $this->executeValue[":$item"]  = $value;
                }
                $this->queryStatement = rtrim($this->queryStatement, " AND");
            }
            else if(is_string($column))
            {
                if(isset($operator , $value))
                {
                    $this->queryStatement           .= " WHERE ".$column." ".$operator." :$column ";
                    $this->executeValue[":$column"]  = $value;
                }
            }
            return $this;
        }

        /**
         * @param  mixed  $column
         * @param  string $operator
         * @param  string $value
         * @return self
         */
        public function orWhere($column, string $operator = null, string $value = null) : self
        {
            if($this->whereKey != true)
            {
                $this->whereKey = true;
                $this->queryStatement .= " OR WHERE ";
            }
            else $this->queryStatement .= " AND ";
            if(is_array($column) && isAssoc($column))
            {
                foreach($column as $item => $value)
                {
                    $this->queryStatement         .= $item." = :$item AND ";
                    $this->executeValue[":$item"]  = $value;
                }
                $this->queryStatement = rtrim($this->queryStatement, " AND");
            }
            else if(is_string($column))
            {
                if(isset($operator , $value))
                {
                    $this->queryStatement           .= " WHERE ".$column." ".$operator." :$column ";
                    $this->executeValue[":$column"]  = $value;
                }
            }
            return $this;
        }

        /**
         * @param  string $table
         * @param  array $condition
         * @return self
         */
        public function innerJoin(string $table, array $condition) : self
        {
            $table = strtolower($table);
            $this->queryStatement .= " INNER JOIN ".$table." ON ";
            foreach($condition as $item => $value)
                $this->queryStatement .= self::$targetTable.'.'.$item." = ".$table.'.'.$value." , ";
            $this->queryStatement = rtrim($this->queryStatement, ' ,');
            return $this;
        }

        /**
         * @return self
         */
        public function innerJoinBySelect() : self
        {
            return $this;
        }

        /**
         * @param  string $table
         * @param  array $condition
         * @return self
         */
        public function leftJoin(string $table, array $condition) : self
        {
            $this->queryStatement .= " LEFT JOIN ".$table." ON ";
            foreach($condition as $item => $value)
                $this->queryStatement .= self::$targetTable.'.'.$item." = ".$table.'.'.$value." , ";
            $this->queryStatement = rtrim($this->queryStatement, ' ,');
            return $this;
        }

        /**
         * @return self
         */
        public function leftJoinBySelect() : self
        {
            return $this;
        }

        /**
         * @param  string $table
         * @param  array $condition
         * @return self
         */
        public function rightJoin(string $table, array $condition) : self
        {
            $this->queryStatement .= " RIGHT JOIN ".$table." ON ";
            foreach($condition as $item => $value)
                $this->queryStatement .= self::$targetTable.'.'.$item." = ".$table.'.'.$value." , ";
            $this->queryStatement = rtrim($this->queryStatement, ' ,');
            return $this;
        }

        /**
         * @return self
         */
        public function rightJoinBySelect() : self
        {
            return $this;
        }

        /**
         * @param  string $table
         * @param  array $condition
         * @return self
         */
        public function fullJoin(string $table, array $condition) : self
        {
            $this->queryStatement .= " FULL JOIN ".$table." ON ";
            foreach($condition as $item => $value)
                $this->queryStatement .= self::$targetTable.'.'.$item." = ".$table.'.'.$value." , ";
            $this->queryStatement = rtrim($this->queryStatement, ' ,');
            return $this;
        }

        /**
         * @return self
         */
        public function fullJoinBySelect() : self
        {
            return $this;
        }

        /**
         * @param  mixed $column
         * @return self
         */
        public function groupBy($column) : self
        {
            if($this->groupByKey != true)
            {
                $this->groupByKey = true;
                $this->queryStatement .= " GROUP BY ";
            }
            else $this->queryStatement .= " , ";
            if(is_string($column))
            {
                $this->queryStatement         .= " :group ";
                $this->executeValue[":group"]  = $column;
            }
            else if(is_array($column) && !isAssoc($column))
            {
                foreach($column as $item)
                {
                    $this->queryStatement         .= " :$item , ";
                    $this->executeValue[":$item"]  = $item;
                }
                $this->queryStatement = rtrim($this->queryStatement, ' ,');
            }
            return $this;
        }

        /**
         * @param  mixed $column
         * @return self
         */
        public function orderBy($column) : self
        {
            if($this->orderByKey != true)
            {
                $this->orderByKey = true;
                $this->queryStatement .= " ORDER BY ";
            }
            else $this->queryStatement .= " , ";
            if(is_string($column))
            {
                $this->queryStatement           .= " :$column ";
                $this->executeValue[":$column"]  = $column;
            }
            else if(is_array($column) && !isAssoc($column))
            {
                foreach($column as $item)
                {
                    $this->queryStatement         .= " :$item , ";
                    $this->executeValue[":$item"]  = $item;
                }
                $this->queryStatement = rtrim($this->queryStatement, ' ,');
            }
            return $this;
        }

        /**
         * @return self
         */
        public function orderByRand() : self
        {
            $this->queryStatement .= " ORDER BY RAND() ";
            return $this;
        }

        /**
         * @param  string $sortType
         * @return self
         */
        public function sort(string $sortType) : self
        {
            if(strtoupper($sortType) == "DESC" || strtoupper($sortType) == "ASC")
                $this->queryStatement .= " $sortType ";
            return $this;
        }

        /**
         * @param  mixed $limitation
         * @return self
         */
        public function limit($limitation) : self
        {
            $this->queryStatement .= " LIMIT ".$limitation;
            return $this;
        }

        /**
         * @return int
         */
        public function getLastRecordId() : int
        {
            return (int)self::$database->lastInsertId();
        }

        /**
         * @return int | null
         */
        public function getCountRow() : ?int
        {
            $query = self::$database->prepare($this->queryStatement);
            if($query->execute($this->executeValue))
                return (int)$query->rowCount();

            return null;
        }

        /**
         * @return int | null
         */
        public function getCountColumn() : ?int
        {
            $query = self::$database->prepare($this->queryStatement);
            if($query->execute($this->executeValue))
                return $query->columnCount();

            return null;
        }

        /**
         * @return mixed
         */
        public function getColumnTable()
        {
            $query = self::$database->prepare("SELECT * FROM ".self::$targetTable);
            if($query->execute())
            {
                $column = [];
                for($i = 0; $i < $query->columnCount(); $i++)
                    $column[] =  $query->getColumnMeta($i)["name"];
                return new class($column)
                {
                    /**
                     * @var array $column
                     */
                    private array $column;

                    /**
                     * @param array $column
                     */
                    public function __construct(array $column)
                    {
                        $this->column = $column;
                    }

                    /**
                     * @return array
                     */
                    public function toArray() : array
                    {
                        return (array)$this->column;
                    }

                    /**
                     * @return string
                     */
                    public function toJson() : string
                    {
                        return json_encode($this->column);
                    }
                };
            }

            return null;
        }

        /**
         * @param  integer $countRow
         * @param  string  $controllerRoute
         * @param  integer $numberRoute
         * @return mixed
         */
        public function paginate(int $countRow, string $controllerRoute = null, int $numberRoute = null)
        {
            $count   = $this->getCountPage($this->getCountRow(), $countRow);
            $number  = $this->getNumberPageFromUrl($count, $numberRoute);
            $links   = $this->getLinksPage($count, $number, $controllerRoute);
            $records = $this->limit($this->getLimit($number, $countRow))->pull()->toArray();

            return new class($links , $records)
            {
                /**
                 * @var string $links
                 */
                private string $links;

                /**
                 * @var array $records
                 */
                private array  $records;

                /**
                 * @param string $links
                 * @param array  $records
                 */
                public function __construct(string $links, array $records)
                {
                    $this->links   = $links;
                    $this->records = $records;
                }

                /**
                 * @return array
                 */
                public function getRecords() : array
                {
                    return (array)$this->records;
                }

                /**
                 * @return string
                 */
                public function getLinks() : string
                {
                    return (string)$this->links;
                }
            };
        }

        /**
         * @param  string $typefetch
         * @param  string $className
         * @return mixed
         */
        public function pull(string $typefetch = Pecod::FETCH_SIMPLE, string $className = null)
        {
            $query = self::$database->prepare($this->queryStatement);
            if($query->execute($this->executeValue))
                if($typefetch == Pecod::FETCH_SIMPLE)
                    return $this->configPullData($query->fetchAll(\PDO::FETCH_ASSOC));
                else if($typefetch == Pecod::FETCH_OBJECT)
                    return $this->configPullDataByObject($query->fetchObject($className));
        }

        /**
         * @param  string $type
         * @return mixed
         */
        public function save(string $type = Pecod::RESULT_OFF)
        {
            $query  = self::$database->prepare($this->queryStatement);
            $result = $query->execute($this->executeValue);
            if($type == Pecod::RESULT_OFF)
                return $this;
            else if($type == Pecod::RESULT_ON)
                return $result;
        }

        /**
         * @param  array $fetchData
         * @return mixed
         */
        private function configPullData(array $fetchData)
        {
            return new class($fetchData)
            {
                private array $fetchData;
                private int $fetchDataFilter;
                public function __construct(array $fetchData)
                {
                    $this->fetchData = $fetchData;
                }
                public function filter(int $row)
                {
                    if($row - 1 >= 0)
                        if(isset($this->fetchData[$row - 1]))
                            $this->fetchDataFilter = $row - 1;
                    return $this;
                }
                public function toArray() : array
                {
                    return isset($this->fetchDataFilter) ? (array)$this->fetchData[$this->fetchDataFilter] : (array)$this->fetchData;
                }
                public function toJson() : string
                {
                    return json_encode(isset($this->fetchDataFilter) ? (array)$this->fetchData[$this->fetchDataFilter] : (array)$this->fetchData);
                }
            };
        }

        /**
         * @param  array $fetchObject
         * @return mixed
         */
        private function configPullDataByObject($fetchObject)
        {
            $fetchDataByObject = [];
            while($row = $fetchObject)
                $fetchDataByObject[] = $row;
            return new class($fetchDataByObject)
            {
                private array $fetchDataByObject;
                public function __construct(array $fetchDataByObject)
                {
                    $this->fetchDataByObject = $fetchDataByObject;
                }
                public function filter(int $row)
                {
                    if($row - 1 >= 0)
                        if(isset($this->fetchDataByObject[$row - 1]))
                            return $this->fetchDataByObject[$row - 1];
                }
            };
        }

        /**
         * @param  integer $allRow
         * @param  integer $countRowPerPage
         * @return integer
         */
        private function getCountPage(int $allRow, int $countRowPerPage) : int
        {
            $countPages  = ceil($allRow/$countRowPerPage);
            return ($countPages < 1) ? 1 : $countPages;
        }

        /**
         * @param  integer $count
         * @param  integer $numberRoute
         * @return integer
         */
        private function getNumberPageFromUrl(int $count, int $numberRoute = null) : int
        {
            $number = isset($numberRoute) ? $numberRoute : ( ($this->request->get()->has('Page') == true) ? $this->request->get()->Page : 1);
            return ($number < 1) ? 1 : (($number > $count) ? $count : $number);
        }

        /**
         * @param  integer $number
         * @param  integer $count
         * @return string
         */
        private function getLimit(int $number, int $count) : string
        {
            return (string)(($number - 1)*$count . " , " . $count);
        }

        /**
         * @param  integer $count
         * @param  integer $number
         * @param  string  $controllerRoute
         * @return string
         */
        private function getLinksPage(int $count, int $number, string $controllerRoute = null) : string
        {
            ob_start();
            if(!isset($controllerRoute))
                (new View())->renderPartials('Pagination.UrlPaginate'   , ['CountPages' => $count , 'NumberPage' => $number]);
            else
                (new View())->renderPartials('Pagination.RoutePaginate' , ['CountPages' => $count , 'NumberPage' => $number , 'Controller' => $controllerRoute]);
            return ob_get_clean();
        }
    }
}