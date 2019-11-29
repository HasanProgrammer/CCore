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

    final class Pecod
    {
        const FETCH_SIMPLE = "fetch_simple";
        const FETCH_OBJECT = "fetch_object";
        const RESULT_ON    = 1;
        const RESULT_OFF   = 0;
        /**
         * Class Fields
         */
        private static $instance;
        private static $database;
        private static $targetTable;
        /**
         * Object Fields
         */
        private $request;
        private $whereKey;
        private $groupByKey;
        private $orderByKey;
        private $executeValue   = null;
        private $queryStatement = null;
        /**
         * @return void
         */
        private function __construct()
        {
            self::$database = Database::getNewInstance()->getConnectionPDO();
            $this->request  = new Request();
        }
        /**
         * @return \PDO
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
        public final function transaction($reference)
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
         * @return Pecod
         */
        public final function select($column = null) : Pecod
        {
            $this->queryStatement = null;
            $this->executeValue   = null;
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
         * @return Pecod
         */
        public final function selectBySelect() : Pecod
        {
            return $this;
        }
        /**
         * @param  mixed $condition
         * @return Pecod
         */
        public final function push($condition) : Pecod
        {
            $this->queryStatement  = null;
            $this->executeValue    = null;
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
         * @return Pecod
         */
        public final function fresh($condition) : Pecod
        {
            $this->queryStatement  = null;
            $this->executeValue    = null;
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
         * @return Pecod
         */
        public final function remove() : Pecod
        {
            $this->queryStatement  = null;
            $this->executeValue    = null;
            $this->queryStatement .= "DELETE FROM ".self::$targetTable;
            return $this;
        }
        /**
         * @param  mixed  $column
         * @param  string $operator
         * @param  string $value
         * @return Pecod
         */
        public final function where($column, string $operator = null, string $value = null) : Pecod
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
         * @return Pecod
         */
        public final function orWhere($column, string $operator = null, string $value = null) : Pecod
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
         * @return Pecod
         */
        public final function innerJoin(string $table, array $condition) : Pecod
        {
            $table = strtolower($table);
            $this->queryStatement .= " INNER JOIN ".$table." ON ";
            foreach($condition as $item => $value)
                $this->queryStatement .= self::$targetTable.'.'.$item." = ".$table.'.'.$value." , ";
            $this->queryStatement = rtrim($this->queryStatement, ' ,');
            return $this;
        }
        /**
         * @return Pecod
         */
        public final function innerJoinBySelect() : Pecod
        {
            return $this;
        }
        /**
         * @param  string $table
         * @param  array $condition
         * @return Pecod
         */
        public final function leftJoin(string $table, array $condition) : Pecod
        {
            $this->queryStatement .= " LEFT JOIN ".$table." ON ";
            foreach($condition as $item => $value)
                $this->queryStatement .= self::$targetTable.'.'.$item." = ".$table.'.'.$value." , ";
            $this->queryStatement = rtrim($this->queryStatement, ' ,');
            return $this;
        }
        /**
         * @return Pecod
         */
        public final function leftJoinBySelect() : Pecod
        {
            return $this;
        }
        /**
         * @param  string $table
         * @param  array $condition
         * @return Pecod
         */
        public final function rightJoin(string $table, array $condition) : Pecod
        {
            $this->queryStatement .= " RIGHT JOIN ".$table." ON ";
            foreach($condition as $item => $value)
                $this->queryStatement .= self::$targetTable.'.'.$item." = ".$table.'.'.$value." , ";
            $this->queryStatement = rtrim($this->queryStatement, ' ,');
            return $this;
        }
        /**
         * @return Pecod
         */
        public final function rightJoinBySelect() : Pecod
        {
            return $this;
        }
        /**
         * @param  string $table
         * @param  array $condition
         * @return Pecod
         */
        public final function fullJoin(string $table, array $condition) : Pecod
        {
            $this->queryStatement .= " FULL JOIN ".$table." ON ";
            foreach($condition as $item => $value)
                $this->queryStatement .= self::$targetTable.'.'.$item." = ".$table.'.'.$value." , ";
            $this->queryStatement = rtrim($this->queryStatement, ' ,');
            return $this;
        }
        /**
         * @return Pecod
         */
        public final function fullJoinBySelect() : Pecod
        {
            return $this;
        }
        /**
         * @param  mixed $column
         * @return Pecod
         */
        public final function groupBy($column) : Pecod
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
         * @return Pecod
         */
        public final function orderBy($column) : Pecod
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
         * @return Pecod
         */
        public final function orderByRand() : Pecod
        {
            $this->queryStatement .= " ORDER BY RAND() ";
            return $this;
        }
        /**
         * @param  string $sortType
         * @return Pecod
         */
        public final function sort(string $sortType) : Pecod
        {
            if(strtoupper($sortType) == "DESC" || strtoupper($sortType) == "ASC")
                $this->queryStatement .= " $sortType ";
            return $this;
        }
        /**
         * @param  mixed $limitation
         * @return Pecod
         */
        public final function limit($limitation) : Pecod
        {
            $this->queryStatement .= " LIMIT ".$limitation;
            return $this;
        }
        /**
         * @return int
         */
        public final function getLastRecordId() : int
        {
            return (int)self::$database->lastInsertId();
        }
        /**
         * @return int
         */
        public final function getCountRow() : int
        {
            $query = self::$database->prepare($this->queryStatement);
            if($query->execute($this->executeValue))
                return (int)$query->rowCount();
        }
        /**
         * @return int
         */
        public final function getCountColumn() : int
        {
            $query = self::$database->prepare($this->queryStatement);
            if($query->execute($this->executeValue))
                return $query->columnCount();
        }
        /**
         * @return mixed
         */
        public final function getColumnTable()
        {
            $query = self::$database->prepare("SELECT * FROM ".self::$targetTable);
            if($query->execute())
            {
                $column = [];
                for($i = 0; $i < $query->columnCount(); $i++)
                    $column[] = $query->getColumnMeta($i)['name'];
                return new class($column)
                {
                    private $column;
                    public function __construct(array $column)
                    {
                        $this->column = $column;
                    }
                    public function toArray() : array
                    {
                        return (array)$this->column;
                    }
                    public function toJson() : string
                    {
                        return json_encode($this->column);
                    }
                };
            }
        }
        /**
         * @param  integer $countRow
         * @param  string  $controllerRoute
         * @param  integer $numberRoute
         * @return mixed
         */
        public final function paginate(int $countRow, string $controllerRoute = null, int $numberRoute = null)
        {
            $count   = $this->getCountPage($this->getCountRow(), $countRow);
            $number  = $this->getNumberPageFromUrl($count, $numberRoute);
            $links   = $this->getLinksPage($count, $number, $controllerRoute);
            $records = $this->limit($this->getLimit($number, $countRow))->pull()->toArray();
            return new class($links, $records)
            {
                private $links;
                private $records;
                public function __construct(string $links, array $records)
                {
                    $this->links   = $links;
                    $this->records = $records;
                }
                public function getRecords() : array
                {
                    return (array)$this->records;
                }
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
        public final function pull(string $typefetch = Pecod::FETCH_SIMPLE, string $className = null)
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
        public final function save(string $type = Pecod::RESULT_OFF)
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
                private $fetchData;
                private $fetchDataFilter;
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
                private $fetchDataByObject;
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
            return ($number < 1) ? 1 : ($number > $count) ? $count : $number;
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