## Первая часть :
SELECT u.id, concat(u.first_name, ' ', u.last_name) as name, b.author,
string_agg(b.name, ', ') as books 
FROM users u
INNER JOIN users_books ub on ub.user_id = u.id
INNER JOIN books b on b.id = ub.book_id
WHERE u.age between 7 and 17
GROUP BY u.id , b.author
HAVING count(u.id) = 2 AND count(b.author) = 2;
***
***
## Втора часть :

RESTful API для работы с курсами обмена валют для BTC

1) GET /v1/method=rates - получение всех курсов с учетом комиссии = 2% (GET запрос) в формате :
    {
    	“status”: “success”,
    	“code”: 200,
    	“data”: {
    	“USD” : <rate>,
    	...
    }
    
   /v1/method=rates&currency=usd - получение конкретного курса :
       {
       	“status”: “success”,
       	“code”: 200,
       	“data”: {
       	“USD” : <rate>,
       }
   
2) POST /v1/method=post  - запрос на обмен определенной валюты в BTC и наоборот
    params : {
        "currency_from": "USD"
        "currency_to": "BTC"
        "value": 1.00
    }

***
***
# Альтернативные варианты запросов:
    GET /v1/rates  - все курсы

    GET /v1/rates?currency=usd - определенный курс

    POST /v1/convert - запрос на обмен
    params : {
         "currency_from": "USD"
         "currency_to": "BTC"
         "value": 1.00
     }
    
    