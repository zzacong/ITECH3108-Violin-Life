
<?php

$sign_up_sql = "
INSERT INTO `user` (
  name, username, email, password, location
) VALUES (
  :name, :username, :email, :hashed_password, :location
)";

$view_sql = "
  SELECT 
    u.name AS owner_name, 
    u.username AS owner_username, 
    v.id, 
    v.title, 
    v.seeking, 
    v.description, 
    v.submitted,
    o.offer, 
    o.accepted, 
    ou.name AS offerer_name, 
    ou.username AS offerer_username    
  FROM 
    user u
  JOIN
    violin v
  ON 
    u.id = v.user_id
  JOIN
    offer o
  ON 
    v.id = o.violin_id
  JOIN
    user ou
  ON 
    o.user_id = ou.id
  ORDER BY
    v.submitted DESC, o.submitted DESC";

$make_offer_sql = "
INSERT INTO offer (
  user_id, violin_id, offer
)
VALUES (
  :user_id, :violin_id, :offer
)";

?>