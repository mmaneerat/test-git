<?php
/**
 * DB Class
 */

/**
 * DB return types
 * 
 * OBJECT – result will be output as a numerically indexed array of row objects.
 * OBJECT_K – result will be output as an associative array of row objects, using first column’s values as keys (duplicates will be discarded).
 * ARRAY_A – result will be output as a numerically indexed array of associative arrays, using column names as keys.
 * ARRAY_N – result will be output as a numerically indexed array of numerically indexed arrays.
 * 
 * @since 1.0.0
 * 
 */
define('OBJECT', 'OBJECT');
define('OBJECT_K', 'OBJECT_K');
define('ARRAY_A', 'ARRAY_A');
define('ARRAY_N', 'ARRAY_N');

/**
 * 
 * @example new sapdb($dbuser, $dbpassword, $dbname, $dbhost)
 * 
 * @see https://developer.wordpress.org/reference/classes/wpdb/
 * @since 1.0.0
 * 
 */
class sapdb
{
    /**
     * Mysqli Connection.
     *
     * @since 1.0.0
     *
     * @var bool
     */
    private $use_mysqli = false;

    /**
     * Database Username
     *
     * @since 1.0.0
     *
     * @var string
     */
    private $dbuser = "";

    /**
     * Database Password
     *
     * @since 1.0.0
     *
     * @var string
     */
    private $dbpassword = "";

    /**
     * Database Name
     *
     * @since 1.0.0
     *
     * @var string
     */
    private $dbname = "";

    /**
     * Database Host
     *
     * @since 1.0.0
     *
     * @var string
     */
    private $dbhost = "";

    /**
     * Database Connection
     *
     * @since 1.0.0
     *
     * @var bool|mysqli
     */
    private $dbh;

    /**
     * Saved info on the table column.
     *
     * @since 1.0.0
     *
     * @var array
     */
    protected $col_info;

    /**
     * Count of rows affected by the last query.
     *
     * @since 1.0.0
     *
     * @var int
     */
    public $rows_affected = 0;

    /**
     * The ID generated for an AUTO_INCREMENT column by the last query (usually INSERT).
     *
     * @since 1.0.0
     *
     * @var int
     */
    public $insert_id = 0;

    /**
     * The last query made.
     *
     * @since 1.0.0
     *
     * @var string
     */
    public $last_query;

    /**
     * Results of the last query.
     *
     * @since 1.0.0
     *
     * @var stdClass[]|null
     */
    public $last_result;

    /**
     * Database query result.
     *
     * Possible values:
     *
     * - For successful SELECT, SHOW, DESCRIBE, or EXPLAIN queries:
     *   - `mysqli_result` instance when the `mysqli` driver is in use
     *   - `resource` when the older `mysql` driver is in use
     * - `true` for other query types that were successful
     * - `null` if a query is yet to be made or if the result has since been flushed
     * - `false` if the query returned an error
     *
     * @since 1.0.0
     *
     * @var mysqli_result|resource|bool|null
     */
    protected $result;

    /**
     * The number of times to retry reconnecting before dying. Default 5.
     *
     * @since 1.0.0
     *
     * @var int
     */
    protected $reconnect_retries = 5;

    /**
     * Whether we've managed to successfully connect at some point.
     *
     * @since 1.0.0
     *
     * @var bool
     */
    private $has_connected = false;
    public $num_rows;
    private $time_start;

    public function __construct($dbuser, $dbpassword, $dbname, $dbhost = "locahost")
    {
        if (function_exists('mysqli_connect')) {
            $this->use_mysqli = true;
        }
        $this->dbname = $dbname;
        $this->dbpassword = $dbpassword;
        $this->dbuser = $dbuser;
        $this->dbhost = $dbhost;

        $this->db_connect();
    }

    public function db_connect($allow_bail = true)
    {
        if ($this->use_mysqli) {
            $this->dbh = mysqli_init();
            mysqli_real_connect($this->dbh, $this->dbhost, $this->dbuser, $this->dbpassword, $this->dbname);
            mysqli_set_charset($this->dbh, "utf8");

            if ($this->dbh->connect_errno) {
                $this->dbh = null;
            }
        } else {
            throw new Exception("No mysqli connection!");
        }
    }

    public function db_set_charset(string $char = "utf8")
    {
        mysqli_set_charset($this->dbh, $char);
    }

    public function prepare($query, ...$args)
    {
        if (is_null($query)) {
            return;
        }

        // If args were passed as an array (as in vsprintf), move them up.
        $passed_as_array = false;
        if (isset($args[0]) && is_array($args[0]) && 1 === count($args)) {
            $passed_as_array = true;
            $args = $args[0];
        }

        foreach ($args as $arg) {
            if (!is_scalar($arg) && !is_null($arg)) {
                throw new Exception(
                    sprintf(
                        'Unsupported value type (%s).',
                        gettype($arg)
                    )
                );
            }
        }

        /*
         * Specify the formatting allowed in a placeholder. The following are allowed:
         *
         * - Sign specifier, e.g. $+d
         * - Numbered placeholders, e.g. %1$s
         * - Padding specifier, including custom padding characters, e.g. %05s, %'#5s
         * - Alignment specifier, e.g. %05-s
         * - Precision specifier, e.g. %.2f
         */
        $allowed_format = '(?:[1-9][0-9]*[$])?[-+0-9]*(?: |0|\'.)?[-+0-9]*(?:\.[0-9]+)?';

        /*
         * If a %s placeholder already has quotes around it, removing the existing quotes
         * and re-inserting them ensures the quotes are consistent.
         *
         * For backward compatibility, this is only applied to %s, and not to placeholders like %1$s,
         * which are frequently used in the middle of longer strings, or as table name placeholders.
         */
        $query = str_replace("'%s'", '%s', $query); // Strip any existing single quotes.
        $query = str_replace('"%s"', '%s', $query); // Strip any existing double quotes.
        $query = preg_replace('/(?<!%)%s/', "'%s'", $query); // Quote the strings, avoiding escaped strings like %%s.

        $query = preg_replace("/(?<!%)(%($allowed_format)?f)/", '%\\2F', $query); // Force floats to be locale-unaware.

        $query = preg_replace("/%(?:%|$|(?!($allowed_format)?[sdF]))/", '%%\\1', $query); // Escape any unescaped percents.

        // Count the number of valid placeholders in the query.
        $placeholders = preg_match_all("/(^|[^%]|(%%)+)%($allowed_format)?[sdF]/", $query, $matches);

        $args_count = count($args);

        if ($args_count !== $placeholders) {
            if (1 === $placeholders && $passed_as_array) {
                // If the passed query only expected one argument, but the wrong number of arguments were sent as an array, bail.
                throw new Exception(
                    'The query only expected one placeholder, but an array of multiple placeholders was sent.'
                );
            } else {
                /*
                 * If we don't have the right number of placeholders,
                 * but they were passed as individual arguments,
                 * or we were expecting multiple arguments in an array, throw a warning.
                 */
                throw new Exception(
                    sprintf(
                        'The query does not contain the correct number of placeholders (%1$d) for the number of arguments passed (%2$d).',
                        $placeholders,
                        $args_count
                    )
                );

                /*
                 * If we don't have enough arguments to match the placeholders,
                 * return an empty string to avoid a fatal error on PHP 8.
                 */
                if ($args_count < $placeholders) {
                    $max_numbered_placeholder = !empty($matches[3]) ? max(array_map('intval', $matches[3])) : 0;

                    if (!$max_numbered_placeholder || $args_count < $max_numbered_placeholder) {
                        return '';
                    }
                }
            }
        }
        array_walk($args, array($this, 'escape_by_ref'));
        $query = vsprintf($query, $args);

        return $this->add_placeholder_escape($query);
    }

    public function add_placeholder_escape($query)
    {
        return str_replace('%', $this->placeholder_escape(), $query);
    }

    public function remove_placeholder_escape($query)
    {
        return str_replace($this->placeholder_escape(), '%', $query);
    }

    /**
     * Escapes content by reference for insertion into the database, for security.
     *
     * @since 1.0.0
     *
     * @param string $string String to escape.
     */
    public function escape_by_ref(&$string)
    {
        if (!is_float($string)) {
            $string = $this->_real_escape($string);
        }
    }


    /**
     * Real escape, using mysqli_real_escape_string() or mysql_real_escape_string().
     *
     * @since 1.0.0
     *
     * @param string $string String to escape.
     * @return string Escaped string.
     */
    public function _real_escape($string)
    {
        if (!is_scalar($string)) {
            return '';
        }

        if ($this->dbh) {
            $escaped = mysqli_real_escape_string($this->dbh, $string);
        } else {
            $escaped = addslashes($string);
        }

        return $this->add_placeholder_escape($escaped);
    }


    /**
     * Generates and returns a placeholder escape string for use in queries returned by ::prepare().
     * 
     * @since 1.0.0
     *
     * @return string String to escape placeholders.
     */
    public function placeholder_escape()
    {
        static $placeholder;

        if (!$placeholder) {
            // If ext/hash is not present, compat.php's hash_hmac() does not support sha256.
            $algo = function_exists('hash') ? 'sha256' : 'sha1';
            $salt = rand();

            $placeholder = '{' . hash_hmac($algo, uniqid($salt, true), $salt) . '}';
        }

        return $placeholder;
    }


    /**
     * First half of escaping for `LIKE` special characters `%` and `_` before preparing for SQL.
     *
     * Use this only before wpdb::prepare() or esc_sql(). Reversing the order is very bad for security.
     *
     * Example Prepared Statement:
     *
     *     $wild = '%';
     *     $find = 'only 43% of planets';
     *     $like = $wild . $wpdb->esc_like( $find ) . $wild;
     *     $sql  = $wpdb->prepare( "SELECT * FROM $wpdb->posts WHERE post_content LIKE %s", $like );
     *
     * Example Escape Chain:
     *
     *     $sql  = esc_sql( $wpdb->esc_like( $input ) );
     *
     * @since 1.0.0
     * 
     * @param string $text The raw text to be escaped. The input typed by the user
     *                     should have no extra or deleted slashes.
     * @return string Text in the form of a LIKE phrase. The output is not SQL safe.
     *                Call wpdb::prepare() or wpdb::_real_escape() next.
     */
    public function esc_like($text)
    {
        return addcslashes($text, '_%\\');
    }


    /**
     * Kills cached query results.
     *
     * @since 1.0.0
     */
    public function flush()
    {
        $this->last_result = array();
        $this->col_info = null;
        $this->last_query = null;
        $this->rows_affected = 0;
        $this->num_rows = 0;
        $this->last_error = '';

        if ($this->result instanceof mysqli_result) {
            mysqli_free_result($this->result);
            $this->result = null;

            // Sanity check before using the handle.
            if (empty($this->dbh) || !($this->dbh instanceof mysqli)) {
                return;
            }

            // Clear out any results from a multi-query.
            while (mysqli_more_results($this->dbh)) {
                mysqli_next_result($this->dbh);
            }
        }
    }

    /**
     * Performs a database query, using current database connection.
     *
     * @since 1.0.0
     *
     * @param string $query Database query.
     * @return int|bool Boolean true for CREATE, ALTER, TRUNCATE and DROP queries. Number of rows
     *                  affected/selected for all other queries. Boolean false on error.
     */
    public function query($query)
    {

        // echo "</br>---------</br>query -> {$query}<br/>---------</br>";

        if (!$query) {
            $this->insert_id = 0;
            return false;
        }

        $query = $this->remove_placeholder_escape($query);

        $this->flush();

        $this->check_current_query = true;

        // Keep track of the last query for debug.
        $this->last_query = $query;

        $this->_do_query($query);

        // Database server has gone away, try to reconnect.
        $mysql_errno = 0;
        if (!empty($this->dbh)) {
            if ($this->dbh instanceof mysqli) {
                $mysql_errno = mysqli_errno($this->dbh);
            } else {
                // Something has gone horribly wrong, let's try a reconnect.
                $mysql_errno = 2006;
            }
        }

        if (empty($this->dbh) || 2006 === $mysql_errno) {
            if ($this->check_connection()) {
                $this->_do_query($query);
            } else {
                $this->insert_id = 0;
                return false;
            }
        }

        $return_val = "";

        if (preg_match('/^\s*(create|alter|truncate|drop)\s/i', $query)) {
            $return_val = $this->result;
        } elseif (preg_match('/^\s*(insert|delete|update|replace)\s/i', $query)) {
            $this->rows_affected = mysqli_affected_rows($this->dbh);
            // Take note of the insert_id.
            if (preg_match('/^\s*(insert|replace)\s/i', $query)) {
                $this->insert_id = mysqli_insert_id($this->dbh);
            }
            // Return number of rows affected.
            $return_val = $this->rows_affected;
        } else {
            $num_rows = 0;
            if ($this->result instanceof mysqli_result) {
                while ($row = mysqli_fetch_object($this->result)) {
                    $this->last_result[$num_rows] = $row;
                    $num_rows++;
                }
            }

            // Log and return the number of rows selected.
            $this->num_rows = $num_rows;
            $return_val = $num_rows;
        }

        return $return_val;
    }


    /**
     * Internal function to perform the mysql_query() call.
     *
     * @since 1.0.0
     *
     * @param string $query The query to run.
     */
    private function _do_query($query)
    {
        if (defined('SAVEQUERIES') && SAVEQUERIES) {
            $this->timer_start();
        }

        if (!empty($this->dbh)) {
            $this->result = mysqli_query($this->dbh, $query);
        }

    }

    public function _force_multi_query($query)
    {
        if (defined('SAVEQUERIES') && SAVEQUERIES) {
            $this->timer_start();
        }

        if (!empty($this->dbh)) {
            // $this->result = mysqli_multi_query($this->dbh, $query);

            $this->dbh->multi_query($query);
            do {
                /* store the result set in PHP */
                if ($result = $this->dbh->store_result()) {
                    while ($row = $result->fetch_row()) {
                        $this->result[] = $row[0];
                    }
                }
            } while ($this->dbh->next_result());
        }
    }


    /**
     * Retrieves an entire SQL result set from the database (i.e., many rows).
     *
     * Executes a SQL query and returns the entire SQL result.
     *
     * @since 1.0.0
     *
     * @param string $query  SQL query.
     * @param string $output Optional. Any of ARRAY_A | ARRAY_N | OBJECT | OBJECT_K constants.
     *                       With one of the first three, return an array of rows indexed
     *                       from 0 by SQL result row number. Each row is an associative array
     *                       (column => value, ...), a numerically indexed array (0 => value, ...),
     *                       or an object ( ->column = value ), respectively. With OBJECT_K,
     *                       return an associative array of row objects keyed by the value
     *                       of each row's first column's value. Duplicate keys are discarded.
     * @return array|object|null Database query results.
     */
    public function get_results($query = null, $output = OBJECT)
    {

        if ($query) {
            $this->query($query);
        } else {
            return null;
        }

        $new_array = array();
        if (OBJECT === $output) {
            // Return an integer-keyed array of row objects.
            return $this->last_result;
        } elseif (OBJECT_K === $output) {
            // Return an array of row objects with keys from column 1.
            // (Duplicates are discarded.)
            if ($this->last_result) {
                foreach ($this->last_result as $row) {
                    $var_by_ref = get_object_vars($row);
                    $key = strval(array_shift($var_by_ref));
                    if (!isset($new_array[$key])) {
                        $new_array[$key] = $row;
                    }
                }
            }
            return $new_array;
        } elseif (ARRAY_A === $output || ARRAY_N === $output) {
            // Return an integer-keyed array of...
            if ($this->last_result) {
                foreach ((array) $this->last_result as $row) {
                    if (ARRAY_N === $output) {
                        // ...integer-keyed row arrays.
                        $new_array[] = array_values(get_object_vars($row));
                    } else {
                        // ...column name-keyed row arrays.
                        $new_array[] = get_object_vars($row);
                    }
                }
            }
            return $new_array;
        }
        return null;
    }

    /**
     * Makes private properties readable for backward compatibility.
     *
     * @since 3.5.0
     *
     * @param string $name The private member to get, and optionally process.
     * @return mixed The private member.
     */
    public function __get($name)
    {
        if ('col_info' === $name) {
            $this->load_col_info();
        }

        return $this->$name;
    }


    /**
     * Makes private properties settable for backward compatibility.
     *
     * @since 3.5.0
     *
     * @param string $name  The private member to set.
     * @param mixed  $value The value to set.
     */
    public function __set($name, $value)
    {
        $protected_members = array(
            'col_meta',
            'table_charset',
            'check_current_query',
        );
        if (in_array($name, $protected_members, true)) {
            return;
        }
        $this->$name = $value;
    }

    /**
     * Makes private properties check-able for backward compatibility.
     *
     * @since 3.5.0
     *
     * @param string $name The private member to check.
     * @return bool If the member is set or not.
     */
    public function __isset($name)
    {
        return isset($this->$name);
    }

    /**
     * Makes private properties un-settable for backward compatibility.
     *
     * @since 3.5.0
     *
     * @param string $name  The private member to unset
     */
    public function __unset($name)
    {
        unset($this->$name);
    }


    /**
     * Loads the column metadata from the last query.
     *
     * @since 3.5.0
     */
    protected function load_col_info()
    {
        if ($this->col_info) {
            return;
        }

        if ($this->use_mysqli) {
            $num_fields = mysqli_num_fields($this->result);
            for ($i = 0; $i < $num_fields; $i++) {
                $this->col_info[$i] = mysqli_fetch_field($this->result);
            }
        }
    }

    /**
     * Closes the current database connection.
     *
     * @since 1.0.0
     *
     * @return bool True if the connection was successfully closed,
     *              false if it wasn't, or if the connection doesn't exist.
     */
    public function close()
    {
        if (!$this->dbh) {
            return false;
        }

        $closed = mysqli_close($this->dbh);

        if ($closed) {
            $this->dbh = null;
            // $this->ready = false;
            // $this->has_connected = false;
        }

        return $closed;
    }

    /**
     * Checks that the connection to the database is still up. If not, try to reconnect.
     *
     * If this function is unable to reconnect, it will forcibly die, or if called
     * after the {@see 'template_redirect'} hook has been fired, return false instead.
     *
     * If `$allow_bail` is false, the lack of database connection will need to be handled manually.
     *
     * @since 3.9.0
     *
     * @param bool $allow_bail Optional. Allows the function to bail. Default true.
     * @return bool|void True if the connection is up.
     */
    public function check_connection($allow_bail = true)
    {
        if (!empty($this->dbh) && mysqli_ping($this->dbh)) {
            return true;
        }

        $error_reporting = false;

        for ($tries = 1; $tries <= $this->reconnect_retries; $tries++) {
            // On the last try, re-enable warnings. We want to see a single instance
            // of the "unable to connect" message on the bail() screen, if it appears.
            if ($this->reconnect_retries === $tries) {
                error_reporting($error_reporting);
            }

            if ($this->db_connect(false)) {
                if ($error_reporting) {
                    error_reporting($error_reporting);
                }

                return true;
            }

            sleep(1);
        }
    }

    /**
     * Starts the timer, for debugging purposes.
     *
     * @since 1.0.0
     *
     * @return true
     */
    public function timer_start()
    {
        $this->time_start = microtime(true);
        return true;
    }

    /**
     * Stops the debugging timer.
     *
     * @since 1.0.0
     *
     * @return float Total time spent on the query, in seconds.
     */
    public function timer_stop()
    {
        return (microtime(true) - $this->time_start);
    }

    /**
     * Retrieves the database server version.
     *
     * @since 1.0.0
     *
     * @return string|null Version number on success, null on failure.
     */
    public function db_version()
    {
        return preg_replace('/[^0-9.].*/', '', $this->db_server_info());
    }

    /**
     * Retrieves full database server information.
     *
     * @since 1.0.0
     *
     * @return string|false Server info on success, false on failure.
     */
    public function db_server_info()
    {
        $server_info = mysqli_get_server_info($this->dbh);

        return $server_info;
    }
}
?>