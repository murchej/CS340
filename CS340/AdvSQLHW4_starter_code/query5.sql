-- Find the film_title of all films which feature both KIRSTEN PALTROW and WARREN NOLTE
-- Order the results by film_title in descending order.
--  Warning: this is a tricky one and while the syntax is all things you know, you have to think a bit oustide the box to figure out how to get a table that shows pairs of actors in movies.


-- Put your query for q5 here.


SELECT title FROM
(SELECT
film.title,
COUNT(actor.first_name) as name_count
FROM film JOIN
film_actor ON film.film_id = film_actor.film_id JOIN
actor ON film_actor.actor_id = actor.actor_id
WHERE concat(actor.first_name,' ',actor.last_name) IN ('KIRSTEN PALTROW','WARREN NOLTE')
GROUP BY film.title
ORDER BY film.title DESC)
A WHERE name_count = 2;