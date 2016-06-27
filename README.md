# PHP Client for ClickHouse columnar DBMS 
https://clickhouse.yandex


# Документация

## Создание клиента

$client = new \ClickHouse\Client('http://127.0.0.1', 8123);


## Проверка сервера

$client->ping();

## Выполнить SELECT запрос

$client->query($sql, $formatName);

$sql - строка с sql запросом

$formatName (не обязательный параметр) - формат вывода данных.

По умолчанию: JSON

Возвращает объект класса AbstractFormat

### интерфейс AbstractFormat

getRawResult возвращает данные в сыром виде, так как их вернул сервер.

getResult  возвращает данные в виде объекта stdClass

getMeta возвращает метаданные. типы столбцов и тд

getTotals  - тотальные значения (при использовании WITH TOTALS в запросе).

getExtremes - экстремальные значения (при настройке extremes, выставленной в 1).

getRows - общее количество выведенных строчек.

getRowsBeforeLimitAtLeast - не менее скольких строчек получилось бы, если бы не было LIMIT-а. Выводится только если запрос содержит LIMIT.

fetchAll - возвращает массив со всеми строками 

fetchOne - возвращает первую строку

fetchColumn - возвращает значение указанного столбца

