-- Find the first_name and last_name of all actors who have never been in a Sci-Fi film.
-- Order by the actor_id in ascending order.

-- Put your query for q4 here


Select actor.actor_id , actor.first_name , actor.last_name from actor where actor.actor_id NOT IN (Select actor.actor_id from actor , film_actor where actor.actor_id = film_actor.actor_id and film_actor.film_id IN ( Select film.film_id from film , film_category , category where category.category_id = film_category.category_id and film_category.film_id = film.film_id and category.name = "Sci-Fi" ) ) ORDER by actor.actor_id;