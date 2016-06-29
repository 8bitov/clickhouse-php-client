# PHP Client for ClickHouse columnar DBMS 
https://clickhouse.yandex


## Документация
https://8bitgroup.atlassian.net/wiki/pages/viewpage.action?pageId=33292360

## Создание клиента

$client = new \ClickHouse\Client('http://127.0.0.1', 8123);


## Проверка сервера

$client->ping();

## Выполнить SELECT запрос

$client->query($sql);

$sql - строка с sql запросом


Возвращает объект класса Statement

### интерфейс Statement

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

## Выполнить INSERT/ALERT запрос

##№ Выполнить BATCH INSERT запрос


## Системные запросы

### tables

Информация о таблицах, содержит столбцы database, name, engine типа String.

$client->system()->tables();

### databases

Информация о базах

$client->system()->databases();


### clusters

 информация о доступных в конфигурационном файле кластерах и серверах, которые в них входят.

$client->system()->clusters();


### остальное скоро будет здесь


## Настройки 

$client->settings()->max_memory_usage; //получить значение настроки

$client->settings()->max_memory_usage = 10G;  //изменить настройку для текущий сессии
