<?php


namespace ClickHouse;


class Settings
{
    /**
     *
     * Данные в ClickHouse обрабатываются по блокам (наборам кусочков столбцов). Внутренние циклы обработки одного блока достаточно эффективны, но при этом существуют заметные издержки на каждый блок. max_block_size - это рекомендация, какого размера блоки (в количестве строк) загружать из таблицы. Размер блока должен быть не слишком маленьким, чтобы издержки на каждый блок оставались незаметными, и не слишком большим, чтобы запрос с LIMIT-ом, который завершается уже после первого блока, выполнялся быстро; чтобы не использовалось слишком много оперативки при вынимании большого количества столбцов в несколько потоков; чтобы оставалась хоть какая-нибудь кэш-локальность.
     *
     * @var int
     * @default 65 536
     */
    private $max_block_size;

    /**
     * @var integer
     * @default 1 048 576
     */
    private $max_insert_block_size;


    /**
     * @var int
     * @default 8
     */
    private $max_threads;

    /**
     * @var int
     * @default 1 048 576 (1 MiB)
     */
    private $max_compress_block_size;

    /**
     * @var int
     * @default  65 536
     */
    private $min_compress_block_size;

    /**
     * @var int
     * @default  64 KiB
     */
    private $max_query_size;

    /**
     * @var int
     * @default  100 000
     */
    private $interactive_delay;
    /**
     * @var int
     * @default  10
     */
    private $connect_timeout;

    /**
     * @var int
     * @default  300
     */
    private $receive_timeout;

    /**
     * @var int
     * @default  300
     */
    private $send_timeout;

    /**
     * Блокироваться в цикле ожидания запроса в сервере на указанное количество секунд.
     *
     * @var int
     * @default  10
     */
    private $poll_interval;

    /**
     * Максимальное количество одновременных соединений с удалёнными серверами при распределённой обработке одного запроса к одной таблице типа Distributed. Рекомендуется выставлять не меньше, чем количество серверов в кластере.
     *
     * @var int
     * @default 100
     */
    private $max_distributed_connections;

    /**
     * Максимальное количество одновременных соединений с удалёнными серверами при распределённой обработке всех запросов к одной таблице типа Distributed. Рекомендуется выставлять не меньше, чем количество серверов в кластере.
     *
     * @var int
     * @default 128
     */
    private $distributed_connections_pool_size;

    /**
     * Максимальное количество попыток соединения с каждой репликой, для движка таблиц Distributed.
     * @var int
     * @default 3
     */
    private $connections_with_failover_max_tries;

    /**
     * Считать ли экстремальные значения (минимумы и максимумы по столбцам результата запроса). Принимает 0 или 1. По умолчанию - 0 (выключено).
     * @var int
     * @default 0
     */
    private $extremes;

    /**
     * Использовать ли кэш разжатых блоков.
     * @var int
     * @default 0
     */
    private $use_uncompressed_cache;

    /**
     * При использовании HTTP-интерфейса, может быть передан параметр query_id - произвольная строка, являющаяся идентификатором запроса.
     * Если в этот момент, уже существует запрос от того же пользователя с тем же query_id, то поведение определяется параметром replace_running_query.
     * @var int
     * @default 1
     */
    private $replace_running_query;

    /**
     * На какие реплики (среди живых реплик) предпочитать отправлять запрос (при первой попытке) при распределённой обработке запроса.
     * @var string
     */
    private $load_balancing = self::LOAD_BALANCING_RANDOM;

    const LOAD_BALANCING_RANDOM = 'randmom';
    const LOAD_BALANCING_NEAREST_HOSTNAME = 'nearest_hostname';
    const LOAD_BALANCING_IN_ORDER = 'in_order';

    /**
     * @var string
     */
    private $totals_mode = self::TOTALS_MODE_BEFORE_HAVING;
    const TOTALS_MODE_BEFORE_HAVING = 'before_having';
    const TOTALS_MODE_AFTER_HAVING_EXCLUSIVE = 'after_having_exclusive';
    const TOTALS_MODE_AFTER_HAVING_AUTO = 'after_having_auto';
    const TOTALS_MODE_TOTALS_AUTO_THRESHOLD = 'totals_auto_threshold';

    /**
     * Позволяет выставить коэффициент сэмплирования по умолчанию для всех запросов SELECT.
     * @var int
     * @default 1
     */
    private $default_sample;

    /**
     * Включить компиляцию запросов.
     *
     * @var 0
     */
    private $compile;

    /**
     * После скольки раз, когда скомпилированный кусок кода мог пригодиться, выполнить его компиляцию. По-умолчанию, 3.
     *
     * @var int
     */
    private $min_count_to_compile = 3;

    /**
     * Если установлено в 1 - выполнять только запросы, которые не меняют данные и настройки.
     * Для примера запросы SELECT и SHOW разрешены, а запросы INSERT и SET - запрещены.
     * Написав SET readonly = 1, вы уже не сможете выключить readonly режим в текущей сессии.
     * @var int
     */
    private $readonly;

    /**
     * Максимальное количество потребляемой памяти при выполнении запроса на одном сервере.
     *
     * @var
     * @default 10 GB
     */
    private $max_memory_usage;

    /**
     * @var \ClickHouse\Client
     */
    private $client;

    /**
     * @TODO описать все настройки max_rows_to_readmax_rows_to_read и ниже из документации
     */

    /**
     *
     * @param $connection
     * @param array $settings
     */
    public function __construct($client, array $settings)
    {
        $this->client = $client;
        $this->setUpSettings($settings);
    }

    /**
     * @param string $name
     * @return string
     */
    public function __get($name)
    {
        if (!property_exists($this, $name)) {
            throw new \InvalidArgumentException(sprintf('settings with name %s not exists', $name));
        }

        return $this->client->query('SELECT value FROM system.settings WHERE name = :name')
            ->bindValue('name', $name)
            ->fetchColumn();
    }

    public function __set($name, $value)
    {
        if (!property_exists($this, $name)) {
            throw new \InvalidArgumentException(sprintf('settings with name %s not exists', $name));
        }

        $this->{$name} = $value;
        return $this->client->execute('SET :name = :value');
    }

    public function __isset($name)
    {

    }

    private function setUpSettings($settings)
    {
        foreach ($settings as $name => $value) {
            $this->__set($name, $value);
        }
    }
}